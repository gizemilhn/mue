@extends('home.layout')

@section('content')
    <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-xl rounded-lg p-6 md:p-8">
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-6">Adreslerim</h2>

            @if($addresses->isEmpty())
                <div class="text-center p-8 bg-gray-50 rounded-lg">
                    <p class="text-gray-500 font-medium">Henüz bir adres eklemediniz.</p>
                </div>
            @else
                <div class="space-y-4 mb-8">
                    @foreach($addresses as $address)
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center bg-gray-100 p-4 rounded-lg shadow-sm">
                            <div class="mb-4 md:mb-0">
                                <strong class="text-lg font-semibold text-gray-800">{{ $address->title }}</strong><br>
                                <span class="text-gray-600">{{ $address->address_line }}</span><br>
                                <span class="text-gray-600">{{ $address->city }}, {{ $address->district }}</span>
                            </div>
                            <div class="flex space-x-2">
                                <a href="{{ route('user.address.edit', $address->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-md transition-colors duration-200">
                                    Düzenle
                                </a>
                                <form action="{{ route('user.address.destroy', $address->id) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Bu adresi silmek istediğinize emin misiniz?')">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        type="button"
                                        class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded-md transition-colors duration-200 delete-address-btn"
                                        data-id="{{ $address->id }}"
                                        data-route="{{ route('user.address.destroy', $address->id) }}">
                                        Sil
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <hr class="my-8 border-gray-300">

            <h4 class="text-xl font-bold text-center text-gray-800 mb-6">Yeni Adres Ekle</h4>
            <form action="{{ route('user.address.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Adres Başlığı</label>
                    <input type="text" name="title" id="title" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label for="address_line" class="block text-sm font-medium text-gray-700 mb-1">Adres</label>
                    <textarea name="address_line" id="address_line" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" rows="3" required></textarea>
                </div>
                <div>
                    <label for="city" class="block text-sm font-medium text-gray-700 mb-1">Şehir</label>
                    <select name="city" id="city" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Şehir seçiniz</option>
                    </select>
                </div>
                <div>
                    <label for="district" class="block text-sm font-medium text-gray-700 mb-1">İlçe</label>
                    <select name="district" id="district" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">İlçe seçiniz</option>
                    </select>
                </div>
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Telefon</label>
                    <input
                        type="text"
                        name="phone"
                        id="phone"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        pattern="^5\d{9}$"
                        maxlength="10"
                        placeholder="5XX1234567"
                        required
                        title="Telefon numarası 10 haneli olmalı ve 5 ile başlamalıdır. Örn: 5XX1234567">
                </div>
                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-md transition-colors duration-200">
                    Kaydet
                </button>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let ilIlceData = [];
        $(document).ready(function () {
            $.getJSON('/json/il_ilce.json', function (data) {
                ilIlceData = data;
                const sehirler = [...new Set(data.map(item => item.il))];
                $.each(sehirler, function (index, il) {
                    $('#city').append(`<option value="${il}">${il}</option>`);
                });
            });
            $('#city').on('change', function () {
                const secilenIl = $(this).val();
                $('#district').empty().append(`<option value="">İlçe seçiniz</option>`);

                const ilceler = ilIlceData.filter(item => item.il === secilenIl);
                $.each(ilceler, function (index, item) {
                    $('#district').append(`<option value="${item.ilce}">${item.ilce}</option>`);
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function () {
            const deleteButtons = document.querySelectorAll('.delete-address-btn');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const addressId = this.dataset.id;
                    const route = this.dataset.route;

                    Swal.fire({
                        title: 'Emin misiniz?',
                        text: 'Bu adres silinecek!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Evet, sil',
                        cancelButtonText: 'İptal',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const form = document.createElement('form');
                            form.action = route;
                            form.method = 'POST';

                            const csrf = document.createElement('input');
                            csrf.type = 'hidden';
                            csrf.name = '_token';
                            csrf.value = '{{ csrf_token() }}';

                            const method = document.createElement('input');
                            method.type = 'hidden';
                            method.name = '_method';
                            method.value = 'DELETE';

                            form.appendChild(csrf);
                            form.appendChild(method);
                            document.body.appendChild(form);
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Başarılı',
                text: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 2000
            });
        </script>
    @endif
@endpush
