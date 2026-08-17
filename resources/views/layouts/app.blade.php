<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('titulo', 'Estado de Cuenta') · Municipalidad</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 font-sans antialiased text-slate-800">
    @hasSection('pagina_ingreso')
        @yield('contenido')
    @else
        <nav class="border-b border-slate-200 bg-white/95 shadow-sm backdrop-blur">
            <div class="mx-auto flex max-w-6xl items-center justify-between px-4 py-3.5 sm:px-6">
                <a href="{{ route('consulta.index') }}" class="flex items-center gap-3" aria-label="Ir a la consulta"><div class="flex h-10 w-10 items-center justify-center rounded-xl bg-primary-600 text-sm font-extrabold text-white shadow-lg shadow-primary-600/20">EC</div><div><span class="block text-sm font-bold leading-4 text-slate-900">Estado de Cuenta</span><span class="block text-xs text-slate-500">Servicios municipales</span></div></a>
                @if(session('contribuyente_codigo'))
                    <div class="flex items-center gap-3 sm:gap-4">
                        <div class="hidden text-right sm:block"><span class="block text-sm font-semibold text-slate-700">{{ session('contribuyente_nombre') }}</span><span class="block text-xs text-slate-400">{{ session('contribuyente_codigo') }}</span></div>
                        <form method="POST" action="{{ route('logout') }}">@csrf <button type="submit" class="btn-secondary !px-4 !py-2 text-xs">Salir</button></form>
                    </div>
                @endif
            </div>
        </nav>
        <main class="mx-auto max-w-6xl px-4 py-7 sm:px-6 sm:py-10">
            @if(session('status'))<div class="mb-6 rounded-lg bg-primary-50 px-4 py-3 text-sm text-primary-700 ring-1 ring-inset ring-primary-100">{{ session('status') }}</div>@endif
            @yield('contenido')
        </main>
        <footer class="mt-16 border-t border-slate-200 py-7 text-center text-xs text-slate-400">Sistema de Consulta de Estado de Cuenta &middot; {{ date('Y') }}</footer>
    @endif
</body>
</html>
