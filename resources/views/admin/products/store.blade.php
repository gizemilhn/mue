@extends('admin.index')
@section('content')
    <div class="p-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Add Product</h1>
            <form action="{{route('upload_product')}}" method="Post" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Product Title -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Product Title</label>
                    <input type="text" name="title" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Product Description -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Product Description</label>
                    <textarea name="description" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 min-h-[100px]"></textarea>
                </div>

                <!-- Product Price -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Product Price</label>
                    <input type="text" name="price" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Product Category -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Product Category</label>
                    <select name="category" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-100 text-gray-900">
                        <option>Select a Option</option>
                        @foreach($category as $category)
                            <option value="{{$category->category_name}}">{{$category->category_name}}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Sizes and Stock -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Bedenler ve Stokları</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($sizes as $size)
                            <div class="flex items-center space-x-3 p-3 border border-gray-200 rounded-md">
                                <input type="checkbox" id="size_checkbox_{{ $size->id }}" class="size-checkbox h-5 w-5 text-blue-600 rounded focus:ring-blue-500"
                                       onchange="toggleStockInput({{ $size->id }})" name="sizes[{{ $size->id }}][selected]" value="1">
                                <label for="size_checkbox_{{ $size->id }}" class="text-sm text-gray-700">{{ $size->name }}</label>
                                <input type="number" name="sizes[{{ $size->id }}][stock]" placeholder="Stok" id="stock_input_{{ $size->id }}"
                                       class="stock-input w-20 px-2 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500" min="0" disabled>
                                @error('sizes.' . $size->id . '.stock')
                                <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Sale Information -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Sale Information</label>
                    <select name="is_published" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-100 text-gray-900">
                        <option value="1">On Sale</option>
                        <option value="0">Not On Sale</option>
                    </select>
                </div>

                <!-- Product Images -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Product Images</label>
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
                        Add Product
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        function toggleStockInput(sizeId) {
            const checkbox = document.getElementById(`size_checkbox_${sizeId}`);
            const stockInput = document.getElementById(`stock_input_${sizeId}`);
            stockInput.disabled = !checkbox.checked;
            if (!checkbox.checked) {
                stockInput.value = '';
            }
        }
    </script>
@endpush
