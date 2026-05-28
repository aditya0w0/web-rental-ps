<section class="space-y-5">
    <header>
        <h2 class="text-lg font-semibold text-rose-800">Delete Account</h2>
        <p class="mt-1 max-w-2xl text-sm leading-6 text-slate-600">Menghapus akun akan menghapus akses ke data rental dan pesanan. Gunakan hanya jika benar-benar diperlukan.</p>
    </header>

    <button type="button" class="btn border border-rose-200 bg-white text-rose-700 hover:bg-rose-50" x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">
        Delete Account
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-semibold text-slate-950">Are you sure?</h2>
            <p class="mt-2 text-sm leading-6 text-slate-600">Enter your password to confirm account deletion.</p>

            <div class="mt-6">
                <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />
                <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" placeholder="{{ __('Password') }}" />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" class="btn btn-secondary" x-on:click="$dispatch('close')">Cancel</button>
                <button type="submit" class="btn border border-rose-200 bg-rose-700 text-white hover:bg-rose-800">Delete Account</button>
            </div>
        </form>
    </x-modal>
</section>
