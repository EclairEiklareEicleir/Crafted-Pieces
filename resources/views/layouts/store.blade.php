<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>the_crafted_pieces</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen text-brand-ink antialiased">

    {{-- NAVBAR --}}
    <x-navbar />

    {{-- PAGE CONTENT --}}
    @yield('content')

    {{-- AUTH MODAL --}}
    <x-auth-modal />

    {{-- Footer --}}
    <x-footer />

    <x-brand-loader />

    {{-- AUTO OPEN MODAL ON ERROR --}}
    @if ($errors->any())
        <script>
            window.addEventListener('DOMContentLoaded', () => {
                openAuthModal();
                showLogin();
            });
        </script>
    @endif

    {{-- GLOBAL SCRIPTS --}}
    <script>
        let authState = 'login'; // "login" | "register"

        function openAuthModal() {
            const modal = document.getElementById('auth-modal');
            modal.classList.remove('hidden');

            requestAnimationFrame(() => {
                modal.classList.add('opacity-100');
                modal.classList.remove('opacity-0');
            });

            renderAuth();
        }

        function closeAuthModal() {
            const modal = document.getElementById('auth-modal');

            modal.classList.remove('opacity-100');
            modal.classList.add('opacity-0');

            setTimeout(() => {
                modal.classList.add('hidden');
            }, 200);
        }

        function resetForm(formId) {
            const form = document.getElementById(formId);
            if (form) form.reset();
        }

        function renderAuth() {
            const login = document.getElementById('login-form');
            const register = document.getElementById('register-form');

            if (authState === 'login') {

                register.classList.add('hidden', 'opacity-0', 'translate-x-6');
                login.classList.remove('hidden');

                requestAnimationFrame(() => {
                    login.classList.remove('opacity-0', '-translate-x-6');
                    login.classList.add('opacity-100', 'translate-x-0');
                });

            } else {

                login.classList.add('hidden', 'opacity-0', '-translate-x-6');
                register.classList.remove('hidden');

                requestAnimationFrame(() => {
                    register.classList.remove('opacity-0', 'translate-x-6');
                    register.classList.add('opacity-100', 'translate-x-0');
                });
            }
        }

        function showLogin() {
            authState = 'login';

            resetForm('register-form');
            renderAuth();
        }

        function showRegister() {
            authState = 'register';

            resetForm('login-form');
            renderAuth();
            initRegisterGuard();
        }

        function initRegisterGuard() {
            const checkbox = document.getElementById('privacy-check');
            const button = document.getElementById('register-btn');

            if (!checkbox || !button) return;

            function updateButtonState() {
                button.disabled = !checkbox.checked;

                if (checkbox.checked) {
                    button.classList.remove('opacity-50', 'cursor-not-allowed');
                } else {
                    button.classList.add('opacity-50', 'cursor-not-allowed');
                }
            }

            checkbox.addEventListener('change', updateButtonState);
            updateButtonState();
        }

        function togglePasswordVisibility(button) {
            const targetId = button.dataset.passwordTarget;
            const passwordInput = document.getElementById(targetId);
            if (!passwordInput) return;

            const visible = passwordInput.type === 'text';
            passwordInput.type = visible ? 'password' : 'text';

            button.innerHTML = visible
                ? `
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-5 w-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8a4 4 0 0 1 0 8 4 4 0 0 1 0-8Z" />
                    </svg>
                  `
                : `
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-5 w-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.5 8.5 15.5 15.5" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.5 8.5 8.5 15.5" />
                    </svg>
                  `;
        }

        document.addEventListener('DOMContentLoaded', () => {
            initRegisterGuard();
        });
    </script>

</body>
</html>