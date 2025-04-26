<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Faily - Twórcy</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .creator-section {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            color: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }

        .team-gallery {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 3rem;
            padding: 2rem;
        }

        .team-member {
            width: 280px;
            transition: all 0.3s ease;
            position: relative;
            perspective: 1000px;
        }

        .team-member:hover {
            transform: translateY(-10px);
        }

        .member-card {
            position: relative;
            width: 100%;
            height: 380px;
            transform-style: preserve-3d;
            transition: transform 0.8s;
        }

        .team-member:hover .member-card {
            transform: rotateY(180deg);
        }

        .member-front, .member-back {
            position: absolute;
            width: 100%;
            height: 100%;
            backface-visibility: hidden;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        }

        .member-front {
            background: linear-gradient(45deg, #2b5876 0%, #4e4376 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .member-back {
            background: linear-gradient(45deg, #4e4376 0%, #2b5876 100%);
            transform: rotateY(180deg);
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .member-img {
            width: 180px;
            height: 180px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid rgba(255,255,255,0.2);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            margin-bottom: 20px;
            transition: all 0.3s;
        }

        .team-member:hover .member-img {
            transform: scale(1.05);
            box-shadow: 0 8px 25px rgba(0,0,0,0.4);
        }

        .member-role {
            font-size: 1.2rem;
            color: #f8f9fa;
            margin-bottom: 10px;
            text-align: center;
        }

        .member-name {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 15px;
            text-align: center;
            color: white;
        }

        .member-description {
            font-size: 0.95rem;
            line-height: 1.6;
            text-align: center;
        }

        .member-social {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 20px;
        }

        .social-icon {
            color: white;
            font-size: 1.5rem;
            transition: all 0.3s;
        }

        .social-icon:hover {
            transform: scale(1.2);
            color: #17a2b8;
        }

        .team-stats {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            padding: 2rem;
            background: rgba(0,0,0,0.2);
            border-radius: 10px;
            margin: 2rem auto;
            max-width: 800px;
        }

        .stat-item {
            text-align: center;
            padding: 1rem;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            color: #17a2b8;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            font-size: 1rem;
            color: #adb5bd;
        }

        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
            100% { transform: translateY(0px); }
        }

        .floating {
            animation: float 4s ease-in-out infinite;
        }

        .delay-1 {
            animation-delay: 0.5s;
        }

        .delay-2 {
            animation-delay: 1s;
        }
    </style>
</head>

<body class="bg-main">
@include('includes.navbar')
<div class="page-container">
    <main class="container py-5">
        <div class="creator-section py-5 px-3 px-md-5 my-5">

            <div class="team-stats">
                <div class="stat-item">
                    <div class="stat-number">3</div>
                    <div class="stat-label">Twórców</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">∞</div>
                    <div class="stat-label">Pomysłów</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">1000+</div>
                    <div class="stat-label">Linijek kodu</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">24/7</div>
                    <div class="stat-label">Zaangażowania</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">100+</div>
                    <div class="stat-label">Chomiczków</div>
                </div>
            </div>

            <div class="team-gallery">
                <!-- Wódz -->
                <div class="team-member floating">
                    <div class="member-card">
                        <div class="member-front">
                            <img src="{{ asset('images/includes/wodz-faily.png') }}" alt="Wódz" class="member-img">
                            <h3 class="member-name">Mateusz Bogacz-Drewniak</h3>
                            <div class="member-role">Backend Developer</div>
                        </div>
                        <div class="member-back">
                            <p class="member-description">
                                Nieoficjalny lider projektu. Mistrz debugowania i jednocześnie główny źródło inspiracji.
                                Kiedy nie naprawia kodu, słucha ciężkiego metalu i szuka nowych wyzwań.
                            </p>
                            <div class="member-social">
                                <a href="https://github.com/mateusz-bogacz-collegiumwitelona" class="social-icon" title="Github" target="_blank" rel="noopener noreferrer">
                                    <img src="{{ asset('images/includes/github-mark-white.png') }}" alt="Github Logo" class="social-logo" width="20" height="20">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Gabi -->
                <div class="team-member floating delay-1">
                    <div class="member-card">
                        <div class="member-front">
                            <img src="{{ asset('images/includes/gabi-faily.png') }}" alt="Gabi" class="member-img">
                            <h3 class="member-name">Gabriela Grabarska</h3>
                            <div class="member-role">Frontend Developer</div>
                        </div>
                        <div class="member-back">
                            <p class="member-description">
                                Czarodziejka backendu, która potrafi zaczarować nawet najbardziej oporną bazę danych.
                                Jej supermocą jest znajdowanie rozwiązań w środku nocy.
                            </p>
                            <div class="member-social">
                                <a href="https://github.com/GrabarskaGabriela" class="social-icon" title="Github" target="_blank" rel="noopener noreferrer">
                                    <img src="{{ asset('images/includes/github-mark-white.png') }}" alt="Github Logo" class="social-logo" width="20" height="20">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chimek -->
                <div class="team-member floating delay-2">
                    <div class="member-card">
                        <div class="member-front">
                            <img src="{{ asset('images/includes/chimek-faily.png') }}" alt="Chimek" class="member-img">
                            <h3 class="member-name">Mateusz Chimkowski</h3>
                            <div class="member-role">UX/UI Designer</div>
                        </div>
                        <div class="member-back">
                            <p class="member-description">
                                Wizjoner interfejsów użytkownika. Spędza godziny na dopracowywaniu szczegółów,
                                a jego paleta kolorów jest legendarna. Miłośnik anime i gier komputerowych.
                            </p>
                            <div class="member-social">
                                <a href="https://github.com/Chimek006" class="social-icon" title="Github" target="_blank" rel="noopener noreferrer">
                                    <img src="{{ asset('images/includes/github-mark-white.png') }}" alt="Github Logo" class="social-logo" width="20" height="20">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dodatkowa sekcja z pełnymi opisami -->
        <div class="creator-full-descriptions mt-5 p-4 text-white rounded-3" style=" background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);">
            <h2 class="text-center mb-4">Poznaj nas bliżej</h2>

            <div class="creator-tabs">
                <ul class="nav nav-tabs justify-content-center" id="creatorTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="wodz-tab" data-bs-toggle="tab" data-bs-target="#wodz" type="button" role="tab">Wódz</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="gabi-tab" data-bs-toggle="tab" data-bs-target="#gabi" type="button" role="tab">Gabi</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="chimek-tab" data-bs-toggle="tab" data-bs-target="#chimek" type="button" role="tab">Chimek</button>
                    </li>
                </ul>
                <div class="tab-content p-3" id="creatorTabContent">
                    <div class="tab-pane fade show active" id="wodz" role="tabpanel">
                        <p>Nieoficjalny, ale umówiony wódz projektu. Nikt nie wie, kto go wybrał, ale nikt też nie protestował – bo Wódz zawsze wie, co robić (albo przynajmniej sprawia takie wrażenie). Pilnuje pozostałej dwójki, jakby byli dziećmi z ADHD, chociaż sam je ma. Trzyma projekt w ryzach i mówi „zrób to lepiej” z taką pewnością, że człowiek robi. Podczas kodzenia słucha Twoyastara of Death i ogląda Kapitana Bombę, co czyni go nie tylko liderem, ale też artystą w cierpieniu. Gdy coś nie działa, poprawia. Gdy działa – i tak poprawia. Gdy milczy, wszyscy wiedzą, że coś wymyśla. Legenda głosi, że kiedyś jedną linijką naprawił cały projekt.</p>
                    </div>
                    <div class="tab-pane fade" id="gabi" role="tabpanel">
                        <p>Backendowa czarodziejka o oczach pełnych cierpienia i logów Dockera. Gdy inni śpią – ona debuguje. Gdy inni płaczą – ona tylko przeklina pod nosem. Jej największym wrogiem nie są bugi, ale zależności Laravela i composer.lock, który nie zna litości. Zawsze dostępna na Slacku po 3 w nocy, gotowa naprawić bazę danych albo przypadkiem ją usunąć – zależnie od dnia. Kocha chomiczki i memy z nimi, co czyni ją niebezpiecznym połączeniem uroku i wewnętrznej rozpaczy. Wierzy w testy jednostkowe tak, jak inni wierzą w horoskopy. Raz powiedziała „chyba działa” – i to był historyczny moment.</p>
                    </div>
                    <div class="tab-pane fade" id="chimek" role="tabpanel">
                        <p>Zajmuje się frontendem, bo… no cóż, nic innego nie potrafi (ale za to robi to z takim rozmachem, że projekt płacze od zachwytu). W duszy – artysta. W praktyce – ktoś, kto 3 godziny debatuje nad odcieniem hovera, a potem i tak wraca do pierwszej wersji. Nieśmiertelny entuzjasta mangi, anime i wszystkiego, co można określić słowem „klimacik”. Gdy nie kodzi, to gra: Factorio, Schedule 1, Baldur's Gate 3, czy CS2… wszystko, byle „ogarnąć to jutro”. Ma tysiąc plików nazwanych „final_v3_real” i każdy jest piękniejszy od poprzedniego. Gdy ktoś pyta „czemu nie działa?”, mówi „to artystyczna decyzja”. I wszyscy przytakują.</p>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
@include('includes.footer')

<script>
    document.querySelectorAll('.team-member').forEach(member => {
        member.addEventListener('click', function() {
            const memberName = this.querySelector('.member-name').textContent;
            const tabId = memberName.includes('Bogacz') ? 'wodz-tab' :
                memberName.includes('Grabarska') ? 'gabi-tab' : 'chimek-tab';
            const tab = document.getElementById(tabId);
            const tabInstance = new bootstrap.Tab(tab);
            tabInstance.show();
            document.getElementById('creatorTabContent').scrollIntoView({
                behavior: 'smooth'
            });
        });
    });
</script>
</body>
</html>
