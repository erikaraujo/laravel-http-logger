<?php

namespace ErikAraujo\HttpLogger;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use ErikAraujo\HttpLogger\Models\Log;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class DefaultLogWriter implements LogWriter
{
    public function logRequest(Request $request)
    {
        Log::create([
            'fingerprint' => $this->getFingerprint($request),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'origin' => $request->getHost(),
            'scheme' => $request->getScheme(),
            'method' => $request->method(),
            'endpoint' => $request->path(),
            'header' => json_encode($request->headers->all()),
            'query_string' => $this->getQueryString($request),
            'post_requests' => $this->getPostRequest($request),
            'files' => $this->getFiles($request)
        ]);
    }

    private function getFiles(Request $request)
    {
        return (new Collection(iterator_to_array($request->files)))
            ->map([$this, 'flatFiles'])
            ->flatten()
            ->implode(',');
    }

    private function flatFiles($file)
    {
        if ($file instanceof UploadedFile) {
            return $file->getClientOriginalName();
        }
        if (is_array($file)) {
            return array_map([$this, 'flatFiles'], $file);
        }

        return (string) $file;
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
