<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsCustomer
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || $request->user()->isAdmin()) {
            return redirect()->route('admin.dashboard')
                ->withErrors(['akses' => 'Admin tidak dapat mengakses halaman customer.']);
        }

        return $next($request);
    }
}
