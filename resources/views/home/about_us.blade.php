@extends('home.layout')
@section('content')

    <div class="bg-white text-gray-800 py-16 px-4">
        <div class="container mx-auto max-w-7xl space-y-24">

            {{-- Başlık --}}
            <div class="text-center">
                <h1 class="text-5xl md:text-6xl font-extrabold mb-4">Hakkımızda</h1>
                <p class="text-lg md:text-xl text-gray-500 max-w-2xl mx-auto">
                    Mue, kendi stilini keşfetmek isteyen herkesin ilham kaynağı olan bir moda butiğidir.
                </p>
            </div>

            {{-- Görsel + Metin Bloğu 1 --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="rounded-3xl overflow-hidden shadow-2xl h-96 lg:h-auto">
                    <img src="{{ asset('images/mue-look-1.jpeg') }}" alt="Mue Kombin" class="w-full h-full object-cover"/>
                </div>
                <div class="flex flex-col space-y-6">
                    <h2 class="text-3xl font-bold">Pinterest İlhamlı Kombinler</h2>
                    <p class="text-gray-600 leading-relaxed">
                        Mue, modayı sıradanlıktan çıkarıyor. Her bir parça, Pinterest'te saatler geçirip hayran kaldığın kombinlerden ilham alınarak seçiliyor. Modern, özgün ve cesur stilleri günlük yaşama taşıyoruz.
                    </p>
                    <p class="text-gray-600 leading-relaxed">
                        Giydiğin her parçanın bir hikayesi, bir enerjisi olsun istiyoruz. Mue ile kombin yapmak yalnızca giyinmek değil, kendini anlatmanın bir yolu haline geliyor.
                    </p>
                </div>
            </div>

            {{-- Görsel + Metin Bloğu 2 --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="lg:order-last rounded-3xl overflow-hidden shadow-2xl h-96 lg:h-auto">
                    <img src="{{ asset('images/mue-store.jpeg') }}" alt="Mue Mağaza" class="w-full h-full object-cover"/>
                </div>
                <div class="flex flex-col space-y-6">
                    <h2 class="text-3xl font-bold">Butikten Dijitale Uzanan Yolculuk</h2>
                    <p class="text-gray-600 leading-relaxed">
                        Mue, küçük bir butik olarak başladığı yolculuğuna şimdi online’da binlerce kadına ulaşarak devam ediyor. Her gönderdiğimiz paket, bizim için bir hikayenin parçası.
                    </p>
                    <p class="text-gray-600 leading-relaxed">
                        Ürünlerimizin arkasında samimiyet, stil ve kaliteli seçimler var. Her alışverişte sadece kıyafet değil, bir ruh hali gönderiyoruz.
                    </p>
                </div>
            </div>

            {{-- Vizyon & Misyon --}}
            <div class="bg-gray-100 p-8 rounded-3xl shadow-lg">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <h3 class="text-2xl font-bold mb-4">Vizyonumuz</h3>
                        <p class="text-gray-600 leading-relaxed">
                            İlham veren görsellerle tarzını yansıtabileceğin, zamansız ama trendleri takip eden bir moda anlayışı sunmak. Moda dünyasında kalıpları yıkan bir çizgiyle kendi hikayemizi yazmak.
                        </p>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold mb-4">Misyonumuz</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Her kadının kendini güçlü, özgün ve şık hissetmesini sağlamak. Kaliteli, ulaşılabilir ve ilham verici parçaları herkes için erişilebilir kılmak.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
