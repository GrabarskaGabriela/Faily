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
        <div class="col-lg-8">
            <div class="card shadow-sm border-black">
                <div class="card-body text-white " style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);">
                    <h1 class="card-title text-center mb-4">Centrum pomocy - strona jeszcze do przebudowy</h1>

                    <div class="mb-5">
                        <h3 class="mb-3">Najczęściej zadawane pytania</h3>
                        <div class="accordion" id="faqAccordion">
                            <div class="accordion-item mb-3 border-0 rounded-3 overflow-hidden shadow-sm">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        Jak zmienić zdjęcie profilowe?
                                    </button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                                     data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        Przejdź do swojego profilu, kliknij przycisk "Edytuj zdjęcie" i wybierz nowe zdjęcie z dysku.
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item mb-3 border-0 rounded-3 overflow-hidden shadow-sm">
                                <h2 class="accordion-header" id="headingTwo">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        Jak zaktualizować dane profilowe?
                                    </button>
                                </h2>
                                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                                     data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        W sekcji "Informacje" na swoim profilu możesz edytować wszystkie dane. Pamiętaj aby zapisać zmiany.
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item mb-3 border-0 rounded-3 overflow-hidden shadow-sm">
                                <h2 class="accordion-header" id="headingThree">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                        Jak skontaktować się z supportem?
                                    </button>
                                </h2>
                                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree"
                                     data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        Napisz do nas na adres: <a href="mailto:support@faily.pl">support@faily.pl</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h3 class="mb-3">Przydatne linki</h3>
                        <ul class="list-group">
                            <li class="list-group-item">
                                <a href="#" class="text-decoration-none">Regulamin serwisu</a>
                            </li>
                            <li class="list-group-item">
                                <a href="#" class="text-decoration-none">Polityka prywatności</a>
                            </li>
                        </ul>
                    </div>

                    <div class="text-center mt-5">
                        <h3 class="mb-3">Potrzebujesz dodatkowej pomocy?</h3>
                        <a href="mailto:support@faily.pl" class="btn text-white border-dark mt-2" style="background: linear-gradient(135deg, #5a00a0 0%, #7f00d4 100%);">
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
