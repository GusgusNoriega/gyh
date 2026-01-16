@extends('layouts.guest')

@section('title', 'Iniciar Sesión')

@section('content')
<div class="min-h-screen relative overflow-hidden">
  <!-- Fondo decorativo -->
  <div class="pointer-events-none absolute inset-0">
    <div class="absolute -top-32 -left-28 size-[520px] rounded-full blur-3xl opacity-30"
         style="background: radial-gradient(circle at 30% 30%, var(--c-primary), transparent 60%);"></div>
    <div class="absolute -bottom-36 -right-28 size-[560px] rounded-full blur-3xl opacity-25"
         style="background: radial-gradient(circle at 70% 70%, var(--c-primary-2), transparent 60%);"></div>
    <div class="absolute inset-0 opacity-[0.06]"
         style="background-image: radial-gradient(circle at 1px 1px, #fff 1px, transparent 0); background-size: 22px 22px;"></div>
  </div>

  <div class="relative flex items-center justify-center px-4 py-12 sm:px-6 lg:px-8">
    <div class="w-full max-w-md">
      <!-- Card -->
      <div class="rounded-[var(--radius)] bg-[var(--c-surface)] ring-1 ring-[var(--c-border)] shadow-soft overflow-hidden">
        <div class="p-7 sm:p-8">
          <div class="flex flex-col items-center text-center">
            <div class="size-14 rounded-2xl bg-[var(--c-elev)] ring-1 ring-[var(--c-border)] grid place-items-center overflow-hidden">
              <img src="{{ asset('img/logo.png') }}" alt="Systems GG" class="h-9 w-auto" />
            </div>
            <h1 class="mt-4 text-2xl font-semibold tracking-tight">Iniciar sesión</h1>
            <p class="mt-1 text-sm text-[var(--c-muted)]">Ingresa tus credenciales para acceder</p>
          </div>

          @if (session('status'))
            <div class="mt-5 rounded-2xl bg-[var(--c-elev)] ring-1 ring-[var(--c-border)] px-4 py-3 text-sm text-[var(--c-text)]">
              {{ session('status') }}
            </div>
          @endif

          @if ($errors->any())
            <div class="mt-5 rounded-2xl bg-red-500/10 ring-1 ring-red-500/30 px-4 py-3 text-sm text-red-200" role="alert">
              <p class="font-medium">Revisa los datos e intenta nuevamente:</p>
              <ul class="mt-2 list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <form class="mt-6 space-y-5" action="{{ url('/login') }}" method="POST" novalidate>
            @csrf

            <!-- Email -->
            <div>
              <label for="email" class="text-sm font-medium text-[var(--c-muted)]">Correo electrónico</label>
              <div class="mt-2 relative">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-[var(--c-muted)]">
                  <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 4h16v16H4z" opacity="0"/>
                    <path d="M4 6l8 7 8-7"/>
                    <path d="M4 18h16V6H4v12z"/>
                  </svg>
                </span>
                <input
                  id="email"
                  name="email"
                  type="email"
                  autocomplete="email"
                  inputmode="email"
                  required
                  value="{{ old('email') }}"
                  class="w-full rounded-2xl bg-[var(--c-elev)] ring-1 ring-[var(--c-border)] pl-11 pr-4 py-3 text-sm placeholder:text-[var(--c-muted)] focus:outline-none focus:ring-2 focus:ring-[var(--c-primary)] @error('email') ring-red-500/50 focus:ring-red-500 @enderror"
                  placeholder="tu@correo.com"
                />
              </div>
              @error('email')
                <p class="mt-2 text-sm text-red-200">{{ $message }}</p>
              @enderror
            </div>

            <!-- Password -->
            <div x-data="{ show: false }">
              <div class="flex items-center justify-between">
                <label for="password" class="text-sm font-medium text-[var(--c-muted)]">Contraseña</label>
                <button type="button" class="text-xs text-[var(--c-muted)] hover:text-[var(--c-text)]" @click="show = !show">
                  <span x-show="!show">Mostrar</span>
                  <span x-show="show" x-cloak>Ocultar</span>
                </button>
              </div>
              <div class="mt-2 relative">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-[var(--c-muted)]">
                  <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 17a2 2 0 0 0 2-2v-1a2 2 0 0 0-4 0v1a2 2 0 0 0 2 2z"/>
                    <path d="M18 10V8a6 6 0 1 0-12 0v2"/>
                    <rect x="5" y="10" width="14" height="12" rx="2"/>
                  </svg>
                </span>
                <input
                  id="password"
                  name="password"
                  :type="show ? 'text' : 'password'"
                  autocomplete="current-password"
                  required
                  class="w-full rounded-2xl bg-[var(--c-elev)] ring-1 ring-[var(--c-border)] pl-11 pr-4 py-3 text-sm placeholder:text-[var(--c-muted)] focus:outline-none focus:ring-2 focus:ring-[var(--c-primary)] @error('password') ring-red-500/50 focus:ring-red-500 @enderror"
                  placeholder="••••••••"
                />
              </div>
              @error('password')
                <p class="mt-2 text-sm text-red-200">{{ $message }}</p>
              @enderror
            </div>

            <div class="flex items-center justify-between">
              <label class="inline-flex items-center gap-2 text-sm text-[var(--c-muted)] select-none">
                <input id="remember-me" name="remember" type="checkbox" class="size-4 rounded border-white/20 bg-[var(--c-elev)] text-[var(--c-primary)] focus:ring-[var(--c-primary)]" />
                <span>Recordarme</span>
              </label>

              <a href="#" class="text-sm text-[var(--c-muted)] hover:text-[var(--c-text)]">¿Olvidaste tu contraseña?</a>
            </div>

            <button
              type="submit"
              class="w-full rounded-2xl px-4 py-3 text-sm font-semibold text-white ring-1 ring-white/10 shadow-soft hover:brightness-110 active:brightness-95 focus:outline-none focus:ring-2 focus:ring-[var(--c-primary)]"
              style="background: linear-gradient(135deg, var(--c-primary), var(--c-primary-2));"
            >
              <span class="inline-flex items-center justify-center gap-2">
                <svg class="size-5 opacity-90" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M12 17a2 2 0 0 0 2-2v-1a2 2 0 0 0-4 0v1a2 2 0 0 0 2 2z"/>
                  <path d="M18 10V8a6 6 0 1 0-12 0v2"/>
                  <rect x="5" y="10" width="14" height="12" rx="2"/>
                </svg>
                <span>Iniciar sesión</span>
              </span>
            </button>
          </form>
        </div>

        <div class="px-7 py-4 sm:px-8 bg-[var(--c-elev)] border-t border-[var(--c-border)]">
          <p class="text-xs text-[var(--c-muted)] text-center">
            © <span>{{ date('Y') }}</span> Systems GG. Todos los derechos reservados.
          </p>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
