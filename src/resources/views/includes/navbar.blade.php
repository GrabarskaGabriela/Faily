<header>
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid nav-wrapper">

            <a class="navbar-brand fs-3 text-color me-3" href="{{ url('/') }}" style="text-decoration: none;">
                <img src="{{ asset('images/includes/logo.png') }}" alt="Logo" width="50" height="50" class="d-inline-block align-text-top"> Faily
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
                    aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">
                @auth
                    <ul class="navbar-nav mb-2 mb-lg-0 fs-5">
                        <li class="nav-item mx-2">
                            <a class="btn btn-gradient-nav" href="{{ url('/map') }}">{{ __('messages.navbar.eventMap') }}</a>
                        </li>
                        <li class="nav-item mx-2">
                            <a class="btn btn-gradient-nav" href="{{ url('/event_list') }}">{{ __('messages.navbar.events') }}</a>
                        </li>
                        <li class="nav-item mx-2">
                            <a class="btn btn-gradient-nav" href="{{ url('/about') }}">{{ __('messages.navbar.developers') }}</a>
                        </li>
                    </ul>

                    <div class="mx-3">
                        <a class="btn btn-gradient-nav" href="{{ url('/add_event') }}">{{ __('messages.navbar.newEvent') }}</a>
                    </div>
                    <div class="dropdown mx-2">
                        <a class="btn btn-gradient-nav dropdown-toggle" href="#" role="button"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            @if (app()->getLocale() == 'pl')
                                <span><img src="{{ asset('images/includes/pl.png') }}" alt="Poland" width="20" height="15"></span>
                            @elseif (app()->getLocale() == 'en')
                                <span><img src="{{ asset('images/includes/gb-eng.png') }}" alt="England" width="20" height="15"></span>
                            @elseif (app()->getLocale() == 'es')
                                <span><img src="{{ asset('images/includes/es.png') }}" alt="Spain" width="20" height="15"></span>
                            @elseif (app()->getLocale() == 'jpn')
                                <span><img src="{{ asset('images/includes/jp.png') }}" alt="Japan" width="20" height="15"></span>
                            @elseif (app()->getLocale() == 'ua')
                                <span><img src="{{ asset('images/includes/ua.png') }}" alt="Ukraine" width="20" height="15"></span>
                            @endif
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item text-color {{ app()->getLocale() == 'pl' ? 'active' : '' }}" href="{{ route('language.change', 'pl') }}"><img src="{{ asset('images/includes/pl.png') }}" alt="Poland" width="20" height="15">Polski</a></li>
                            <li><a class="dropdown-item text-color {{ app()->getLocale() == 'en' ? 'active' : '' }}" href="{{ route('language.change', 'en') }}"><img src="{{ asset('images/includes/gb-eng.png') }}" alt="England" width="20" height="15">English</a></li>
                            <li><a class="dropdown-item text-color {{ app()->getLocale() == 'es' ? 'active' : '' }}" href="{{ route('language.change', 'es') }}"><img src="{{ asset('images/includes/es.png') }}" alt="Spain" width="20" height="15">Español</a></li>
                            <li><a class="dropdown-item text-color {{ app()->getLocale() == 'jpn' ? 'active' : '' }}" href="{{ route('language.change', 'jpn') }}"><img src="{{ asset('images/includes/jp.png') }}" alt="Japan" width="20" height="15">日本語</a></li>
                            <li><a class="dropdown-item text-color {{ app()->getLocale() == 'ua' ? 'active' : '' }}" href="{{ route('language.change', 'ua') }}"><img src="{{ asset('images/includes/ua.png') }}" alt="Ukraine" width="20" height="15"> Українська</a></li>
                        </ul>
                    </div>

                    <div class="dropdown ms-auto">
                        <a class="btn btn-gradient-nav dropdown-toggle" href="#" role="button"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            {{ __('messages.navbar.account') }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item text-color" href="{{ url('/account') }}">{{ __('messages.navbar.goToAccount') }}</a></li>
                            <li><a class="dropdown-item text-color" href="{{ url('/my_events') }}">{{ __('messages.navbar.myEvents') }}</a></li>
                            <li><a class="dropdown-item text-color" href="{{ url('/help') }}">{{ __('messages.navbar.help') }}</a></li>
                            <li><a class="dropdown-item text-color" href="{{ url('/profile/dashboard') }}">{{ __('messages.navbar.settings') }}</a></li>
                            <li><hr class="dropdown-divider bg-white"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-color">{{ __('messages.navbar.signOut') }}</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <div class="dropdown mx-2">
                        <a class="btn btn-gradient-nav dropdown-toggle" href="#" role="button"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            @if (app()->getLocale() == 'pl')
                                <span><img src="{{ asset('images/includes/pl.png') }}" alt="Poland" width="20" height="15"></span>
                            @elseif (app()->getLocale() == 'en')
                                <span><img src="{{ asset('images/includes/gb-eng.png') }}" alt="England" width="20" height="15"></span>
                            @elseif (app()->getLocale() == 'es')
                                <span><img src="{{ asset('images/includes/es.png') }}" alt="Spain" width="20" height="15"></span>
                            @elseif (app()->getLocale() == 'jpn')
                                <span><img src="{{ asset('images/includes/jp.png') }}" alt="Japan" width="20" height="15"></span>
                            @elseif (app()->getLocale() == 'ua')
                                <span><img src="{{ asset('images/includes/ua.png') }}" alt="Ukraine" width="20" height="15"></span>
                            @endif
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item text-color {{ app()->getLocale() == 'pl' ? 'active' : '' }}" href="{{ route('language.change', 'pl') }}"><img src="{{ asset('images/includes/pl.png') }}" alt="Poland" width="20" height="15">Polski</a></li>
                            <li><a class="dropdown-item text-color {{ app()->getLocale() == 'en' ? 'active' : '' }}" href="{{ route('language.change', 'en') }}"><img src="{{ asset('images/includes/gb-eng.png') }}" alt="England" width="20" height="15">English</a></li>
                            <li><a class="dropdown-item text-color {{ app()->getLocale() == 'es' ? 'active' : '' }}" href="{{ route('language.change', 'es') }}"><img src="{{ asset('images/includes/es.png') }}" alt="Spain" width="20" height="15">Español</a></li>
                            <li><a class="dropdown-item text-color {{ app()->getLocale() == 'jpn' ? 'active' : '' }}" href="{{ route('language.change', 'jpn') }}"><img src="{{ asset('images/includes/jp.png') }}" alt="Japan" width="20" height="15">日本語</a></li>
                            <li><a class="dropdown-item text-color {{ app()->getLocale() == 'ua' ? 'active' : '' }}" href="{{ route('language.change', 'ua') }}"><img src="{{ asset('images/includes/ua.png') }}" alt="Ukraine" width="20" height="15"> Українська</a></li>
                        </ul>
                    </div>

                    <a href="{{ route('login') }}" class="btn btn-gradient-nav ms-auto">
                        {{ __('messages.navbar.signIn') }}
                    </a>
                @endauth
            </div>
        </div>
    </nav>
</header>
