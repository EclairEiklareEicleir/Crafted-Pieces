<div id="auth-modal"
     class="hidden fixed inset-0 z-50 flex items-center justify-center">

    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>

    <div class="relative w-full max-w-md rounded-[2rem] border border-brand-border bg-white p-8 shadow-2xl shadow-brand-primary/10">

        <div class="mb-6 flex items-center gap-3 pr-10">
            <img src="{{ asset('images/crafted_pieces_logo.png') }}" alt="Crafted Pieces" class="h-12 w-auto rounded-xl object-contain">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-brand-secondary">Crafted Pieces</p>
                <p class="text-sm text-brand-ink/70">Access your account</p>
            </div>
        </div>

        <button type="button"
                onclick="closeAuthModal()"
                class="absolute right-4 top-4 text-2xl font-bold text-brand-ink/60 transition hover:text-brand-primary">
            ×
        </button>

        {{-- ================= LOGIN ================= --}}
        <form id="login-form"
            class="auth-form space-y-5 opacity-100 translate-x-0 transition-all duration-300 ease-in-out"
            method="POST"
            action="{{ route('login') }}">

            <h2 class="text-center text-2xl font-semibold text-brand-primary">Login</h2>

            @csrf

            <div>
                <label class="mb-2 block text-sm font-semibold text-brand-primary">Email</label>
                <input type="email" name="email" required
                       class="brand-input">
                @error('email')
                    <p class="mt-2 text-sm text-brand-secondary">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-brand-primary">Password</label>
                <div class="relative">
                    <input id="login-password" type="password" name="password" required
                           class="brand-input pr-12">
                    <button type="button"
                            data-password-target="login-password"
                            class="absolute inset-y-0 right-3 flex items-center text-brand-ink/70 transition hover:text-brand-primary"
                            onclick="togglePasswordVisibility(this)">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-5 w-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8a4 4 0 0 1 0 8 4 4 0 0 1 0-8Z" />
                        </svg>
                    </button>
                </div>
                @error('password')
                    <p class="mt-2 text-sm text-brand-secondary">{{ $message }}</p>
                @enderror
            </div>

            <label class="flex items-center gap-2 text-sm text-brand-ink/70">
                <input type="checkbox" name="remember" class="h-4 w-4">
                Remember me
            </label>

            <button type="submit"
                    class="brand-btn-primary w-full">
                Log In
            </button>

            <p class="text-center text-sm text-brand-ink/70">
                Don’t have an account?
                <button type="button"
                        onclick="showRegister()"
                        class="font-semibold text-brand-secondary hover:text-brand-primary hover:underline">
                    Sign Up Now!
                </button>
            </p>
        </form>

        {{-- ================= REGISTER ================= --}}
        <form id="register-form"
            class="auth-form hidden space-y-5 opacity-0 translate-x-6 transition-all duration-300 ease-in-out"
            method="POST"
            action="{{ route('register') }}">

            <h2 class="text-center text-2xl font-semibold text-brand-primary">Create Account</h2>

            @csrf

            <div>
                <label class="mb-2 block text-sm font-semibold text-brand-primary">Full name</label>
                <input type="text" name="name" required
                       class="brand-input">
                @error('name')
                    <p class="mt-2 text-sm text-brand-secondary">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-brand-primary">Email</label>
                <input type="email" name="email" required
                       class="brand-input">
                @error('email')
                    <p class="mt-2 text-sm text-brand-secondary">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-brand-primary">Password</label>
                <div class="relative">
                    <input id="register-password" type="password" name="password" required
                           class="brand-input pr-12">
                    <button type="button"
                            data-password-target="register-password"
                            class="absolute inset-y-0 right-3 flex items-center text-brand-ink/70 transition hover:text-brand-primary"
                            onclick="togglePasswordVisibility(this)">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-5 w-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8a4 4 0 0 1 0 8 4 4 0 0 1 0-8Z" />
                        </svg>
                    </button>
                </div>
                @error('password')
                    <p class="mt-2 text-sm text-brand-secondary">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-brand-primary">Confirm password</label>
                <div class="relative">
                    <input id="register-password-confirmation" type="password" name="password_confirmation" required
                           class="brand-input pr-12">
                    <button type="button"
                            data-password-target="register-password-confirmation"
                            class="absolute inset-y-0 right-3 flex items-center text-brand-ink/70 transition hover:text-brand-primary"
                            onclick="togglePasswordVisibility(this)">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-5 w-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8a4 4 0 0 1 0 8 4 4 0 0 1 0-8Z" />
                        </svg>
                    </button>
                </div>
            </div>

            <label class="flex items-start gap-2 text-sm text-brand-ink/70">
                <input type="checkbox" id="privacy-check" class="mt-1 h-4 w-4">
                <span>
                    I agree to the
                    <a href="{{ route('privacy.policy') }}" class="font-semibold text-brand-secondary hover:text-brand-primary hover:underline">Privacy Policy</a>
                    and
                    <a href="{{ route('terms.service') }}" class="font-semibold text-brand-secondary hover:text-brand-primary hover:underline">Terms of Service</a>.
                </span>
            </label>

            <button type="submit"
                    id="register-btn"
                    disabled
                    class="brand-btn-primary w-full opacity-50 cursor-not-allowed">
                Register
            </button>

            <p class="text-center text-sm text-brand-ink/70">
                Already have an account?
                <button type="button"
                        onclick="showLogin()"
                        class="font-semibold text-brand-secondary hover:text-brand-primary hover:underline">
                    Login here
                </button>
            </p>
        </form>

    </div>
</div>