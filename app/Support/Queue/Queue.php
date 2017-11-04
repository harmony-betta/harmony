<?php

namespace App\Support\Queue;

use App\Models\Jobs;

class Queue
{

    protected $jobs;

    public function pushQueue($job_name, $action, $data, $delay = 1)
    {

        $selectedTime = date('h:i:s');

        if(!is_null($delay)){
            $endTime = strtotime("+$delay minutes", strtotime($selectedTime));
        }else{
            $endTime = strtotime($selectedTime);
        }

        $jobs = Jobs::create([
            'job_name' => $job_name,
            'action' => $action,
            'run_at' => date('Y-m-d h:i:s', $endTime),
            'data' => json_encode($data),
        ]);
    }

    public function getQueue($job_name, $action)
    {
        $this->jobs = Jobs::where(['job_name' => $job_name, "action" => $action])->first()->toArray();

        return $this->jobs;
    }

    public function getJob()
    {
        $ex_job = explode('\\', $this->jobs['job_name']);
        return end($ex_job);
    }

    public function getAction()
    {
        $ex_act = explode('\\', $this->jobs['action']);
        return end($ex_act);
    }

    public function process()
    {
        $time = $this->jobs['run_at'];
        $script = '
        <script>
        var updateClock = function() {
            function pad(n) {
                return (n < 10) ? "0" + n : n;
            }
        
            var now = new Date();
            var s = pad(now.getUTCHours()) + ":" +
                    pad(now.getUTCMinutes()) + ":" +
                    pad(now.getUTCSeconds());

            document.getElementById("time").innerHTML = now.toLocaleString();
        
            var delay = 1000 - (now % 1000);
            setTimeout(updateClock, delay);
        };
        </script>
        ';

        // do{
            
        //     return "$script Waiting prosess.. in $time. But now time is <span id='time'><script>updateClock()</script></span>";

        // } while($time == date('Y-m-d h:i:s'));
        if ($time <= date('Y-m-d h:i:s')) {
            return "$script Waiting prosess.. in $time. But now time is <span id='time'><script>updateClock()</script></span>";
        }else{
            self::work();
        }
    }

    public function work()
    {
        $class_name     = self::getJob();
        $method_name    = self::getAction();

        $class_with_namespace = $this->jobs['job_name'];

        $file           = dirname(dirname(dirname(__DIR__))).'/app/Jobs/'.$class_name.'.php';

        if(file_exists($file)){

            if (class_exists($class_with_namespace)) {

                if(method_exists($class_with_namespace, $method_name))
                    call_user_func([$class_with_namespace, $method_name]);
                
            }else{
                return "Class $class_name not found!";
            }

        }else{
            return "Not Found $class_name.php";
        }
    }

}