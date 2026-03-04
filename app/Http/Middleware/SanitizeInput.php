<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

//testt

class SanitizeInput
{
    /**
     * Input keys that should never be trimmed (e.g. passwords).
     */
    protected array $except = [
        'password',
        'password_confirmation',
        'current_password',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $cleaned = $this->clean($request->all());
        $request->merge($cleaned);

        return $next($request);
    }

    protected function clean(array $data): array
    {
        foreach ($data as $key => $value) {
            if (in_array($key, $this->except, true)) {
                continue;
            }

            if (is_array($value)) {
                $data[$key] = $this->clean($value);
            } elseif (is_string($value)) {
                // Trim whitespace and strip null bytes (common injection bypass vector)
                $value = trim($value);
                $value = str_replace("\0", '', $value);
                $data[$key] = $value;
            }
        }

        return $data;
    }
}
