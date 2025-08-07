<section class="space-y-6" style="width: 1200px; margin: 20px; padding: 20px;">
    <header>
        <h2 class="text-md-left font-thin text-gray-900">
            {{ __('Hesap Silme') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Hesabınız silindiğinde, tüm kaynakları ve verileri kalıcı olarak silinecektir. Hesabınızı silmeden önce, lütfen saklamak istediğiniz tüm verileri veya bilgileri indirin.') }}
        </p>
    </header>


    <x-danger-button
        x-data="{}"
        x-on:click="$dispatch('open-modal', 'confirm-user-deletion')"
    >{{ __('Hesap Silme') }}</x-danger-button>


    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable style="margin-bottom: 30px; padding: 20px">
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Hesabınızı silmek istediğinize emin misiniz?') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                {{ __('Hesabınız silindiğinde, tüm kaynakları ve verileri kalıcı olarak silinecektir. Hesabınızı kalıcı olarak silmek istediğinizi onaylamak için lütfen şifrenizi girin.') }}
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-3/4"
                    placeholder="{{ __('Password') }}"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end" >
                <x-secondary-button x-on:click="$dispatch('close')" style="background-color: #1a202c; color: #CCCCCC">
                    {{ __('İptal') }}
                </x-secondary-button>

                <x-danger-button class="ms-3" style="background-color: #8d0414; color: #CCCCCC">
                    {{ __('Hesabımı Sil') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'deactivate-user')"
    >{{ __('Hesap Dondurma') }}</x-danger-button>

    <!-- Modal for Deactivating Account -->
    <x-modal name="deactivate-user" :show="$errors->userDeletion->isNotEmpty()" focusable style="margin-bottom: 30px; padding: 20px">
        <form method="post" action="{{ route('profile.deactivate') }}" class="p-6">
            @csrf
            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Hesabınızı devre dışı bırakmak istediğinizden emin misiniz?') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                {{ __('Hesabınız devre dışı bırakıldığında, yeniden etkinleştirene kadar erişiminiz olmayacaktır.') }}
            </p>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')" style="background-color: #1a202c; color: #CCCCCC">
                    {{ __('İptal') }}
                </x-secondary-button>

                <x-danger-button class="ms-3" style="background-color: #8d0414; color: #CCCCCC">
                    {{ __('Hesabımı Dondur') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>

</section>
