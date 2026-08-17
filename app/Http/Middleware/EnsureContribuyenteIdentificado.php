<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureContribuyenteIdentificado
{
    public function handle(Request $request, Closure $next): Response
    {
        $expiraEn = $request->session()->get('contribuyente_expira_en');

        if (! $request->session()->has('contribuyente_codigo') || ! $expiraEn) {
            return redirect()
                ->route('login')
                ->with('status', 'Debe identificarse con su DNI y nombre completo para continuar.');
        }

        if (now()->timestamp >= (int) $expiraEn) {
            $request->session()->forget([
                'contribuyente_codigo',
                'contribuyente_nombre',
                'contribuyente_expira_en',
            ]);
            $request->session()->regenerate();

            return redirect()
                ->route('login')
                ->with('status', 'Tu sesión ha finalizado después de 30 minutos. Identifícate nuevamente para continuar.');
        }

        return $next($request);
    }
}
