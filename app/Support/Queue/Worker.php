<?php

namespace App\Support\Queue;

class Worker {
    /**
     * Store the semaphore queue handler.
     * @var resource
     */
    private $queue = NULL;
    /**
     * Store an instance of the read Message
     * @var Message
     */
    private $message = NULL;
    /**
     * Constructor: Setup our enviroment, load the queue and then
     * process the message.
     */
    public function __construct() {
        # Get the queue
        $this->queue = Queue::getQueue();
        # Now process
        $this->process();
    }
    private function process() {
        $messageType = NULL;
        $messageMaxSize = 1024;
        # Loop over the queue
        while(msg_receive($this->queue, QUEUE_TYPE_START, $messageType, $messageMaxSize, $this->message)) {
            # We have the message, fire back
             $this->complete($messageType, $this->message);
            # Reset the message state
            $messageType = NULL;
            $this->message = NULL;
        }    
    }
    /**
     * complete: Handle the message we read from the queue
     *
     * @param $messageType int - The type we actually got, not what we desired
     * @param $message Message - The actual object
     */
    private function complete($messageType, Message $message) {
        # Generic method
        echo $message->getKey();    
    }
}