<?php

namespace ErikAraujo\HttpLogger\Middlewares;

use Closure;
use Illuminate\Http\Request;
use ErikAraujo\HttpLogger\LogWriter;

class HttpLogger
{
    protected $logWriter;

    public function __construct(LogWriter $logWriter)
    {
        $this->logWriter = $logWriter;
    }

    public function handle(Request $request, Closure $next)
    {
        if ($this->shouldLogRequest($request)) {
            $this->logWriter->logRequest($request);
        }

        return $next($request);
    }

    private function shouldLogRequest(Request $request): bool
    {
        return in_array(strtolower($request->method()), config('http-logger.methods'));
    }
}
