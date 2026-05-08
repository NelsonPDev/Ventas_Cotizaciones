<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Sistema Administrativo de Ventas y Cotizaciones</title>
    <link rel="icon" href="{{ asset('images/logo-comunitec.svg') }}" type="image/svg+xml">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 antialiased">
    <div class="min-h-screen lg:grid lg:grid-cols-[280px_1fr]">
        <aside id="sidebar" class="fixed inset-y-0 left-0 z-40 w-72 -translate-x-full border-r border-slate-200 bg-white transition-transform duration-200 lg:static lg:w-auto lg:translate-x-0">
            <div class="border-b border-slate-200 px-5 py-4">
                <x-brand-logo class="h-auto w-full max-w-44" />
                <p class="mt-2 truncate text-xs text-slate-500">{{ auth()->user()->role?->nombre }}</p>
            </div>

            <nav class="space-y-1 px-3 py-4">
                <a href="{{ route('dashboard') }}" class="nav-link @if(request()->routeIs('dashboard')) nav-link-active @endif">Dashboard</a>
                @if(auth()->user()->hasRole('Administrador'))
                    <a href="{{ route('usuarios.index') }}" class="nav-link @if(request()->routeIs('usuarios.*')) nav-link-active @endif">Usuarios</a>
                @endif
                <a href="{{ route('clientes.index') }}" class="nav-link @if(request()->routeIs('clientes.*')) nav-link-active @endif">Clientes</a>
                <a href="{{ route('cotizaciones.index') }}" class="nav-link @if(request()->routeIs('cotizaciones.*')) nav-link-active @endif">Cotizaciones</a>
                <a href="{{ route('ventas.index') }}" class="nav-link @if(request()->routeIs('ventas.*')) nav-link-active @endif">Ventas</a>
                @if(auth()->user()->hasRole('Administrador', 'Usuario de Consulta'))
                    <a href="{{ route('reportes.index') }}" class="nav-link @if(request()->routeIs('reportes.*')) nav-link-active @endif">Reportes</a>
                @endif
            </nav>
        </aside>

        <div id="sidebar-overlay" class="fixed inset-0 z-30 hidden bg-slate-950/40 lg:hidden"></div>

        <main class="min-w-0">
            <header class="sticky top-0 z-20 flex h-16 items-center justify-between border-b border-slate-200 bg-white/95 px-4 backdrop-blur lg:px-8">
                <button type="button" id="sidebar-toggle" class="inline-flex size-10 items-center justify-center rounded-lg border border-slate-200 text-slate-700 lg:hidden" aria-label="Abrir menu">
                    <span class="text-xl leading-none">=</span>
                </button>

                <div class="hidden min-w-0 lg:block">
                    <p class="text-sm text-slate-500">Ventas y Cotizaciones</p>
                    <h1 class="truncate text-lg font-semibold">@yield('page-title', 'Dashboard')</h1>
                </div>

                <div class="ml-auto flex items-center gap-3">
                    <div class="hidden text-right sm:block">
                        <p class="text-sm font-medium">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-slate-500">{{ auth()->user()->email }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="rounded-lg border border-slate-200 px-3 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-100">Salir</button>
                    </form>
                </div>
            </header>

            <section class="px-4 py-6 lg:px-8">
                @if(session('status'))
                    <div class="mb-5 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800" data-alert>
                        {{ session('status') }}
                    </div>
                @endif

                @yield('content')
            </section>
        </main>
    </div>
</body>
</html>
