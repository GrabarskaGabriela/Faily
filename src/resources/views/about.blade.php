<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Faily - konto</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .team-gallery {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 2rem;
            text-align: center;
        }

        .team-member {
            max-width: 400px;
            cursor: pointer;
        }

        .team-member img {
            width: 100%;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .member-description {
            display: none;
            margin-top: 2rem;
        }

        .member-description.active {
            display: block;
        }
    </style>
</head>

<body class="bg-main">
@include('includes.navbar')
<div class="page-container d-flex flex-column min-vh-100 ">
    <main class="container my-5 bg-light">
        <h2 class="text-center mb-5">Poznaj Twórców projektu</h2>
        <div class="team-gallery">
            <div class="team-member" onclick="showDescription('Wódz')">
                <img src="{{ asset('images/includes/wodz-faily.png') }}" alt="Wodz">
                <h5 class="mt-2">Wódz</h5>
            </div>
            <div class="team-member" onclick="showDescription('Gabi')">
                <img src="{{ asset('images/includes/gabi-faily.png') }}" alt="Gabi">
                <h5 class="mt-2">Gabi</h5>
            </div>
            <div class="team-member" onclick="showDescription('Chimek')">
                <img src="{{ asset('images/includes/chimek-faily.png') }}" alt="Chimek">
                <h5 class="mt-2">Chimek</h5>
            </div>
        </div>

        <div id="desc-Wódz" class="member-description active">
            <h4 class="text-center"><strong>Mateusz Bogacz-Drewniak</strong></h4>
            <p>
                Nieoficjalny, ale umówiony wódz projektu. Nikt nie wie, kto go wybrał, ale nikt też nie protestował – bo Wódz zawsze wie, co robić (albo przynajmniej sprawia takie wrażenie). Pilnuje pozostałej dwójki, jakby byli dziećmi z ADHD, chociaż sam je ma. Trzyma projekt w ryzach i mówi „zrób to lepiej” z taką pewnością, że człowiek robi. Podczas kodzenia słucha Twoyastara of Death i ogląda Kapitana Bombę, co czyni go nie tylko liderem, ale też artystą w cierpieniu. Gdy coś nie działa, poprawia. Gdy działa – i tak poprawia. Gdy milczy, wszyscy wiedzą, że coś wymyśla. Legenda głosi, że kiedyś jedną linijką naprawił cały projekt.
            </p>
        </div>

        <div id="desc-Gabi" class="member-description">
            <h4 class="text-center"><strong>Gabriela Grabarska</strong></h4>
            <p>
                Backendowa czarodziejka o oczach pełnych cierpienia i logów Dockera. Gdy inni śpią – ona debuguje. Gdy inni płaczą – ona tylko przeklina pod nosem. Jej największym wrogiem nie są bugi, ale zależności Laravela i composer.lock, który nie zna litości. Zawsze dostępna na Slacku po 3 w nocy, gotowa naprawić bazę danych albo przypadkiem ją usunąć – zależnie od dnia. Kocha chomiczki i memy z nimi, co czyni ją niebezpiecznym połączeniem uroku i wewnętrznej rozpaczy. Wierzy w testy jednostkowe tak, jak inni wierzą w horoskopy. Raz powiedziała „chyba działa” – i to był historyczny moment.
            </p>
        </div>

        <div id="desc-Chimek" class="member-description">
            <h4 class="text-center"><strong>Mateusz Chimkowski</strong></h4>
            <p>
                Zajmuje się frontendem, bo… no cóż, nic innego nie potrafi (ale za to robi to z takim rozmachem, że projekt płacze od zachwytu). W duszy – artysta. W praktyce – ktoś, kto 3 godziny debatuje nad odcieniem hovera, a potem i tak wraca do pierwszej wersji. Nieśmiertelny entuzjasta mangi, anime i wszystkiego, co można określić słowem „klimacik”. Gdy nie kodzi, to gra: Factorio, Schedule 1, Baldur’s Gate 3, czy CS2… wszystko, byle „ogarnąć to jutro”. Ma tysiąc plików nazwanych „final_v3_real” i każdy jest piękniejszy od poprzedniego. Gdy ktoś pyta „czemu nie działa?”, mówi „to artystyczna decyzja”. I wszyscy przytakują.
            </p>
        </div>
    </main>
</div>
@include('includes.footer')
<script>
    function showDescription(name) {
        document.querySelectorAll('.member-description').forEach(el => el.classList.remove('active'));
        document.getElementById('desc-' + name).classList.add('active');
    }
</script>
</body>
</html>
