@extends('admin.index')
@section('content')
    <div class="p-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-800">Ürün Güncelle</h1>
                    <p class="text-sm text-gray-500 mt-1">{{ $product->name }} ürününün bilgilerini düzenleyebilirsiniz</p>
                </div>
                <div class="flex items-center space-x-2">
                    <a href="{{ URL::previous() }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                        </svg>
                        Geri Dön
                    </a>
                </div>
            </div>
            <form action="{{route('edit_product',$product->id)}}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Product Name -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Ürün Adı</label>
                    <input type="text" name="name" value="{{$product->name}}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Product Description -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Açıklama</label>
                    <textarea name="description" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 min-h-[100px]">{{$product->description}}</textarea>
                </div>

                <!-- Price and Stock -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Fiyat</label>
                        <input type="text" name="price" value="{{$product->price}}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Stok</label>
                        <input type="number" name="stock" value="{{$product->stock}}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-100">
                    </div>
                </div>

                <!-- Category -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Kategori</label>
                    <select name="category" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-100">
                        @foreach($category as $cat)
                            <option value="{{$cat->category_name}}" {{ $product->category == $cat->category_name ? 'selected' : '' }}>
                                {{$cat->category_name}}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Size Selection -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Beden Seçimi ve Stok Güncelleme</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-2">
                        @foreach($sizes as $size)
                            @php
                                $pivot = $product->sizes->firstWhere('id', $size->id);
                                $checked = $pivot ? 'checked' : '';
                                $stock = $pivot ? $pivot->pivot->stock : '';
                            @endphp
                            <div class="flex items-center space-x-3 p-3 border border-gray-200 rounded-md">
                                <input type="checkbox" id="size_checkbox_{{ $size->id }}"
                                       class="size-checkbox h-5 w-5 text-blue-600 rounded focus:ring-blue-500"
                                       onchange="toggleStockInput({{ $size->id }})"
                                       name="sizes[{{ $size->id }}][selected]"
                                       value="1" {{ $checked }}>
                                <label for="size_checkbox_{{ $size->id }}" class="text-sm text-gray-700">{{ $size->name }}</label>
                                <input type="number" name="sizes[{{ $size->id }}][stock]"
                                       placeholder="Stok"
                                       id="stock_input_{{ $size->id }}"
                                       class="stock-input w-20 px-2 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500"
                                       value="{{ $stock }}"
                                       min="0"
                                       style="{{ $checked ? '' : 'display:none;' }}">
                                @error('sizes.' . $size->id . '.stock')
                                <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Sale Option -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Satış Durumu</label>
                    <select name="is_published" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-100">
                        <option value="0" {{ $product->is_published == 0 ? 'selected' : '' }}>Kapalı</option>
                        <option value="1" {{ $product->is_published == 1 ? 'selected' : '' }}>Açık</option>
                    </select>
                </div>

                <!-- Current Images -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Mevcut Resimler</label>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 mt-2">
                        @foreach ($product->images as $image)
                            <div class="relative border rounded-md p-2 {{ $image->is_featured ? 'border-green-500 border-2' : 'border-gray-200' }}">
                                <img src="{{ asset($image->image_path) }}" class="w-full h-72 object-cover rounded" alt="">
                                <div class="mt-2 space-y-1">
                                    <button type="button" class="delete-btn w-full px-2 py-1 bg-red-100 text-red-700 text-xs rounded hover:bg-red-200 transition-colors" data-id="{{ $image->id }}">
                                        Sil
                                    </button>
                                    @if (!$image->is_featured)
                                        <button type="button" class="feature-btn w-full px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded hover:bg-blue-200 transition-colors" data-id="{{ $image->id }}">
                                            Öne Çıkar
                                        </button>
                                    @else
                                        <span class="inline-block w-full px-2 py-1 bg-green-100 text-green-700 text-xs rounded text-center">
                                            Öne Çıkan
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- New Images -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Yeni Resim Ekle</label>
                    <input type="file" name="images[]" multiple class="block w-full text-sm text-gray-500
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-md file:border-0
                        file:text-sm file:font-semibold
                        file:bg-blue-50 file:text-blue-700
                        hover:file:bg-blue-100">
                </div>

                <!-- Submit Button -->
                <div class="pt-4">
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                        Ürünü Güncelle
                    </button>
                    <input type="hidden" name="page" value="{{ request()->input('page', 1) }}">
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        function toggleStockInput(sizeId) {
            const checkbox = document.getElementById(`size_checkbox_${sizeId}`);
            const input = document.getElementById(`stock_input_${sizeId}`);

            if (checkbox.checked) {
                input.style.display = 'inline-block';
            } else {
                input.style.display = 'none';
                input.value = '';
            }
        }

            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function () {
                    const imageId = this.dataset.id;
                    const buttonRef = this;


                    Swal.fire({
                        title: 'Emin misiniz?',
                        text: "Bu resmi silmek istediğinize emin misiniz?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#881d1d',
                        cancelButtonColor: '#0e4c87',
                        confirmButtonText: 'Evet, sil!',
                        cancelButtonText: 'İptal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch(`/delete_product_image/${imageId}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken,
                                    'Accept': 'application/json',
                                    'Content-Type': 'application/json'
                                }
                            })
                                .then(response => {
                                    if (response.ok) {
                                        buttonRef.closest('.single-image-wrapper').remove();
                                        toastr.success('Resim başarıyla silindi!');
                                    } else {
                                        toastr.error('Bir hata oluştu!');
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    toastr.error('Bir hata oluştu!');
                                });
                        }
                    });
                });
            });
            document.querySelectorAll('.feature-btn').forEach(button => {
                button.addEventListener('click', function () {
                    const imageId = this.dataset.id;

                    fetch(`/set_featured_image/${imageId}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    })
                        .then(response => {
                            if (response.ok) {
                                toastr.success('Resim öne çıkarıldı!');
                                location.reload();
                            } else {
                                toastr.error('Bir hata oluştu!');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            toastr.error('Bir hata oluştu!');
                        });
                });
            });
        });

    </script>
@endpush
