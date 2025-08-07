<section>

    <form method="post" action="{{ route('profile.update') }}" style="width: 1200px; margin: 20px; padding: 20px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1)">
        @csrf
        @method('patch')

        <h2  style=" font-size: 24px; margin-bottom: 10px;color: #333;">
            {{ __('Profil Bilgileri') }}
        </h2>
        <p style="color: #555; margin-bottom: 20px; font-size: 1rem;">
            {{ __("Hesabınızın profil bilgilerini ve e-posta adresini güncelleyin.") }}
        </p>
        <div class="form-group">
            <label for="name">{{ __('İsim') }}</label>
            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required>
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>
        <div class="form-group">
            <label for="name">{{ __('Soyisim') }}</label>
            <input type="text" id="surname" name="surname" value="{{ old('surname', $user->surname) }}" required>
            <x-input-error class="mt-2" :messages="$errors->get('surname')" />
        </div>

        <!-- Email Input -->
        <div class="form-group">
            <label for="email">{{ __('Email') }}</label>
            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>

        <!-- Phone Input -->
        <div class="form-group">
            <label for="phone">{{ __('Telefon') }}</label>
            <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
        </div>
        <!-- Save Button -->
        <div class="flex items-center gap-4">
            <button type="submit">{{ __('Kaydet') }}</button>
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
