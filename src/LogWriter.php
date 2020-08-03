<?php

namespace ErikAraujo\HttpLogger;

use Illuminate\Http\Request;

interface LogWriter
{
    public function logRequest(Request $request);
}
