@extends('home.layout')

@section('content')

    <section class="bg-white text-gray-800 py-16 px-4">
        <div class="max-w-7xl mx-auto space-y-24">
            <!-- Başlık Bölümü -->
            <div class="text-center max-w-3xl mx-auto">
                <h1 class="text-4xl md:text-5xl font-bold mb-6">Bize Ulaşın</h1>
                <p class="text-xl text-gray-600">
                    Mue ile ilgili her türlü soru, öneri ve destek talepleriniz için bizimle iletişime geçebilirsiniz.
                </p>
            </div>

            <!-- Form ve Görsel Bölümü -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Form -->
                <div class="bg-gray-50 p-8 rounded-xl shadow-md">
                    <form action="{{ route('contact.submit') }}" method="POST" class="space-y-6">
                        @csrf
                        <div>
                            <label for="name" class="block text-lg font-medium mb-2">Adınız</label>
                            <input
                                type="text"
                                name="name"
                                id="name"
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent transition"
                                placeholder="Adınız ve soyadınız"
                            >
                        </div>
                        <div>
                            <label for="email" class="block text-lg font-medium mb-2">E-posta</label>
                            <input
                                type="email"
                                name="email"
                                id="email"
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent transition"
                                placeholder="email@example.com"
                            >
                        </div>
                        <div>
                            <label for="message" class="block text-lg font-medium mb-2">Mesajınız</label>
                            <textarea
                                name="message"
                                id="message"
                                rows="5"
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent transition"
                                placeholder="Mesajınızı buraya yazın..."
                            ></textarea>
                        </div>
                        <button
                            type="submit"
                            class="w-full bg-black text-white py-3 px-6 rounded-lg font-medium hover:bg-gray-800 transition duration-300"
                        >
                            Gönder
                        </button>
                    </form>
                </div>

                <!-- Görsel -->
                <div class="h-full rounded-xl overflow-hidden shadow-lg">
                    <img
                        src="{{ asset('images/contact-image.png') }}"
                        alt="İletişim Fotoğrafı"
                        class="w-full h-full object-cover"
                    >
                </div>
            </div>

            <!-- Adres ve Harita Bölümü -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Adres -->
                <div class="bg-gray-50 p-8 rounded-xl shadow-md">
                    <h2 class="text-2xl md:text-3xl font-bold mb-6">Mağaza Adresimiz</h2>
                    <div class="space-y-4 text-gray-700">
                        <p class="text-lg">
                            <span class="font-semibold">Mue Butik</span><br>
                            Moda Caddesi No: 24/A<br>
                            Kadıköy / İstanbul
                        </p>
                        <p class="text-lg">
                            <span class="font-semibold">Tel:</span> +90 555 123 45 67
                        </p>
                        <p class="text-lg">
                            <span class="font-semibold">E-posta:</span> iletisim@muebutik.com
                        </p>
                    </div>
                </div>

                <!-- Harita -->
                <div class="rounded-xl overflow-hidden shadow-lg h-96">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d12038.778357687688!2d29.0290966!3d40.9874554!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x14cac8c04fc5bb6f%3A0x5e3d9d437e3f92b5!2sModa%2C%20Kad%C4%B1k%C3%B6y%2F%C4%B0stanbul!5e0!3m2!1str!2str!4v1715352630160!5m2!1str!2str"
                        width="100%"
                        height="100%"
                        style="border:0;"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                    ></iframe>
                </div>
            </div>
        </div>
    </section>

@endsection
