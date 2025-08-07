<section>
    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6" style="width: 1200px; margin: 20px; padding: 20px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1)">
        @csrf
        @method('put')
        <h2 style=" font-size: 24px; margin-bottom: 10px;color: #333;">
            {{ __('Şifre Güncelleme') }}
        </h2>

        <p style="color: #555; margin-bottom: 20px; font-size: 1rem;">
            {{ __('Hesabınızın güvenliğini sağlamak için uzun ve rastgele bir parola kullandığınızdan emin olun.') }}
        </p>
        <div>
            <x-input-label for="update_password_current_password" :value="__('Mevcut Şifre')" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="mt-1 block w-full" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password" :value="__('Yeni Şifre')" />
            <x-text-input id="update_password_password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('Şifreyi Doğrulayın')" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Kaydet') }}</x-primary-button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
<style>
    label {
        font-size: 1rem;
        font-weight: 500;
        color: #333;
        margin-bottom: 8px;
        display: block;
    }
    input[type="text"], input[type="email"], input[type="password"], textarea {
        width: 100%;
        padding: 10px;
        margin-top: 5px;
        border: 1px solid #ddd;
        border-radius: 5px;
        background-color: #f9f9f9;
        font-size: 1rem;
        color: #333;
    }
    input[type="text"]:focus, input[type="email"]:focus, input[type="password"]:focus, textarea:focus {
        border-color: #007bff;
        outline: none;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
    }
    .x-input-error {
        color: #e74c3c;
        font-size: 0.875rem;
        margin-top: 5px;
    }

    button {
        width: 100%;
        padding: 12px;
        background-color: #4b5563;
        color: white;
        border: none;
        border-radius: 5px;
        font-size: 1rem;
        cursor: pointer;
        margin-top: 20px;
        transition: background-color 0.3s ease;
    }

    button:hover {
        background-color: #1a202c;
    }
    .flex {
        display: flex;
        gap: 10px;
    }

</style>
