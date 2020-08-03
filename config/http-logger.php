<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Http Logger Model
    |--------------------------------------------------------------------------
    |
    | The Log Model
    |
    */
    'model' => \ErikAraujo\HttpLogger\Models\Log::class,

    /*
    |--------------------------------------------------------------------------
    | Http Logger Methods
    |--------------------------------------------------------------------------
    |
    | Add HTTP methods that will get logged
    |
    */
    'methods' => ['post', 'put', 'patch', 'delete'],

    /*
    |--------------------------------------------------------------------------
    | Http Logger Writer
    |--------------------------------------------------------------------------
    |
    | The log writer used to write the request log to the database.
    | Should implement `LogWriter`.
    |
    */
    'log_writer' => \ErikAraujo\HttpLogger\DefaultLogWriter::class,

    /*
    |--------------------------------------------------------------------------
    | Http Logger Table
    |--------------------------------------------------------------------------
    |
    | Add fields that should never get logged.
    |
    */
    'except' => [
        'password',
        'password_confirmation',
    ],

    /*
    |--------------------------------------------------------------------------
    | Http Logger Table
    |--------------------------------------------------------------------------
    |
    | Feel free to change the table name where all the logs are going to get
    | stored. Make sure to also rename the table in the generated migration file.
    |
    */
    'table' => 'logs',

];
