<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Helpers\ControllerHelper;
use Illuminate\Http\Exceptions\HttpResponseException;

class DynamicSanitize
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $this->checkArray($request->all());

        return $next($request);

    }

    private function checkArray(array $data)
    {
        foreach ($data as $key => $value) {

            if (is_array($value)) {
                $this->checkArray($value);
                continue;
            }

            if (is_string($value) && $this->isMalicious($value)) {
                abort(422, "Invalid characters detected in field: {$key}");
                 return response()->json([
                    'error' => 'Invalid characters detected in field: {$key}'
                ], 201);
            }
        }
    }

    private function isMalicious(string $value): bool
    {
        return preg_match('/<script|<\/script>|<.*?>|javascript:|onerror=|onload=/i', $value);
    }
}
