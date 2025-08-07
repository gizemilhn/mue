<style>


    .container {
        max-width: 1400px;
        margin: 60px auto;
        padding: 0 15px;
    }

    /* Başlık */
    h1 {
        font-size: 3rem;
        font-weight: bold;
        text-align: center;
        margin-bottom: 16px;
    }

    h2 {
        font-size: 1.75rem;
        font-weight: 600;
        margin-bottom: 16px;
    }

    h3 {
        font-size: 1.25rem;
        font-weight: bold;
        margin-bottom: 8px;
    }

    /* Paragraf */
    p {
        color: #6b7280;
        font-size: 1rem;
        margin-bottom: 16px;
    }

    /* Başlık altı açıklama metni */
    p.text-xl {
        font-size: 1.25rem;
        text-align: center;
        margin-bottom: 24px;
    }

    /* Görsel Bloğu */
    .image-block {
        width: 100%;
        height: 100%;
        overflow: hidden;
        border-radius: 16px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .image-block img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Düzenli Grid */
    .grid-container {
        display: grid;
        grid-template-columns: 1fr;
        gap: 32px;
        margin-bottom: 48px;
    }

    .grid-container .image-block {
        max-height: 400px;
    }

    .grid-container .text-block {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    /* Vizyon ve Misyon */
    .vision-mission-container {
        background-color: #e5e7eb;
        border-radius: 16px;
        padding: 40px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin-top: 48px;
    }

    .vision-mission-container .grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 32px;
    }

    .vision-mission-container .grid div {
        display: flex;
        flex-direction: column;
    }

    .vision-mission-container .grid div p {
        font-size: 1rem;
        line-height: 1.6;
        color: #6b7280;
    }

    /* Responsive Design */
    @media (min-width: 768px) {
        .grid-container {
            grid-template-columns: repeat(2, 1fr);
            align-items: center;
        }

        .grid-container .image-block {
            max-height: 500px;
        }

        .vision-mission-container .grid {
            grid-template-columns: 1fr 1fr;
        }
    }

</style>


@extends('home.layout')
@section('content')

    <div class="bg-white text-gray-800 py-16 px-4">
        <div class="container space-y-24">

            {{-- Başlık --}}
            <div class="text-center">
                <h1>Hakkımızda</h1>
                <p class="text-xl">
                    Mue, kendi stilini keşfetmek isteyen herkesin ilham kaynağı olan bir moda butiğidir.
                </p>
            </div>

            {{-- Görsel + Metin Bloğu 1 --}}
            <div class="grid-container">
                <div class="image-block">
                    <img src="{{ asset('images/mue-look-1.jpeg') }}" alt="Mue Kombin" />
                </div>
                <div class="text-block">
                    <h2>Pinterest İlhamlı Kombinler</h2>
                    <p>
                        Mue, modayı sıradanlıktan çıkarıyor. Her bir parça, Pinterest'te saatler geçirip hayran kaldığın kombinlerden ilham alınarak seçiliyor. Modern, özgün ve cesur stilleri günlük yaşama taşıyoruz.
                    </p>
                    <p>
                        Giydiğin her parçanın bir hikayesi, bir enerjisi olsun istiyoruz. Mue ile kombin yapmak yalnızca giyinmek değil, kendini anlatmanın bir yolu haline geliyor.
                    </p>
                </div>
            </div>

            {{-- Görsel + Metin Bloğu 2 --}}
            <div class="grid-container">
                <div class="image-block">
                    <img src="{{ asset('images/mue-store.jpeg') }}" alt="Mue Mağaza" />
                </div>
                <div class="text-block">
                    <h2>Butikten Dijitale Uzanan Yolculuk</h2>
                    <p>
                        Mue, küçük bir butik olarak başladığı yolculuğuna şimdi online’da binlerce kadına ulaşarak devam ediyor. Her gönderdiğimiz paket, bizim için bir hikayenin parçası.
                    </p>
                    <p>
                        Ürünlerimizin arkasında samimiyet, stil ve kaliteli seçimler var. Her alışverişte sadece kıyafet değil, bir ruh hali gönderiyoruz.
                    </p>
                </div>
            </div>

            {{-- Vizyon & Misyon --}}
            <div class="vision-mission-container">
                <div class="grid">
                    <div>
                        <h3>Vizyonumuz</h3>
                        <p>
                            İlham veren görsellerle tarzını yansıtabileceğin, zamansız ama trendleri takip eden bir moda anlayışı sunmak. Moda dünyasında kalıpları yıkan bir çizgiyle kendi hikayemizi yazmak.
                        </p>
                    </div>
                    <div>
                        <h3>Misyonumuz</h3>
                        <p>
                            Her kadının kendini güçlü, özgün ve şık hissetmesini sağlamak. Kaliteli, ulaşılabilir ve ilham verici parçaları herkes için erişilebilir kılmak.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
