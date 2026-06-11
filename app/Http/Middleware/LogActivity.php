<?php

namespace App\Http\Middleware;

use App\Models\ActivityLog;
use Closure;
use Illuminate\Http\Request;

class LogActivity
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (auth()->check() && !$request->is('api/*')) {
            ActivityLog::create([
                'user_id' => auth()->id(),
                'activity' => $this->resolveActivity($request),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        return $response;
    }

    private function resolveActivity(Request $request): string
    {
        $method = $request->method();
        $path = $request->path();

        return match ($method) {
            'GET' => "Mengakses: {$path}",
            'POST' => "Membuat data pada: {$path}",
            'PUT', 'PATCH' => "Memperbarui data pada: {$path}",
            'DELETE' => "Menghapus data pada: {$path}",
            default => "Aktivitas pada: {$path}",
        };
    }
}
