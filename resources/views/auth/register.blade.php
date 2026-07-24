<x-guest-layout>
    <div class="mb-6">
        <h1 class="text-xl font-semibold text-slate-950">Register</h1>
        <p class="mt-1 text-sm text-slate-600">Buat akun PlayHub baru.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="mt-2 block w-full rounded-lg border-slate-300 focus:border-sky-500 focus:ring-sky-500" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="mt-2 block w-full rounded-lg border-slate-300 focus:border-sky-500 focus:ring-sky-500" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="mt-2 block w-full rounded-lg border-slate-300 focus:border-sky-500 focus:ring-sky-500" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="mt-2 block w-full rounded-lg border-slate-300 focus:border-sky-500 focus:ring-sky-500" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <button type="submit" class="btn btn-primary w-full">
            {{ __('Register') }}
        </button>

        <p class="text-center text-sm text-slate-600">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="font-semibold text-sky-700 hover:text-sky-900">Login</a>
        </p>
    </form>
</x-guest-layout>
