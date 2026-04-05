<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - BillarSoft</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; }
        .loader {
            border: 2px solid #f3f3f3;
            border-top: 2px solid #F97316;
            border-radius: 50%;
            width: 16px;
            height: 16px;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>

<body class="bg-gray-900 flex items-center justify-center min-h-screen p-4">

<div class="bg-gray-800 w-full max-w-4xl flex flex-col md:flex-row shadow-2xl rounded-xl overflow-hidden">
    <!-- COLUMNA IZQUIERDA -->
    <div class="w-full md:w-1/2 h-64 md:h-auto relative bg-cover bg-center"
         style="background-image: url('{{ asset('img/billar_nexus.png') }}');">
        <div class="absolute inset-0 bg-gradient-to-br from-black/40 via-purple-900/30 to-pink-800/40"></div>
        <div class="absolute inset-0 flex flex-col justify-center items-center text-center p-8">
            <svg class="hidden w-20 h-20 text-white mb-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                      d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z"
                      clip-rule="evenodd"></path>
                <path d="M10 11.2a1.2 1.2 0 100-2.4 1.2 1.2 0 000 2.4z"></path>
            </svg>
            <h1 class="hidden text-white text-3xl md:text-4xl font-bold">BillarSoft</h1>
            <p class=" hidden text-gray-200 text-lg mt-2">La mejor forma de gestionar tu club.</p>
        </div>
    </div>

    <!-- COLUMNA DERECHA -->
    <div class="w-full md:w-1/2 p-8 md:p-12">

        <form id="login-form" method="POST" action="{{ route('login') }}">
            @csrf

            <h2 class="text-3xl font-bold text-white mb-2">Acceso al Sistema</h2>
            <p class="text-gray-400 mb-8">Ingresa tus credenciales para continuar.</p>

            {{-- Errores generales --}}
            @if ($errors->any())
            <div id="error-message"
                 class="bg-red-800 border border-red-600 text-red-200 px-4 py-3 rounded-lg relative mb-4">
                @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
                @endforeach
            </div>
            @endif

            <!-- Selector de Método de Login -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-300 mb-3">
                    Método de ingreso
                </label>
                <div class="flex gap-2">
                    <button type="button" id="tab-email" class="login-method-tab flex-1 px-4 py-2 rounded-lg font-medium transition-all
                        bg-orange-600 text-white"
                            data-method="email">
                        📧 Correo
                    </button>
                    <button type="button" id="tab-document" class="login-method-tab flex-1 px-4 py-2 rounded-lg font-medium transition-all
                        bg-gray-700 text-gray-300 hover:bg-gray-600"
                            data-method="numerodocumento">
                        🆔 Documento
                    </button>
                </div>
            </div>

            <!-- Usuario (email) -->
            <div id="email-field" class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-300 mb-2">
                    Correo electrónico
                </label>
                <input type="email" id="email" name="email"
                       value="{{ old('email') }}"
                       class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white
                           focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                       placeholder="ej: usuario@correo.com" required autofocus>
            </div>

            <!-- Usuario (número de documento) -->
            <div id="document-field" class="mb-4 hidden">
                <label for="numerodocumento" class="block text-sm font-medium text-gray-300 mb-2">
                    Número de documento
                </label>
                <input type="text" id="numerodocumento" name="numerodocumento"
                       value="{{ old('numerodocumento') }}"
                       class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white
                           focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                       placeholder="ej: 1234567890">
            </div>

            <!-- Contraseña -->
            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-300 mb-2">
                    Contraseña
                </label>
                <div class="relative">
                    <input type="password" id="password" name="password"
                           class="w-full px-4 py-3 pr-10 bg-gray-700 border border-gray-600 rounded-lg text-white
                      focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                           placeholder="••••••••" required>

                    <!-- Botón ojito -->
                    <button type="button"
                            id="togglePassword"
                            class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-400 hover:text-gray-200">
                        <!-- Ícono ojo (visible) -->
                        <svg id="icon-eye" xmlns="http://www.w3.org/2000/svg"
                             class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M2.46 12.12C3.73 7.94 7.52 5 12 5c4.48 0 8.27 2.94 9.54 7.12a1.12 1.12 0 010 .76C20.27 16.06 16.48 19 12 19c-4.48 0-8.27-2.94-9.54-7.12a1.12 1.12 0 010-.76z" />
                            <circle cx="12" cy="12" r="3" />
                        </svg>

                        <!-- Ícono ojo tachado (oculto por defecto) -->
                        <svg id="icon-eye-off" xmlns="http://www.w3.org/2000/svg"
                             class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M3 3l18 18M10.58 10.59A3 3 0 0113.41 13.4M9.88 4.13A8.94 8.94 0 0112 4c4.48 0 8.27 2.94 9.54 7.12a1.12 1.12 0 010 .76 9.01 9.01 0 01-4.03 4.9M6.1 6.1A8.97 8.97 0 002.46 12.12a1.12 1.12 0 000 .76A9.01 9.01 0 007.5 17.78" />
                        </svg>
                    </button>
                </div>

            </div>

            <!-- Opciones -->
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center">
                    <input id="remember" name="remember" type="checkbox"
                           class="h-4 w-4 text-orange-500 border-gray-600 rounded bg-gray-700 focus:ring-orange-400">
                    <label for="remember" class="ml-2 block text-sm text-gray-300">
                        Recordar
                    </label>
                </div>

                <a href="forgot-password" class="text-sm font-medium text-orange-500 hover:text-orange-400 hover:underline">
                    ¿Olvidó su contraseña?
                </a>
                <a href="register" class="text-sm font-medium text-green-500 hover:text-orange-400 hover:underline">
                    registrate
                </a>
            </div>

            <!-- Botón -->
            <button type="submit" id="login-button"
                    class="w-full bg-orange-600 text-white font-bold py-3 px-4 rounded-lg
                        hover:bg-orange-700 transition-all duration-300 ease-in-out flex items-center justify-center">
                <span id="button-text">Ingresar</span>
                <div id="button-loader" class="loader hidden ml-2"></div>
            </button>
        </form>

        <p class="text-center text-gray-500 text-sm mt-10">
            © 2025 BillarNexus. Todos los derechos reservados.
        </p>
    </div>
</div>

<script>
    // Manejo de selector de método de login
    const emailField = document.getElementById('email-field');
    const documentField = document.getElementById('document-field');
    const emailInput = document.getElementById('email');
    const documentInput = document.getElementById('numerodocumento');
    const emailTab = document.getElementById('tab-email');
    const documentTab = document.getElementById('tab-document');
    const loginMethodTabs = document.querySelectorAll('.login-method-tab');

    loginMethodTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const method = this.dataset.method;

            // Remover estilos activos de todos los tabs
            loginMethodTabs.forEach(t => {
                t.classList.remove('bg-orange-600', 'text-white');
                t.classList.add('bg-gray-700', 'text-gray-300', 'hover:bg-gray-600');
            });

            // Activar el tab clickeado
            this.classList.add('bg-orange-600', 'text-white');
            this.classList.remove('bg-gray-700', 'text-gray-300', 'hover:bg-gray-600');

            // Mostrar/ocultar campos
            if (method === 'email') {
                emailField.classList.remove('hidden');
                documentField.classList.add('hidden');
                emailInput.removeAttribute('disabled');
                documentInput.setAttribute('disabled', 'disabled');
                emailInput.focus();
            } else {
                emailField.classList.add('hidden');
                documentField.classList.remove('hidden');
                emailInput.setAttribute('disabled', 'disabled');
                documentInput.removeAttribute('disabled');
                documentInput.focus();
            }
        });
    });

    // Opcional: solo para efecto visual de loader (sin validar usuario en JS)
    const loginForm = document.getElementById('login-form');
    const loginButton = document.getElementById('login-button');
    const buttonText = document.getElementById('button-text');
    const buttonLoader = document.getElementById('button-loader');

    loginForm.addEventListener('submit', () => {
        loginButton.disabled = true;
        buttonText.classList.add('hidden');
        buttonLoader.classList.remove('hidden');
    });

    document.addEventListener('DOMContentLoaded', () => {
        const passwordInput = document.getElementById('password');
        const toggleBtn = document.getElementById('togglePassword');
        const iconEye = document.getElementById('icon-eye');
        const iconEyeOff = document.getElementById('icon-eye-off');

        if (passwordInput && toggleBtn) {
            toggleBtn.addEventListener('click', () => {
                const showing = passwordInput.type === 'text';
                passwordInput.type = showing ? 'password' : 'text';

                // Alternar iconos
                if (showing) {
                    iconEye.classList.remove('hidden');
                    iconEyeOff.classList.add('hidden');
                } else {
                    iconEye.classList.add('hidden');
                    iconEyeOff.classList.remove('hidden');
                }
            });
        }
    });



</script>

</body>
</html>
