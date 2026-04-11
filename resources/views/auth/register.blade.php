<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - BillarSoft</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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

<div class="bg-gray-800 w-full max-w-5xl flex flex-col md:flex-row shadow-2xl rounded-xl overflow-hidden">
    <!-- COLUMNA IZQUIERDA -->
    <div class="w-full md:w-2/5 h-64 md:h-auto relative bg-cover bg-center"
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
            <p class="hidden text-gray-200 text-lg mt-2">Únete a nuestro sistema de gestión.</p>
        </div>
    </div>

    <!-- COLUMNA DERECHA -->
    <div class="w-full md:w-3/5 p-6 md:p-10 overflow-y-auto max-h-screen">

        <form id="register-form" method="POST" action="{{ route('register') }}">
            @csrf

            <h2 class="text-3xl font-bold text-white mb-2">Crear cuenta</h2>
            <p class="text-gray-400 mb-6">Completa el formulario para registrarte.</p>

            {{-- Errores generales --}}
            @if ($errors->any())
            <div class="bg-red-800 border border-red-600 text-red-200 px-4 py-3 rounded-lg relative mb-4">
                @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
                @endforeach
            </div>
            @endif

            <!-- Formulario en dos columnas -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- COLUMNA IZQUIERDA: Información Personal -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-orange-400 mb-3"><svg class="inline w-5 h-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg> Información Personal</h3>
                    
                    <!-- Número de documento -->
                    <div>
                        <label for="numerodocumento" class="block text-sm font-medium text-gray-300 mb-2">
                            <svg class="inline w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0"/></svg> Número de documento
                        </label>
                        <input 
                            type="number" 
                            id="numerodocumento" 
                            name="numerodocumento" 
                            value="{{ old('numerodocumento') }}"
                            class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white
                                focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                            placeholder="ej: 1234567890"
                            required 
                            autofocus
                        />
                    </div>

                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-300 mb-2">
                            <svg class="inline w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg> Nombre completo
                        </label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            value="{{ old('name') }}"
                            class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white
                                focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                            placeholder="ej: Juan Pérez"
                            required
                        />
                    </div>

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-300 mb-2">
                            <svg class="inline w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg> Correo electrónico
                        </label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="{{ old('email') }}"
                            class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white
                                focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                            placeholder="ej: usuario@correo.com"
                            required
                        />
                    </div>
                </div>

                <!-- COLUMNA DERECHA: Rol y Seguridad -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-orange-400 mb-3"><svg class="inline w-5 h-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg> Rol y Seguridad</h3>

                    <!-- Rol / Tipo de Usuario -->
                    <div>
                        <label for="tipo" class="block text-sm font-medium text-gray-300 mb-2">
                            <svg class="inline w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg> Rol / Tipo de Usuario
                        </label>
                        <select 
                            id="tipo" 
                            name="tipo" 
                            class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white
                                focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                            required
                        >
                            <option value="">-- Selecciona un rol --</option>
                            <option value="empleado" {{ old('tipo') == 'empleado' ? 'selected' : '' }}>Empleado</option>
                            <option value="admin" {{ old('tipo') == 'admin' ? 'selected' : '' }}>Administrador</option>
                        </select>
                        
                        <!-- Info box -->
                        <div class="mt-2 p-2 bg-gray-700/50 border border-gray-600 rounded-lg">
                            <div class="text-xs text-gray-300 space-y-1">
                                <p><span class="font-semibold text-orange-400">Empleado:</span> Acceso solo a Mesas y Ventas</p>
                                <p><span class="font-semibold text-orange-400">Admin:</span> Acceso total al sistema</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-300 mb-2">
                            <svg class="inline w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg> Contraseña
                        </label>
                        <div class="relative">
                            <input 
                                type="password" 
                                id="password" 
                                name="password"
                                class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white
                                    focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent pr-10"
                                placeholder="Mínimo 8 caracteres"
                                required
                            />
                            <button type="button" onclick="togglePassword('password', 'eye-icon-1')" aria-label="Mostrar u ocultar contraseña" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-orange-500 transition">
                                <i id="eye-icon-1" class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-300 mb-2">
                            <svg class="inline w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg> Confirmar contraseña
                        </label>
                        <div class="relative">
                            <input 
                                type="password" 
                                id="password_confirmation" 
                                name="password_confirmation"
                                class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white
                                    focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent pr-10"
                                placeholder="Repite tu contraseña"
                                required
                            />
                            <button type="button" onclick="togglePassword('password_confirmation', 'eye-icon-2')" aria-label="Mostrar u ocultar confirmación de contraseña" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-orange-500 transition">
                                <i id="eye-icon-2" class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer del formulario -->
            <div class="flex flex-col sm:flex-row items-center justify-between mt-6 pt-4 border-t border-gray-700 gap-4">
                <a class="text-sm font-medium text-orange-500 hover:text-orange-400 hover:underline transition" 
                   href="{{ route('login') }}">
                    ¿Ya tienes cuenta? Inicia sesión
                </a>

                <button type="submit" id="register-button"
                        class="w-full sm:w-auto bg-orange-600 text-white font-bold py-3 px-6 rounded-lg
                            hover:bg-orange-700 transition-all duration-300 ease-in-out flex items-center justify-center">
                    <span id="button-text">Registrarse</span>
                    <div id="button-loader" class="loader hidden ml-2"></div>
                </button>
            </div>
        </form>

        <p class="text-center text-gray-500 text-sm mt-8">
            © {{ date('Y') }} BillarNexus. Todos los derechos reservados.
        </p>
    </div>
</div>

<script>
    // Efecto visual de loader al enviar
    const registerForm = document.getElementById('register-form');
    const registerButton = document.getElementById('register-button');
    const buttonText = document.getElementById('button-text');
    const buttonLoader = document.getElementById('button-loader');

    registerForm.addEventListener('submit', () => {
        registerButton.disabled = true;
        buttonText.classList.add('hidden');
        buttonLoader.classList.remove('hidden');
    });

    function togglePassword(inputId, iconId) {
        const passwordInput = document.getElementById(inputId);
        const eyeIcon = document.getElementById(iconId);
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        }
    }
</script>

</body>
</html>