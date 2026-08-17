<?php

namespace App\Http\Controllers;

use App\Models\Contribuyente;
use App\Models\Usuario;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function mostrarFormulario(): View
    {
        return view('auth.login');
    }

    public function mostrarRegistro(): View
    {
        return view('auth.register');
    }

    public function registrar(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'dni' => ['required', 'string', 'max:20'],
            'email' => ['required', 'email', 'max:100'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'dni.required' => 'Ingrese su número de DNI.',
            'email.required' => 'Ingrese su correo electrónico.',
            'email.email' => 'Ingrese un correo electrónico válido.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'La confirmación de contraseña no coincide.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $dni = trim((string) $request->input('dni'));
        $email = mb_strtolower(trim((string) $request->input('email')));
        $contribuyente = Contribuyente::query()->where('dni', $dni)->first();

        if (! $contribuyente) {
            return back()->withErrors(['dni' => 'No se encontró un contribuyente con ese DNI.'])->withInput();
        }

        if (Usuario::query()->where('dni', $dni)->orWhere('email', $email)->exists()) {
            return back()->withErrors(['email' => 'Ya existe una cuenta asociada a este DNI o correo.'])->withInput();
        }

        $codigo = (string) random_int(100000, 999999);
        $request->session()->put('registro_pendiente', [
            'codigo_contribuyente' => $contribuyente->codigo,
            'dni' => $dni,
            'email' => $email,
            'password' => Hash::make($request->input('password')),
            'codigo_verificacion' => Hash::make($codigo),
            'codigo_expira_en' => now()->addMinutes(10)->timestamp,
        ]);
        $this->enviarCodigo($email, $codigo);

        return redirect()->route('verificacion.form')->with('status', 'Enviamos un código de verificación a tu correo.');
    }

    public function mostrarVerificacion(Request $request): View|RedirectResponse
    {
        if (! $request->session()->has('registro_pendiente')) {
            return redirect()->route('login');
        }

        return view('auth.verify');
    }

    public function verificarCorreo(Request $request): RedirectResponse
    {
        $request->validate(['codigo' => ['required', 'digits:6']]);
        $registroPendiente = $request->session()->get('registro_pendiente');

        if (! $registroPendiente) {
            return redirect()->route('register')->with('status', 'Inicia nuevamente el registro.');
        }

        if (now()->timestamp >= (int) $registroPendiente['codigo_expira_en']) {
            return back()->withErrors(['codigo' => 'El código venció. Solicita uno nuevo.']);
        }

        if (! Hash::check($request->input('codigo'), $registroPendiente['codigo_verificacion'])) {
            return back()->withErrors(['codigo' => 'El código ingresado no es válido.']);
        }

        Usuario::create([
            'codigo_contribuyente' => $registroPendiente['codigo_contribuyente'],
            'dni' => $registroPendiente['dni'],
            'email' => $registroPendiente['email'],
            'password' => $registroPendiente['password'],
            'email_verified_at' => now(),
            'codigo_verificacion' => '',
            'codigo_expira_en' => now(),
        ]);
        $request->session()->forget('registro_pendiente');

        return redirect()->route('login')->with('status', 'Correo confirmado. Ya puedes iniciar sesión.');
    }

    public function reenviarCodigo(Request $request): RedirectResponse
    {
        $registroPendiente = $request->session()->get('registro_pendiente');

        if (! $registroPendiente) {
            return redirect()->route('register');
        }

        $codigo = (string) random_int(100000, 999999);
        $registroPendiente['codigo_verificacion'] = Hash::make($codigo);
        $registroPendiente['codigo_expira_en'] = now()->addMinutes(10)->timestamp;
        $request->session()->put('registro_pendiente', $registroPendiente);
        $this->enviarCodigo($registroPendiente['email'], $codigo);

        return back()->with('status', 'Te enviamos un nuevo código de verificación.');
    }

    public function identificar(Request $request): RedirectResponse
    {
        $request->validate([
            'dni' => ['required', 'string', 'max:20'],
            'password' => ['required', 'string'],
        ], [
            'dni.required' => 'Ingrese su número de DNI.',
            'password.required' => 'Ingrese su contraseña.',
        ]);

        $usuario = Usuario::query()->where('dni', trim((string) $request->input('dni')))->first();

        if (! $usuario || ! Hash::check($request->input('password'), $usuario->password)) {
            return back()->withErrors(['dni' => 'Los datos de acceso no son válidos.'])->withInput($request->only('dni'));
        }

        if (! $usuario->email_verified_at) {
            return redirect()->route('register')->with('status', 'Esta cuenta no fue confirmada. Regístrate nuevamente para recibir un código.');
        }

        $request->session()->regenerate();
        $request->session()->put('contribuyente_codigo', $usuario->codigo_contribuyente);
        $request->session()->put('contribuyente_nombre', $usuario->contribuyente->nombre);
        $request->session()->put('contribuyente_expira_en', now()->addMinutes(30)->timestamp);

        return redirect()->route('consulta.index');
    }

    public function salir(Request $request): RedirectResponse
    {
        $request->session()->forget(['contribuyente_codigo', 'contribuyente_nombre', 'contribuyente_expira_en']);
        $request->session()->regenerate();

        return redirect()->route('login')->with('status', 'Sesión finalizada correctamente.');
    }

    private function enviarCodigo(string $email, string $codigo): void
    {
        Mail::raw("Tu código de verificación es: {$codigo}. Vence en 10 minutos. No compartas este código con nadie.", function ($message) use ($email) {
            $message->to($email)->subject('Código de verificación - Estado de Cuenta');
        });
    }
}
