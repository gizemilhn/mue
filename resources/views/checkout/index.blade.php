@extends('home.layout')

@section('content')
    <div class="container mt-4">
        <h2>Adres Seçimi</h2>

        <form action="{{ route('checkout.stripe') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="mt-4 mb-2">Adres Seç</label>
                @php $selectedId = session('selected_address') @endphp

                @foreach($addresses as $address)
                    <div>
                        <input class="mb-4" type="radio" name="address_id" value="{{ $address->id }}" required
                            {{ $selectedId == $address->id ? 'checked' : '' }}>
                        {{$address->title}},{{ $address->address_line }}, {{ $address->district }}, {{ $address->city }} - {{ $address->phone }}
                    </div>
                @endforeach
            </div>

            <button type="submit" class="btn btn-success mt-3 rounded-2 mb-4">Ödeme Yap</button>
        </form>

        <hr>
        <h4>Yeni Adres Ekle</h4>
        <form action="{{ route('checkout.address.store') }}" method="POST">
            @csrf
            <div class="form-group mt-4">
                <label for="district">Adres Başlığı</label>
                <input name="title" class="form-control mt-4" placeholder="Adres Başlığı" required>
            </div>
            <div class="form-group mt-4">
                <label for="district">Adres</label>
                <input name="address_line" class="form-control" placeholder="Adres" required>
            </div>
            <div class="form-group mt-4">
                <label for="city">Şehir</label>
                <select name="city" id="city" class="form-control">
                    <option value="">Şehir seçiniz</option>
                </select>
            </div>
            <div class="form-group mt-4">
                <label for="district">İlçe</label>
                <select name="district" id="district" class="form-control">
                    <option value="">İlçe seçiniz</option>
                </select>
            </div>
            <div class="form-group mt-4">
                <label for="district">Telefon</label>
                <input name="phone" class="form-control" placeholder="Telefon" required>
            </div>

            <button type="submit" class="btn btn-primary mt-4 mb-4 rounded-2">Kaydet</button>
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
