<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar sesion - Sistema Administrativo de Ventas y Cotizaciones</title>
    <link rel="icon" href="{{ asset('images/logo-comunitec.svg') }}" type="image/svg+xml">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 antialiased">
    <main class="grid min-h-screen lg:grid-cols-[minmax(420px,0.9fr)_520px]">
        <section class="hidden border-r border-slate-200 bg-white px-8 py-8 lg:flex lg:items-center lg:justify-center">
            <x-brand-logo class="h-auto w-full max-w-lg" />
        </section>

        <section class="flex min-h-screen items-center justify-center px-5 py-6">
            <div class="w-full max-w-sm rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                <div class="mb-6">
                    <x-brand-logo class="mx-auto mb-5 h-auto w-48 lg:hidden" />
                    <h1 class="text-xl font-semibold text-slate-950">Iniciar sesion</h1>
                </div>

                <form method="POST" action="{{ route('login.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="email" class="form-label">Correo electronico</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" autofocus class="form-input @error('email') form-input-error @enderror">
                        @error('email')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="form-label">Contrasena</label>
                        <input id="password" name="password" type="password" autocomplete="current-password" class="form-input @error('password') form-input-error @enderror">
                        @error('password')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <label class="flex items-center gap-3 text-sm text-slate-600">
                        <input type="checkbox" name="remember" value="1" class="size-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                        Mantener sesion iniciada
                    </label>

                    <button type="submit" class="btn-primary w-full">Entrar</button>
                </form>
            </div>
        </section>
    </main>
</body>
</html>
