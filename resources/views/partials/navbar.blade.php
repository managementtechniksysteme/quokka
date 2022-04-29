<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top">
    <div class="container-xl">
        <a class="navbar-brand" href="{{ route('home') }}">
            {{ config('app.name') }}
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto">
                @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link d-inline-flex align-items-center pr-0" href="{{ route('companies.index') }}">
                            <svg class="feather feather-20 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#briefcase"></use>
                            </svg>
                            Firmen
                        </a>
                        <a id="navbarCompaniesDropdown" class="nav-link dropdown-toggle d-inline-flex align-items-center pl-0 ml-n1" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="caret h-20"></span>
                        </a>

                        <div class="dropdown-menu" aria-labelledby="navbarCompaniesDropdown">
                            <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('people.index') }}">
                                <svg class="feather feather-16 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#users"></use>
                                </svg>
                                Personen
                            </a>
                            <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('addresses.index') }}">
                                <svg class="feather feather-16 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#map-pin"></use>
                                </svg>
                                Adressen
                            </a>
                        </div>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link d-inline-flex align-items-center" href="{{ route('projects.index') }}">
                            <svg class="feather feather-20 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#clipboard"></use>
                            </svg>
                            Projekte
                        </a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link d-inline-flex align-items-center pr-0" href="{{ route('tasks.index') }}">
                            <svg class="feather feather-20 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#check-square"></use>
                            </svg>
                            Aufgaben
                        </a>
                        <a id="navbarTasksDropdown" class="nav-link dropdown-toggle d-inline-flex align-items-center pl-0 ml-n1" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="caret h-20"></span>
                        </a>

                        <div class="dropdown-menu" aria-labelledby="navbarTasksDropdown">
                            <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('memos.index') }}">
                                <svg class="feather feather-16 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#voicemail"></use>
                                </svg>
                                Aktenvermerke
                            </a>
                            <a class="dropdown-item  d-inline-flex align-items-center" href="{{ route('service-reports.index') }}">
                                <svg class="feather feather-16 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#settings"></use>
                                </svg>
                                Serviceberichte
                            </a>
                        </div>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link d-inline-flex align-items-center pr-0" href="{{ route('accounting.index') }}">
                            <svg class="feather feather-20 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#clock"></use>
                            </svg>
                            Abrechnung
                        </a>
                        <a id="navbarAccountingDropdown" class="nav-link dropdown-toggle d-inline-flex align-items-center pl-0 ml-n1" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="caret h-20"></span>
                        </a>

                        <div class="dropdown-menu" aria-labelledby="navbarAccountingDropdown">
                            <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('logbook.index') }}">
                                <svg class="feather feather-16 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#book"></use>
                                </svg>
                                Fahrtenbuch
                            </a>
                        </div>
                    </li>

                    <li class="nav-item dropdown">
                        <a id="navbarHelpDropdown" class="nav-link dropdown-toggle d-inline-flex align-items-center" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <svg class="feather feather-20 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#tool"></use>
                            </svg>
                            Tools
                        </a>

                        <div class="dropdown-menu" aria-labelledby="navbarHelpDropdown">
                            <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('qr-scan.index') }}">
                                <svg class="feather feather-16 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#camera"></use>
                                </svg>
                                QR-Code scannen
                            </a>
                        </div>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link d-inline-flex align-items-center pr-0" href="{{ route('application-settings.edit') }}">
                            <svg class="feather feather-20 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#settings"></use>
                            </svg>
                            Einstellungen
                        </a>
                        <a id="navbarSettingsDropdown" class="nav-link dropdown-toggle d-inline-flex align-items-center pl-0 ml-n1" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="caret h-20"></span>
                        </a>

                        <div class="dropdown-menu" aria-labelledby="navbarSettingsDropdown">
                            <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('employees.index') }}">
                                <svg class="feather feather-16 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#users"></use>
                                </svg>
                                Mitarbeiter
                            </a>
                            <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('roles.index') }}">
                                <svg class="feather feather-16 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#key"></use>
                                </svg>
                                Rollen
                            </a>
                            <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('wage-services.index') }}">
                                <svg class="feather feather-16 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#cpu"></use>
                                </svg>
                                Leistungen
                            </a>
                            <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('vehicles.index') }}">
                                <svg class="feather feather-16 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#truck"></use>
                                </svg>
                                Fuhrpark
                            </a>
                        </div>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link d-inline-flex align-items-center pr-0" href="{{ route('help.index') }}">
                            <svg class="feather feather-20 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#help-circle"></use>
                            </svg>
                            Hilfe
                        </a>
                        <a id="navbarHelpDropdown" class="nav-link dropdown-toggle d-inline-flex align-items-center pl-0 ml-n1" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="caret h-20"></span>
                        </a>

                        <div class="dropdown-menu" aria-labelledby="navbarHelpDropdown">
                            <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('changelog.show') }}">
                                <svg class="feather feather-16 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#info"></use>
                                </svg>
                                {{ config('app.name') }} @version('compact')
                            </a>
                        </div>
                    </li>
                @endauth

            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
                <!-- Authentication Links -->
                @guest
                    <li class="nav-item">
                        <a class="nav-link d-inline-flex align-items-center" href="{{ route('login') }}">
                            <svg class="feather feather-20 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#log-in"></use>
                            </svg>
                            {{ __('Login') }}
                        </a>
                    </li>
                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link d-inline-flex align-items-center" href="{{ route('register') }}">
                                <svg class="feather feather-20 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#send"></use>
                                </svg>
                                {{ __('Register') }}
                            </a>
                        </li>
                    @endif
                @else
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-inline-flex align-items-center" id="navbarUserDropdown" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <svg class="feather feather-20 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#user"></use>
                            </svg>
                            {{ Auth::user()->person->first_name }}
                        </a>


                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarUserDropdown">
                            <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('home') }}">
                                <svg class="feather feather-16 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#activity"></use>
                                </svg>
                                Übersicht
                            </a>
                            <a class="dropdown-item  d-inline-flex align-items-center" href="{{ route('user-settings.edit') }}">
                                <svg class="feather feather-16 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#settings"></use>
                                </svg>
                                Einstellungen
                            </a>
                            <a class="dropdown-item  d-inline-flex align-items-center" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                <svg class="feather feather-16 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#log-out"></use>
                                </svg>
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
