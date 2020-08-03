<?php

namespace ErikAraujo\HttpLogger\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    public $table;

    public function __construct()
    {
        $this->table = config('http-logger.table');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fingerprint',
        'ip',
        'user_agent',
        'origin',
        'scheme',
        'method',
        'endpoint',
        'header',
        'query_string',
        'post_requests',
        'files',
    ];
}
