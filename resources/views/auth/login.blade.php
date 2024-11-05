<x-guest-layout>
    <div class="min-h-screen flex flex-col items-center justify-center bg-gradient-to-br from-blue-50 to-blue-100 p-4">
        <!-- Logo and Brand Section -->
        <div class="text-center mb-6 md:mb-8">
            <div class="flex items-center justify-center gap-3 mb-2">
                <!-- You can replace this with your actual logo image -->
                <div class="w-10 h-10 md:w-12 md:h-12 bg-blue-700 rounded-lg flex items-center justify-center">
                    <span class="text-xl md:text-2xl font-bold text-white">SA</span>
                </div>
                <h1 class="text-xl md:text-2xl font-bold text-blue-700">Surya Arena</h1>
            </div>
            <p class="text-xs md:text-sm text-gray-600 font-medium">Sport Hall Management System</p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}"
            class="flex flex-col gap-4 md:gap-6 w-full max-w-[320px] md:max-w-sm bg-white p-4 md:p-8 rounded-xl md:rounded-2xl shadow-lg border border-blue-600 relative overflow-hidden">
            @csrf

            <!-- Decorative Elements -->
            <div class="absolute top-0 right-0 w-24 h-24 bg-blue-50 rounded-full -mr-12 -mt-12"></div>
            <div class="absolute bottom-0 left-0 w-20 h-20 bg-blue-50 rounded-full -ml-10 -mb-10"></div>

            <!-- Title and Message -->
            <div class="relative">
                <p class="text-2xl md:text-3xl font-bold text-blue-700 relative pl-8 md:pl-10 mb-0 md:mb-1">
                    Masuk Akun
                    <span
                        class="absolute left-0 top-1 w-4 h-4 md:w-5 md:h-5 bg-blue-700 rounded-full animate-pulse"></span>
                </p>
                <p class="text-gray-600 text-xs md:text-sm mt-2">Masuk untuk mengelola reservasi lapangan olahraga Anda.
                </p>
            </div>

            <!-- Email Address -->
            <div class="relative">
                <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus
                    autocomplete="username"
                    class="peer block w-full p-2 md:p-3 text-sm md:text-base border rounded-lg border-gray-300 focus:outline-none focus:border-blue-600 bg-white relative z-10"
                    placeholder=" " />
                <label for="email"
                    class="absolute left-2 md:left-3 -top-2 text-xs text-blue-600 bg-white px-1 transition-all duration-200 transform
                          peer-placeholder-shown:top-2 md:peer-placeholder-shown:top-3 
                          peer-focus:-top-2 peer-focus:left-2 md:peer-focus:left-3 peer-focus:text-xs 
                          peer-placeholder-shown:peer-focus:-top-2 peer-placeholder-shown:peer-focus:left-2 z-20">
                    Email
                </label>
                <x-input-error :messages="$errors->get('email')" class="mt-1 md:mt-2" />
            </div>

            <!-- Password -->
            <div class="relative">
                <x-text-input id="password" type="password" name="password" required autocomplete="current-password"
                    class="peer block w-full p-2 md:p-3 text-sm md:text-base border rounded-lg border-gray-300 focus:outline-none focus:border-blue-600 bg-white relative z-10"
                    placeholder=" " />
                <label for="password"
                    class="absolute left-2 md:left-3 -top-2 text-xs text-blue-600 bg-white px-1 transition-all duration-200 transform
                          peer-placeholder-shown:top-2 md:peer-placeholder-shown:top-3 
                          peer-focus:-top-2 peer-focus:left-2 md:peer-focus:left-3 peer-focus:text-xs 
                          peer-placeholder-shown:peer-focus:-top-2 peer-placeholder-shown:peer-focus:left-2 z-20">
                    Kata Sandi
                </label>
                <x-input-error :messages="$errors->get('password')" class="mt-1 md:mt-2" />
            </div>


            <!-- Remember Me -->
            <div class="flex items-center mt-2 md:mt-4 relative z-10">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox"
                        class="w-4 h-4 rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500"
                        name="remember">
                    <span class="ml-2 text-xs md:text-sm text-gray-600">Ingat saya</span>
                </label>
            </div>

            <!-- Actions -->
            <div
                class="flex flex-col md:flex-row items-start md:items-center gap-2 md:gap-0 md:justify-between mt-4 md:mt-6 relative z-10">
                <a href="{{ route('register') }}"
                    class="text-xs md:text-sm text-gray-600 hover:text-blue-700 hover:underline">Belum punya akun?
                    Daftar</a>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}"
                        class="text-xs md:text-sm text-gray-600 hover:text-blue-700 hover:underline">Lupa kata
                        sandi?</a>
                @endif
            </div>

            <!-- Submit Button -->
            <div class="flex justify-center md:justify-end mt-4 relative z-10">
                <x-primary-button
                    class="w-full md:w-auto bg-blue-700 hover:bg-blue-800 text-white font-semibold rounded-lg py-2 px-4 md:px-6 text-sm md:text-base transition-all duration-200 hover:shadow-lg">
                    Masuk
                </x-primary-button>
            </div>
        </form>

        <!-- Footer -->
        <div class="mt-6 text-center">
            <p class="text-xs md:text-sm text-gray-500">&copy; 2024 Surya Arena Sport Hall. All rights reserved.</p>
        </div>
    </div>
</x-guest-layout>
