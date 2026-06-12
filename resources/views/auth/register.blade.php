<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Register As Select -->
        <div class="mt-4">
            <x-input-label for="register_as" :value="__('Daftar Sebagai')" />
            <select id="register_as" name="register_as" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                <option value="user">Staff Unit Peminjam</option>
                <option value="supplier">Supplier Rekanan</option>
            </select>
        </div>

        <!-- Supplier Additional Fields -->
        <div id="supplier_fields" class="mt-4" style="display: none;">
            <div class="mt-4">
                <x-input-label for="alamat" :value="__('Alamat Perusahaan')" />
                <x-text-input id="alamat" class="block mt-1 w-full" type="text" name="alamat" :value="old('alamat')" />
                <x-input-error :messages="$errors->get('alamat')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="telepon" :value="__('Telepon Perusahaan')" />
                <x-text-input id="telepon" class="block mt-1 w-full" type="text" name="telepon" :value="old('telepon')" />
                <x-input-error :messages="$errors->get('telepon')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="pic" :value="__('Nama PIC (Contact Person)')" />
                <x-text-input id="pic" class="block mt-1 w-full" type="text" name="pic" :value="old('pic')" />
                <x-input-error :messages="$errors->get('pic')" class="mt-2" />
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const registerAs = document.getElementById('register_as');
                const supplierFields = document.getElementById('supplier_fields');
                const alamat = document.getElementById('alamat');
                const telepon = document.getElementById('telepon');
                const pic = document.getElementById('pic');

                function toggleFields() {
                    if (registerAs.value === 'supplier') {
                        supplierFields.style.display = 'block';
                        alamat.required = true;
                        telepon.required = true;
                        pic.required = true;
                    } else {
                        supplierFields.style.display = 'none';
                        alamat.required = false;
                        telepon.required = false;
                        pic.required = false;
                    }
                }

                registerAs.addEventListener('change', toggleFields);
                toggleFields(); // run initially
            });
        </script>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
