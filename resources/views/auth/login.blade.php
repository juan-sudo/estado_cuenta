@extends('layouts.app')
@section('titulo', 'Ingresar')
@section('pagina_ingreso', true)
@section('contenido')
<div class="login-page">
    <section class="login-visual" aria-label="Municipalidad"><div class="login-visual__content"><a href="{{ url('/') }}" class="brand brand--light"><span class="brand__mark">EC</span><span>Estado de Cuenta</span></a><div class="login-visual__message"><span class="eyebrow">Servicios en línea</span><h1>Tu información municipal, siempre a tu alcance.</h1><p>Consulta tu estado de cuenta de forma segura desde cualquier lugar.</p></div><p class="login-visual__footer">© {{ date('Y') }} Municipalidad · Plataforma de atención al contribuyente</p></div></section>
    <main class="login-panel"><div class="login-card"><a href="{{ url('/') }}" class="brand brand--dark lg:hidden"><span class="brand__mark">EC</span><span>Estado de Cuenta</span></a><div class="login-card__heading"><span class="login-card__icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z"/><path d="M4 21a8 8 0 0 1 16 0"/></svg></span><div><p class="eyebrow text-primary-600">Acceso ciudadano</p><h2>Ingresa a tu cuenta</h2></div></div><p class="login-card__description">Utiliza tu DNI y la contraseña creada durante tu registro.</p>
        @if(session('status'))<div class="login-alert login-alert--success" role="status">{{ session('status') }}</div>@endif
        @if($errors->any())<div class="login-alert login-alert--error" role="alert">@foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach</div>@endif
        <form method="POST" action="{{ route('login.attempt') }}" class="mt-8 space-y-5">@csrf
            <div><label for="dni" class="label-field">Número de DNI</label><div class="input-with-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M7 10h4M7 14h7M16 11h1M16 14h1"/></svg><input type="text" name="dni" id="dni" value="{{ old('dni') }}" class="input-field" placeholder="Ej. 45678912" inputmode="numeric" required autofocus></div></div>
            <div><label for="password" class="label-field">Contraseña</label><div class="input-with-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="5" y="10" width="14" height="11" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/></svg><input type="password" name="password" id="password" class="input-field" placeholder="Tu contraseña" autocomplete="current-password" required></div></div>
            <button type="submit" class="btn-primary login-submit">Ingresar <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg></button>
        </form>
        <p class="auth-switch">¿Aún no tienes una cuenta? <a href="{{ route('register') }}">Regístrate aquí</a></p><p class="login-security"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="5" y="10" width="14" height="11" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/></svg>Protegido con verificación de correo.</p>
    </div></main>
</div>
@endsection
