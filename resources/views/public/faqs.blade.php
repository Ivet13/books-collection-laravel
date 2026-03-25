<x-public.public-layout>
    <h2>FAQS</h2>
    <div class="faq-cards">
        @foreach ($records as $faq)
            <div class="faq-card">
                <a href="{{ route('customer.faq', $faq->id) }}">
                    <span class="faq-label">Pregunta</span>
                    <h3>{{ data_get($faq, 'locale.en.title', $faq->title ?? '') }}</h3>
                    <span class="faq-label">Respuesta</span>
                    <p class="faq-description">
                        {{ data_get($faq, 'locale.en.description', $faq->description ?? '') }}
                    </p>
                </a>
            </div>
        @endforeach
    </div>
</x-public.public-layout>
