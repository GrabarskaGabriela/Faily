<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Faily - Pomoc</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-main">
@include('includes.navbar')

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="card shadow shadow-sm border-0 rounded-4 overflow-hidden">
                <div class="card-body text-color">
                    <h1 class="card-title text-center mb-5 display-5 fw-bold">Centrum pomocy</h1>
                    <p class="text-center text-color mb-4 fst-italic">Strona jest w trakcie przebudowy – dziękujemy za cierpliwość!</p>

                    <div class="mb-5">
                        <h3 class="mb-4">Najczęściej zadawane pytania</h3>
                        <div class="accordion" id="faqAccordion">
                            @foreach ([
                                ['title' => 'Jak zmienić zdjęcie profilowe?', 'content' => 'Przejdź do swojego profilu, kliknij przycisk "Edytuj zdjęcie" i wybierz nowe zdjęcie z dysku.'],
                                ['title' => 'Jak zaktualizować dane profilowe?', 'content' => 'W sekcji "Informacje" na swoim profilu możesz edytować wszystkie dane. Pamiętaj aby zapisać zmiany.'],
                                ['title' => 'Jak skontaktować się z supportem?', 'content' => 'Napisz do nas na adres: <a href="mailto:support@faily.pl" class="text-color text-decoration-underline">support@faily.pl</a>.']
                            ] as $index => $faq)
                                <div class="accordion-item bg-dark text-color border-0 mb-3 rounded-3 shadow-sm">
                                    <h2 class="accordion-header" id="heading{{ $index }}">
                                        <button class="accordion-button bg-dark text-color collapsed" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#collapse{{ $index }}"
                                                aria-expanded="false"
                                                aria-controls="collapse{{ $index }}">
                                            {{ $faq['title'] }}
                                        </button>
                                    </h2>
                                    <div id="collapse{{ $index }}" class="accordion-collapse collapse"
                                         aria-labelledby="heading{{ $index }}" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            {!! $faq['content'] !!}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-4">
                        <h3 class="mb-3">Przydatne linki</h3>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item bg-transparent border-secondary">
                                <a href="#" class="text-decoration-none text-light">Regulamin serwisu</a>
                            </li>
                            <li class="list-group-item bg-transparent border-secondary">
                                <a href="#" class="text-decoration-none text-light">Polityka prywatności</a>
                            </li>
                        </ul>
                    </div>
                    <div class="text-center mt-5">
                        <h3 class="mb-3">Potrzebujesz dodatkowej pomocy?</h3>
                        <a href="mailto:support@faily.pl" class="btn border-0 px-4 py-2 fw-semibold text-color"
                           style="background: linear-gradient(135deg, #7b2cbf 0%, #9d4edd 100%); border-radius: 30px;">
                            Napisz do nas
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('includes.footer')
</body>
</html>
