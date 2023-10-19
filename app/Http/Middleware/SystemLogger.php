<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Log;

class SystemLogger
{
    public array $except
        = [

        ];

    public function handle($request, \Closure $next)
    {
        return $next($request);
    }


    public function terminate(Request $request, Response $response)
    {
        if ($this->inExceptArray($request) || !$this->shouldLog($request)) {
            return;
        }
        $files = (new Collection(iterator_to_array($request->files)))
            ->map([$this, 'flatFiles'])
            ->flatten();
        Log::create([
            'geoip'      => $request->getClientIp(),
            'user_id'    => auth('web')->guest() ? 0 : $request->user('web')->getAuthIdentifier(),
            'username'   => auth('web')->guest() ? '' : $request->user('web')->username,
            'user_agent' => $request->userAgent(),
            'request'    => [
                'query'       => $request->query,
                'body'        => $request->getContent(),
                'form_params' => $request->post(),
                'files'       => $files
            ],
            'uri'        => $request->getPathInfo(),
            'method'     => $request->method(),
            'response'   => [
                'code' => $response->getStatusCode(),
                'body' => Str::isJson($response->getContent()) ? json_decode($response->getContent()) : $response->getContent()
            ]
        ]);
    }

    public function flatFiles($file)
    {
        if ($file instanceof UploadedFile) {
            return $file->getClientOriginalName();
        }
        if (is_array($file)) {
            return array_map([$this, 'flatFiles'], $file);
        }

        return (string)$file;
    }

    protected function shouldLog(Request $request): bool
    {
        return in_array(strtolower($request->method()), ['post', 'put', 'patch', 'delete']);
    }

    /**
     * @param Request $request
     * @return bool
     */
    protected function inExceptArray($request)
    {
        foreach ($this->except as $except) {
            if ($except !== '/') {
                $except = trim($except, '/');
            }

            if ($request->fullUrlIs($except) || $request->is($except)) {
                return true;
            }
        }

        return false;
    }
}

