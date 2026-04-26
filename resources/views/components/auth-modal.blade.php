<div id="auth-modal"
     class="hidden fixed inset-0 z-50 flex items-center justify-center">

    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>

    <div class="relative w-full max-w-md bg-white p-8 rounded-[2rem] border border-[#eadfd7] shadow-sm">

        <button type="button"
                onclick="closeAuthModal()"
                class="absolute top-4 right-4 text-2xl font-bold text-[#6f5a51] hover:text-[#a86b57] transition">
            ×
        </button>

        {{-- ================= LOGIN ================= --}}
        <form id="login-form"
            class="auth-form space-y-5 opacity-100 translate-x-0 transition-all duration-300 ease-in-out"
            method="POST"
            action="{{ route('login') }}">

            <h2 class="text-2xl font-semibold text-[#4d3028] text-center">Login</h2>

            @csrf

            <div>
                <label class="mb-2 block text-sm font-semibold text-[#5d342b]">Email</label>
                <input type="email" name="email" required
                       class="w-full rounded-2xl border border-[#e7d6cb] px-4 py-3 text-sm focus:border-[#b8745f] focus:outline-none">
                @error('email')
                    <p class="mt-2 text-sm text-red-700">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-[#5d342b]">Password</label>
                <input type="password" name="password" required
                       class="w-full rounded-2xl border border-[#e7d6cb] px-4 py-3 text-sm focus:border-[#b8745f] focus:outline-none">
                @error('password')
                    <p class="mt-2 text-sm text-red-700">{{ $message }}</p>
                @enderror
            </div>

            <label class="flex items-center gap-2 text-sm text-[#6f5a51]">
                <input type="checkbox" name="remember" class="h-4 w-4">
                Remember me
            </label>

            <button type="submit"
                    class="w-full rounded-full bg-[#b8745f] px-5 py-3 text-sm font-semibold text-white hover:bg-[#a96550]">
                Log In
            </button>

            <p class="text-center text-sm text-[#6f5a51]">
                Don’t have an account?
                <button type="button"
                        onclick="showRegister()"
                        class="text-[#a86b57] font-semibold hover:underline">
                    Sign Up Now!
                </button>
            </p>
        </form>

        {{-- ================= REGISTER ================= --}}
        <form id="register-form"
            class="auth-form hidden space-y-5 opacity-0 translate-x-6 transition-all duration-300 ease-in-out"
            method="POST"
            action="{{ route('register') }}">

            <h2 class="text-2xl font-semibold text-[#4d3028] text-center">Create Account</h2>

            @csrf

            <div>
                <label class="mb-2 block text-sm font-semibold text-[#5d342b]">Full name</label>
                <input type="text" name="name" required
                       class="w-full rounded-2xl border border-[#e7d6cb] px-4 py-3 text-sm">
                @error('name')
                    <p class="mt-2 text-sm text-red-700">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-[#5d342b]">Email</label>
                <input type="email" name="email" required
                       class="w-full rounded-2xl border border-[#e7d6cb] px-4 py-3 text-sm">
                @error('email')
                    <p class="mt-2 text-sm text-red-700">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-[#5d342b]">Password</label>
                <input type="password" name="password" required
                       class="w-full rounded-2xl border border-[#e7d6cb] px-4 py-3 text-sm">
                @error('password')
                    <p class="mt-2 text-sm text-red-700">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-[#5d342b]">Confirm password</label>
                <input type="password" name="password_confirmation" required
                       class="w-full rounded-2xl border border-[#e7d6cb] px-4 py-3 text-sm">
            </div>

            <label class="flex items-start gap-2 text-sm text-[#6f5a51]">
                <input type="checkbox" id="privacy-check" class="mt-1 h-4 w-4">
                <span>
                    I agree to the
                    <a href="#" class="text-[#a86b57] font-semibold hover:underline">Privacy Policy</a>
                    and
                    <a href="#" class="text-[#a86b57] font-semibold hover:underline">Terms of Service</a>.
                </span>
            </label>

            <button type="submit"
                    id="register-btn"
                    disabled
                    class="w-full rounded-full bg-[#b8745f] px-5 py-3 text-sm font-semibold text-white opacity-50 cursor-not-allowed transition">
                Register
            </button>

            <p class="text-center text-sm text-[#6f5a51]">
                Already have an account?
                <button type="button"
                        onclick="showLogin()"
                        class="text-[#a86b57] font-semibold hover:underline">
                    Login here
                </button>
            </p>
        </form>

    </div>
</div>