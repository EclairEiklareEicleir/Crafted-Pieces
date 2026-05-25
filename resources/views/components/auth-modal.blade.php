<div id="auth-modal"
     data-auth-modal
     class="hidden fixed inset-0 z-50 items-center justify-center px-4 py-6 sm:px-6">

    <button type="button"
            class="absolute inset-0 cursor-default bg-black/60 backdrop-blur-sm"
            aria-label="Close authentication modal"
            onclick="closeAuthModal()"></button>

    <div class="relative w-full max-w-xl overflow-hidden rounded-4xl border border-brand-border bg-white shadow-2xl shadow-brand-primary/10">

        <div class="border-b border-brand-border bg-brand-surface px-6 py-5 sm:px-8">
            <div class="flex items-center gap-3 pr-10">
                <img src="{{ asset('images/crafted_pieces_logo.png') }}" alt="Crafted Pieces" class="h-12 w-auto rounded-xl object-contain">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-brand-secondary">Crafted Pieces</p>
                    <p class="text-sm text-brand-ink/70">Access your account</p>
                </div>
            </div>
        </div>

        <button type="button"
                onclick="closeAuthModal()"
                class="absolute right-4 top-4 rounded-full border border-brand-border bg-white/90 p-2 text-brand-ink/60 shadow-sm transition hover:border-brand-secondary hover:text-brand-primary">
            <span class="sr-only">Close authentication modal</span>
            <span aria-hidden="true" class="text-2xl leading-none">×</span>
        </button>

        {{-- ================= LOGIN ================= --}}
        <form id="login-form"
              class="auth-form space-y-5 p-6 opacity-100 translate-x-0 transition-all duration-300 ease-in-out sm:p-8"
              method="POST"
              action="{{ route('login') }}">

            <h2 class="text-center text-2xl font-semibold text-brand-primary">Login</h2>

            @csrf

            <div>
                <label class="mb-2 block text-sm font-semibold text-brand-primary">Email</label>
                <input type="email" name="email" required class="brand-input">

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

            <button type="submit" class="brand-btn-primary w-full">
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
              class="auth-form hidden space-y-5 p-6 opacity-0 translate-x-6 transition-all duration-300 ease-in-out sm:p-8"
              method="POST"
              action="{{ route('register') }}">

            <h2 class="text-center text-2xl font-semibold text-brand-primary">Create Account</h2>

            @csrf

            <div>
                <label class="mb-2 block text-sm font-semibold text-brand-primary">Full name</label>
                <input type="text" name="name" required class="brand-input">

                @error('name')
                    <p class="mt-2 text-sm text-brand-secondary">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-brand-primary">Email</label>
                <input type="email" name="email" required class="brand-input">

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
                    <button type="button" data-legal-modal-open="privacy" class="font-semibold text-brand-secondary hover:text-brand-primary hover:underline">Privacy Policy</button>
                    and
                    <button type="button" data-legal-modal-open="terms" class="font-semibold text-brand-secondary hover:text-brand-primary hover:underline">Terms of Service</button>.
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

        <x-legal-modal type="privacy" />
        <x-legal-modal type="terms" />

    </div>
</div>