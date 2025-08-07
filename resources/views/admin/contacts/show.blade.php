@extends('admin.index')
@section('content')
            <div class="container mx-auto px-4 py-8">
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h1 class="text-2xl font-bold">İletişim Formu Detayı</h1>
                            <a href="{{ route('admin.contacts.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                                Geri Dön
                            </a>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Sol Taraf -->
                            <div>
                                <div class="mb-4">
                                    <h3 class="text-sm font-medium text-gray-500">Ad Soyad</h3>
                                    <p class="mt-1 text-sm text-gray-900">{{ $contact->name }}</p>
                                </div>

                                <div class="mb-4">
                                    <h3 class="text-sm font-medium text-gray-500">E-posta</h3>
                                    <p class="mt-1 text-sm text-gray-900">
                                        <a href="mailto:{{ $contact->email }}" class="text-blue-600 hover:text-blue-800">
                                            {{ $contact->email }}
                                        </a>
                                    </p>
                                </div>

                                <div class="mb-4">
                                    <h3 class="text-sm font-medium text-gray-500">Gönderim Tarihi</h3>
                                    <p class="mt-1 text-sm text-gray-900">
                                        {{ $contact->created_at->format('d.m.Y H:i') }}
                                    </p>
                                </div>
                            </div>

                            <!-- Sağ Taraf -->
                            <div>
                                <div class="mb-4">
                                    <h3 class="text-sm font-medium text-gray-500">Mesaj</h3>
                                    <div class="mt-1 p-4 bg-gray-50 rounded-md">
                                        <p class="text-sm text-gray-800 whitespace-pre-line">{{ $contact->message }}</p>
                                    </div>
                                </div>

                                @if($contact->is_read)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Okundu
                        </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            Yeni
                        </span>
                                @endif
                            </div>
                        </div>

                        <!-- Yanıtla Butonu -->
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <button
                                onclick="window.location.href='mailto:{{ $contact->email }}?subject=RE: {{ $contact->name }} ile ilgili yanıt'"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs uppercase tracking-widest rounded-md transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                <a href="https://mail.google.com/mail/?view=cm&fs=1&to={{ $contact->email }}&su=RE: {{ $contact->name }} ile ilgili yanıt"
                                   target="_blank" class="text-white">
                                    Gmail ile Yanıtla
                                </a>
                                <svg class="ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
@endsection

<script>
    document.getElementById('email-link').addEventListener('click', function(e) {
        if(!confirm('E-posta uygulaması açılsın mı?')) {
            e.preventDefault();
        }
    });
</script>
