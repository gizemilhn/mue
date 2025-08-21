@extends('home.layout')

@section('content')
    <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-xl rounded-lg p-6 md:p-8">
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-6">Adres Düzenle</h2>

            <form action="{{ route('user.address.update', $address->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="title" class="block text-gray-700 font-semibold mb-2">Adres Başlığı</label>
                    <input type="text" name="title" id="title" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ $address->title }}" required>
                </div>

                <div class="mb-4">
                    <label for="address_line" class="block text-gray-700 font-semibold mb-2">Adres</label>
                    <textarea name="address_line" id="address_line" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" rows="4" required>{{ $address->address_line }}</textarea>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2" for="city">Şehir</label>
                    <select name="city" id="city" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="{{ $address->city }}" selected>{{ $address->city }}</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2" for="district">İlçe</label>
                    <select name="district" id="district" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="{{ $address->district }}" selected>{{ $address->district }}</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="phone" class="block text-gray-700 font-semibold mb-2">Telefon</label>
                    <input
                        type="text"
                        name="phone"
                        id="phone"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        value="{{ $address->phone }}"
                        pattern="^5\d{9}$"
                        maxlength="10"
                        placeholder="5XX1234567"
                        required
                        title="Telefon numarası 10 haneli olmalı ve 5 ile başlamalıdır. Örn: 5XX1234567">
                </div>

                <div class="flex flex-col sm:flex-row items-center space-y-4 sm:space-y-0 sm:space-x-4 mt-6">
                    <button type="submit" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-md transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Güncelle
                    </button>
                    <a href="{{ route('user.address') }}" class="w-full sm:w-auto bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-6 rounded-md text-center transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500">
                        İptal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let ilIlceData = [];

        $(document).ready(function () {
            const currentCity = '{{ $address->city }}';
            const currentDistrict = '{{ $address->district }}';

            $.getJSON('/json/il_ilce.json', function (data) {
                ilIlceData = data;
                const sehirler = [...new Set(data.map(item => item.il))];

                $('#city').empty();
                $('#district').empty();

                $.each(sehirler, function (index, il) {
                    $('#city').append(`<option value="${il}" ${il === currentCity ? 'selected' : ''}>${il}</option>`);
                });

                const ilceler = ilIlceData.filter(item => item.il === currentCity);
                $.each(ilceler, function (index, item) {
                    $('#district').append(`<option value="${item.ilce}" ${item.ilce === currentDistrict ? 'selected' : ''}>${item.ilce}</option>`);
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
    </script>
@endpush
