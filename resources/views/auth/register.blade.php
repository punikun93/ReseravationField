<x-guest-layout>
    <div class="min-h-screen flex flex-col items-center justify-center bg-gradient-to-br from-blue-50 to-blue-100 p-4">
        <!-- Logo and Brand Section -->
        <div class="text-center mb-6 md:mb-8">
            <div class="flex items-center justify-center gap-3 mb-2">
                <div class="w-10 h-10 md:w-12 md:h-12 bg-blue-700 rounded-lg flex items-center justify-center">
                    <span class="text-xl md:text-2xl font-bold text-white">SA</span>
                </div>
                <h1 class="text-xl md:text-2xl font-bold text-blue-700">Surya Arena</h1>
            </div>
            <p class="text-xs md:text-sm text-gray-600 font-medium">Sport Hall Management System</p>
        </div>

        <form method="POST" action="{{ route('register') }}"
            class="flex flex-col gap-4 md:gap-6 w-full max-w-[320px] md:max-w-sm bg-white p-4 md:p-8 rounded-xl md:rounded-2xl shadow-lg border border-blue-600 relative overflow-hidden">
            @csrf

            <!-- Decorative Elements -->
            <div class="absolute top-0 right-0 w-24 h-24 bg-blue-50 rounded-full -mr-12 -mt-12"></div>
            <div class="absolute bottom-0 left-0 w-20 h-20 bg-blue-50 rounded-full -ml-10 -mb-10"></div>

            <!-- Title and Message -->
            <p class="text-2xl md:text-3xl font-bold text-blue-700 relative pl-8 md:pl-10 mb-0 md:mb-1">
                Daftar Akun
                <span class="absolute left-0 top-1 w-4 h-4 md:w-5 md:h-5 bg-blue-700 rounded-full animate-pulse"></span>
            </p>
            <p class="text-gray-600 text-xs md:text-sm mt-2">Daftar sekarang untuk akses penuh ke aplikasi penyewaan
                lapangan futsal kami.</p>

            <!-- Nama Lengkap -->
            <div class="relative">
                <x-text-input id="name" type="text" name="name" required autofocus
                    class="peer block w-full p-2 md:p-3 text-sm md:text-base border rounded-lg border-gray-300 focus:outline-none focus:border-blue-600 bg-white relative z-10"
                    placeholder=" " />
                <label for="name"
                    class="absolute left-2 md:left-3 -top-2 text-xs text-blue-600 bg-white px-1 transition-all duration-200 transform
                           peer-placeholder-shown:top-2 md:peer-placeholder-shown:top-3 
                           peer-focus:-top-2 peer-focus:left-2 md:peer-focus:left-3 peer-focus:text-xs 
                           peer-placeholder-shown:peer-focus:-top-2 peer-placeholder-shown:peer-focus:left-2 z-20">
                    Nama Lengkap
                </label>
                <x-input-error :messages="$errors->get('name')" class="mt-1 md:mt-2" />
            </div>

            <!-- Email -->
            <div class="relative">
                <x-text-input id="email" type="email" name="email" required
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

            <!-- Kata Sandi -->
            <div class="relative">
                <x-text-input id="password" type="password" name="password" required
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

            <!-- Konfirmasi Kata Sandi -->
            <div class="relative">
                <x-text-input id="password_confirmation" type="password" name="password_confirmation" required
                    class="peer block w-full p-2 md:p-3 text-sm md:text-base border rounded-lg border-gray-300 focus:outline-none focus:border-blue-600 bg-white relative z-10"
                    placeholder=" " />
                <label for="password_confirmation"
                    class="absolute left-2 md:left-3 -top-2 text-xs text-blue-600 bg-white px-1 transition-all duration-200 transform
                           peer-placeholder-shown:top-2 md:peer-placeholder-shown:top-3 
                           peer-focus:-top-2 peer-focus:left-2 md:peer-focus:left-3 peer-focus:text-xs 
                           peer-placeholder-shown:peer-focus:-top-2 peer-placeholder-shown:peer-focus:left-2 z-20">
                    Konfirmasi Kata Sandi
                </label>
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 md:mt-2" />
            </div>


            <!-- Actions -->
            <div
                class="flex flex-col md:flex-row items-center gap-3 md:gap-0 md:justify-between mt-4 md:mt-6 relative z-10">
                <a href="{{ route('login') }}"
                    class="text-xs md:text-sm text-gray-600 hover:text-blue-700 hover:underline">Sudah punya akun?
                    Masuk</a>
                <button type="submit"
                    class="text-center w-full md:w-auto bg-blue-700 hover:bg-blue-800 text-white font-semibold rounded-lg py-2 px-4 md:px-6 text-sm md:text-base transition-all duration-200 hover:shadow-lg">
                    Daftar
                </button>
            </div>
        </form>

        <!-- Footer -->
        <div class="mt-6 text-center">
            <p class="text-xs md:text-sm text-gray-500">&copy; 2024 Surya Arena Sport Hall. All rights reserved.</p>
        </div>
    </div>
</x-guest-layout>
