<?php

namespace ErikAraujo\HttpLogger;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class DefaultLogWriter implements LogWriter
{
    public function logRequest(Request $request)
    {
        $model = config('http-logger.model');
        $log = new $model();

        $log->fingerprint = $this->getFingerprint($request);
        $log->ip_address = $request->ip();
        $log->user_agent = $request->userAgent();
        $log->origin = $request->getHost();
        $log->scheme = $request->getScheme();
        $log->method = $request->method();
        $log->endpoint = $request->path();
        $log->header = json_encode($request->headers->all());
        $log->query_string = $this->getQueryString($request);
        $log->post_requests = $this->getPostRequest($request);
        $log->files = $this->getFiles($request);

        $log->save();
    }

    private function getFiles(Request $request)
    {
        return (new Collection(iterator_to_array($request->files)))
            ->map(function ($file) {
                if ($file instanceof UploadedFile) {
                    return $file->getClientOriginalName();
                }
                if (is_array($file)) {
                    return array_map([$this, 'flatFiles'], $file);
                }

                return (string) $file;
            })->flatten()->implode(',');
    }

    private function getFingerprint($request)
    {
        return hash('sha1', json_encode([
            $request->url(),
            $request->method(),
            $request->ips(),
            $request->getQueryString(),
            $request->header(),
            $request->except(config('http-logger.except'))
        ]));
    }

    private function getQueryString($request)
    {
        if (empty($request->query())) {
            return null;
        }

        return json_encode($request->query());
    }

    private function getPostRequest($request)
    {
        $all = $request->except(config('http-logger.except'));
        $query = $request->query();

        $postRequest = array_diff($all, $query);
        if (empty($postRequest)) {
            return null;
        }
        return json_encode($postRequest);
    }
}
