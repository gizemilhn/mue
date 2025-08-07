@extends('home.layout')

@section('content')
    <div class="container mt-4">
        <h2 class="text-center mb-4">Siparişlerim</h2>

        @if($orders->isEmpty())
            <p class="text-center">Henüz bir siparişiniz bulunmamaktadır.</p>
        @else
            <div class="table-responsive rounded-2">
                <table class="table table-bordered ">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Tarih</th>
                        <th>Status</th>
                        <th>Toplam Tutar</th>
                        <th>İşlemler</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->created_at->format('d-m-Y') }}</td>
                            <td>
                                @switch($order->status)
                                    @case('pending')
                                        <span class="badge bg-warning">Beklemede</span>
                                        @break
                                    @case('approved')
                                        <span class="badge bg-success">Onaylandı</span>
                                        @break
                                    @case('cancelled')
                                        <span class="badge bg-danger">İptal Edildi</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary">Bilinmiyor</span>
                                @endswitch
                            </td>
                            <td>{{ number_format($order->total_price, 2) }}₺</td>
                            <td>
                                <a href="{{ route('user.order.details', $order->id) }}" class="btn btn-info btn-sm rounded-2">Detaylar</a>
                                @if($order->status == 'pending')
                                    <form action="{{ route('user.order.cancel', $order->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('POST')
                                        <button  type="submit" class="btn btn-danger btn-sm rounded-2">İptal Et</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
