@extends('home.layout')

@section('content')
    <div class="container mt-4">
        <h2 class="mb-4">Adres Düzenle</h2>
        <form action="{{ route('user.address.update', $address->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="title" class="form-label">Adres Başlığı</label>
                <input type="text" name="title" id="title" class="form-control" value="{{ $address->title }}" required>
            </div>
            <div class="mb-3">
                <label for="address_line" class="form-label">Adres</label>
                <textarea name="address_line" id="address_line" class="form-control" required>{{ $address->address_line }}</textarea>
            </div>
            <div class="form-group mb-3">
                <label class="mb-2" for="city">Şehir</label>
                <select name="city" id="city" class="form-control">
                    <option value="">{{ $address->city }}</option>
                </select>
            </div>
            <div class="form-group mb-3">
                <label class="mb-2" for="district">İlçe</label>
                <select name="district" id="district" class="form-control">
                    <option value="">{{ $address->district }}</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Telefon</label>
                <input type="text" name="phone" id="phone" class="form-control" value="{{ $address->phone }}" required>
            </div>
            <button type="submit" class="btn btn-primary rounded-2 mt-2">Güncelle</button>
            <a href="{{ route('user.address') }}" class="btn btn-secondary rounded-2 mt-2" >İptal</a>
        </form>
    </div>
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
</script>
