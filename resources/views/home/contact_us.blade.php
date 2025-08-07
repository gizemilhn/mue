@extends('home.layout')

<style>

    .contact-section {
        max-width: 1400px;
        margin: 60px auto;
        padding: 0 15px;
        display: flex;
        justify-content: center;
        flex-direction: column;
    }

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

    p {
        color: #6b7280;
        font-size: 1rem;
        margin-bottom: 16px;
    }

    .form-section {
        display: grid;
        grid-template-columns: 1fr;
        gap: 32px;
    }

    .form-section .form-block {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .form-section input,
    .form-section textarea,
    .form-section button {
        border: 1px solid #d1d5db;
        border-radius: 8px;
        padding: 12px 16px;
        font-size: 1rem;
    }

    .form-section input,
    .form-section textarea {
        width: 100%;
    }

    .form-section button {
        margin-top: 10px;
        background-color: #000;
        color: white;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .form-section button:hover {
        background-color: #333;
    }

    .form-section .image-block {
        max-width: 100%;
        height: 100%;
        overflow: hidden;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .form-section .image-block img {
        object-fit: cover;
        width: 100%;
        height: 100%;
    }

    .address-map-section {
        display: grid;
        grid-template-columns: 1fr;
        gap: 32px;
        margin-top: 40px;
    }

    .address-map-section .address-block {
        background-color: #f3f4f6;
        padding: 24px;
        border-radius: 12px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .address-map-section .map-block {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        height: 400px;
    }

    @media (min-width: 768px) {
        .form-section {
            grid-template-columns: 1fr 1fr;
            align-items: center;
        }

        .address-map-section {
            grid-template-columns: 1fr 1fr;
        }

        .form-section .image-block {
            max-height: 450px;
        }

        .address-map-section .map-block {
            height: 450px;
        }
    }
</style>

@section('content')

    <section class="contact-section">
        <div class="bg-white text-gray-800 py-16 px-4">
            <div class="max-w-[1400px] mx-auto space-y-24">

                <div class="text-center">
                    <h1>Bize Ulaşın</h1>
                    <p class="mt-6 text-xl text-gray-600 max-w-3xl mx-auto">
                        Mue ile ilgili her türlü soru, öneri ve destek talepleriniz için bizimle iletişime geçebilirsiniz.
                    </p>
                </div>

                <div class="form-section">
                    <div class="form-block">
                        <form action="{{ route('contact.submit') }}" method="POST">
                            @csrf
                            <div>
                                <label for="name" class="font-semibold">Adınız</label>
                                <input type="text" name="name" id="name" required>
                            </div>
                            <div>
                                <label for="email" class="font-semibold">E-posta</label>
                                <input type="email" name="email" id="email" required>
                            </div>
                            <div>
                                <label for="message" class="font-semibold">Mesajınız</label>
                                <textarea name="message" id="message" rows="5" required></textarea>
                            </div>
                            <button type="submit">Gönder</button>
                        </form>
                    </div>
                    <div class="image-block">
                        <img src="{{ asset('images/contact-image.png') }}" alt="İletişim Fotoğrafı">
                    </div>
                </div>

                <div class="address-map-section">
                    <div class="address-block">
                        <h2>Mağaza Adresimiz</h2>
                        <p class="text-gray-700">
                            Mue Butik<br>
                            Moda Caddesi No: 24/A<br>
                            Kadıköy / İstanbul<br>
                            Tel: +90 555 123 45 67<br>
                            E-posta: iletisim@muebutik.com
                        </p>
                    </div>
                    <div class="map-block">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d12038.778357687688!2d29.0290966!3d40.9874554!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x14cac8c04fc5bb6f%3A0x5e3d9d437e3f92b5!2sModa%2C%20Kad%C4%B1k%C3%B6y%2F%C4%B0stanbul!5e0!3m2!1str!2str!4v1715352630160!5m2!1str!2str"
                            width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>

            </div>
        </div>
    </section>

@endsection
