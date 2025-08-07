@extends('home.layout')

@section('content')
    <div class="container mt-4">
        <h2 class="text-center mb-4">Adreslerim</h2>
        @if($addresses->isEmpty())
            <p>Henüz bir adres eklemediniz.</p>
        @else
            <div class="list-group mb-4">
                @foreach($addresses as $address)
                    <div class="list-group-item d-flex justify-content-between align-items-center border-1 p-2 mt-4 rounded-2">
                        <div>
                            <strong>{{ $address->title }}</strong><br>
                            {{ $address->address_line }}<br>
                            {{ $address->city }}, {{ $address->district }}
                        </div>
                        <div>
                            <a href="{{ route('user.address.edit', $address->id) }}" class="btn btn-sm btn-primary rounded-2">Düzenle</a>
                            <form action="{{ route('user.address.destroy', $address->id) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Bu adresi silmek istediğinize emin misiniz?')">
                                @csrf
                                @method('DELETE')
                                <button
                                    type="button"
                                    class="btn btn-sm btn-danger rounded delete-address-btn"
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

        <h4 class="text-center">Yeni Adres Ekle</h4>
        <form action="{{ route('user.address.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="title" class="form-label">Adres Başlığı</label>
                <input type="text" name="title" id="title" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="address_line" class="form-label">Adres</label>
                <textarea name="address_line" id="address_line" class="form-control" required></textarea>
            </div>
            <div class="form-group mb-3">
                <label class="mb-2" for="city">Şehir</label>
                <select name="city" id="city" class="form-control">
                    <option value="">Şehir seçiniz</option>
                </select>
            </div>
            <div class="form-group mb-3">
                <label  class="mb-2" for="district">İlçe</label>
                <select name="district" id="district" class="form-control">
                    <option value="">İlçe seçiniz</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Telefon</label>
                <input
                    type="text"
                    name="phone"
                    id="phone"
                    class="form-control"
                    pattern="^5\d{9}$"
                    maxlength="10"
                    placeholder="5XX1234567"
                    required
                    title="Telefon numarası 10 haneli olmalı ve 5 ile başlamalıdır. Örn: 5XX1234567">
            </div>
            <button type="submit" class="btn btn-success mt-4 rounded-2 ">Kaydet</button>
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

    //SweetAlertKontrol
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
