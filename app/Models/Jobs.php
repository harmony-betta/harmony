<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jobs extends Model
{
    /**
     * Undocumented variable
     *
     * @var $table adalah nama tabel dari model anda
     * @var $fillable adalah kolom apa saja yang anda ijinkan untuk diisi
     * @method FunctionName adalah method yang anda definisikan untuk model
     * 
     * @return any
     * 
     * protected $table = 'Jobs';
     * 
     * protected $fillable = ['name','email','password'];
     * public function FunctionName(Type $var = null)
     * {
     *       # code..
     * }
     */

    protected $table = 'jobs';
     
    protected $fillable = ['job_name','action','data','run_at'];

}