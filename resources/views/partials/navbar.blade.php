<nav class="navbar navbar-expand-xl navbar-light bg-white shadow-sm fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand mr-0 mr-xl-2" href="{{ route('home') }}">
            {{ config('app.name') }}
        </a>

        @auth

            @can('search')
                <div class="d-inline-flex d-xl-none flex-grow-1 mx-4">
                    <div class="input-group global-search global-search-centered border rounded-sm flex-grow-1 mx-auto">
                        <span class="input-group-prepend">
                            <div class="input-group-text bg-transparent border-0">
                                <svg class="icon icon-16 text-muted">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#search"></use>
                                </svg>
                            </div>
                        </span>
                        <form class="form-inline needs-validation global-search-form flex-grow-1" action="{{ route('search.index') }}" method="get" novalidate>
                            <input type="search" name="query" class="form-control global-search-input outline-none border-0 pl-0 rounded-0 flex-grow-1" placeholder="Suche" autocomplete="off" required>
                        </form>
                        <span class="input-group-append ml-auto">
                            <button class="btn btn-outline-secondary border-0 text-gray-500 global-search-append-button" onclick="window.dispatchEvent(new CustomEvent('toggle-spotlight'))">
                                <span class="lead">
                                    <svg class="icon icon-baseline">
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#command"></use>
                                    </svg>
                                    <span>K</span>
                                </span>
                            </button>
                        </span>
                    </div>
                </div>
            @endcan

        @endauth

        <button class="p-2 bg-transparent border rounded-sm outline-none d-inline-flex d-xl-none position-relative" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <svg class="icon icon-24 align-self-center">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#menu"></use>
            </svg>
            @auth
                @if(Auth::user()->unreadNotifications()->count())
                    <span class="notification-badge"></span>
                @endif
            @endauth
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav">
                @auth
                    @if(auth()->user()->can('viewAny', \App\Models\Company::class) || auth()->user()->can('viewAny', \App\Models\Person::class) || auth()->user()->can('viewAny', \App\Models\Address::class))
                        <li class="nav-item dropdown">
                            <a class="nav-link @cannot('viewAny', \App\Models\Company::class) disabled @endcannot d-inline-flex align-items-center pr-0" href="{{ route('companies.index') }}">
                                <svg class="icon icon-20 mr-1">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#briefcase"></use>
                                </svg>
                                Firmen
                            </a>
                            @if(auth()->user()->can('viewAny', \App\Models\Person::class) || auth()->user()->can('viewAny', \App\Models\Address::class))
                                <a id="navbarCompaniesDropdown" class="nav-link dropdown-toggle d-inline-flex align-items-center pl-0 ml-n1" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="caret h-20"></span>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarCompaniesDropdown">
                                @can('viewAny', \App\Models\Person::class)
                                    <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('people.index') }}">
                                        <svg class="icon icon-16 mr-1">
                                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#users"></use>
                                        </svg>
                                        Personen
                                    </a>
                                @endcan
                                @can('viewAny', \App\Models\Address::class)
                                    <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('addresses.index') }}">
                                        <svg class="icon icon-16 mr-1">
                                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#map-pin"></use>
                                        </svg>
                                        Adressen
                                    </a>
                                @endcan
                                </div>
                            @endif
                        </li>
                    @endif

                    @can('viewAny', \App\Models\Project::class)
                        <li class="nav-item">
                            <a class="nav-link d-inline-flex align-items-center" href="{{ route('projects.index') }}">
                                <svg class="icon icon-20 mr-1">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#clipboard"></use>
                                </svg>
                                Projekte
                            </a>
                        </li>
                    @endcan

                    @if(auth()->user()->can('viewAny', \App\Models\Task::class) || auth()->user()->can('viewAny', \App\Models\Memo::class) || auth()->user()->can('viewAny', \App\Models\ServiceReport::class))
                        <li class="nav-item dropdown">
                            <a class="nav-link @cannot('viewAny', \App\Models\Task::class) disabled @endcannot d-inline-flex align-items-center pr-0" href="{{ route('tasks.index') }}">
                                <svg class="icon icon-20 mr-1">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#check-square"></use>
                                </svg>
                                Aufgaben
                            </a>
                            @if(auth()->user()->can('viewAny', \App\Models\Memo::class) || auth()->user()->can('viewAny', \App\Models\ServiceReport::class))
                                <a id="navbarTasksDropdown" class="nav-link dropdown-toggle d-inline-flex align-items-center pl-0 ml-n1" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="caret h-20"></span>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarTasksDropdown">
                                    @can('viewAny', \App\Models\Memo::class)
                                    <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('memos.index') }}">
                                        <svg class="icon icon-16 mr-1">
                                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#voicemail"></use>
                                        </svg>
                                        Aktenvermerke
                                    </a>
                                    @endcan
                                    @can('viewAny', \App\Models\ServiceReport::class)
                                        <a class="dropdown-item  d-inline-flex align-items-center" href="{{ route('service-reports.index') }}">
                                            <svg class="icon icon-16 mr-1">
                                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#settings"></use>
                                            </svg>
                                            Serviceberichte
                                        </a>
                                    @endcan
                                    @can('viewAny', \App\Models\AdditionsReport::class)
                                        <a class="dropdown-item  d-inline-flex align-items-center" href="{{ route('additions-reports.index') }}">
                                            <svg class="icon-bs icon-16 mr-1">
                                                <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#tools"></use>
                                            </svg>
                                            Regieberichte
                                        </a>
                                    @endcan
                                    @can('viewAny', \App\Models\InspectionReport::class)
                                        <a class="dropdown-item  d-inline-flex align-items-center" href="{{ route('inspection-reports.index') }}">
                                            <svg class="icon-bs icon-16 mr-1">
                                                <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#patch-check"></use>
                                            </svg>
                                            Prüfberichte
                                        </a>
                                    @endcan
                                    @can('viewAny', \App\Models\ConstructionReport::class)
                                        <a class="dropdown-item  d-inline-flex align-items-center" href="{{ route('construction-reports.index') }}">
                                            <svg class="icon-bs icon-16 mr-1">
                                                <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#hammer"></use>
                                            </svg>
                                            Bautagesberichte
                                        </a>
                                    @endcan
                                </div>
                            @endif
                        </li>
                    @endif

                    @if(auth()->user()->can('viewAny', \App\Models\Accounting::class) || auth()->user()->can('viewAny', \App\Models\Logbook::class))
                        <li class="nav-item dropdown">
                            <a class="nav-link @cannot('viewAny', \App\Models\Accounting::class) disabled @endcannot d-inline-flex align-items-center pr-0" href="{{ route('accounting.index') }}">
                                <svg class="icon icon-20 mr-1">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#clock"></use>
                                </svg>
                                Abrechnung
                            </a>
                            @can('viewAny', \App\Models\Logbook::class)
                                <a id="navbarAccountingDropdown" class="nav-link dropdown-toggle d-inline-flex align-items-center pl-0 ml-n1" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="caret h-20"></span>
                                </a>

                                <div class="dropdown-menu" aria-labelledby="navbarAccountingDropdown">
                                    <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('logbook.index') }}">
                                        <svg class="icon icon-16 mr-1">
                                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#book"></use>
                                        </svg>
                                        Fahrtenbuch
                                    </a>
                                </div>
                            @endcan
                        </li>
                    @endif

                    @if(auth()->user()->can('tools-viewlatestchanges') || auth()->user()->can('tools-scanqr') || auth()->user()->can('tools-viewexceptions'))
                        <li class="nav-item dropdown">
                            <a id="navbarHelpDropdown" class="nav-link dropdown-toggle d-inline-flex align-items-center" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <svg class="icon icon-20 mr-1">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#tool"></use>
                                </svg>
                                Tools
                            </a>

                            <div class="dropdown-menu" aria-labelledby="navbarHelpDropdown">
                                @can('tools-viewlatestchanges')
                                    <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('latest-changes.index') }}">
                                        <svg class="icon icon-16 mr-1">
                                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#activity"></use>
                                        </svg>
                                        Letzte Änderungen
                                    </a>
                                @endcan
                                @can('tools-scanqr')
                                    <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('qr-scan.index') }}">
                                        <svg class="icon icon-16 mr-1">
                                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#camera"></use>
                                        </svg>
                                        QR-Code scannen
                                    </a>
                                @endcan
                                @can('tools-viewexceptions')
                                    <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('exceptions.index') }}">
                                        <svg class="icon icon-16 mr-1">
                                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#alert-triangle"></use>
                                        </svg>
                                        Fehlerdateien
                                    </a>
                                @endcan
                            </div>
                        </li>
                    @endif

                    @if(auth()->user()->can('application-settings-update') || auth()->user()->can('viewAny', \App\Models\Employee::class) || auth()->user()->can('viewAny', \Spatie\Permission\Models\Role::class) || auth()->user()->can('viewAny', \App\Models\MaterialService::class) || auth()->user()->can('viewAny', \App\Models\WageService::class) || auth()->user()->can('viewAny', \App\Models\Vehicle::class))
                        <li class="nav-item dropdown">
                            <a class="nav-link @cannot('application-settings-update') disabled @endcannot d-inline-flex align-items-center pr-0" href="{{ route('application-settings.edit') }}">
                                <svg class="icon icon-20 mr-1">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#settings"></use>
                                </svg>
                                Einstellungen
                            </a>
                            @if(auth()->user()->can('viewAny', \App\Models\Employee::class) || auth()->user()->can('viewAny', \Spatie\Permission\Models\Role::class) || auth()->user()->can('viewAny', \App\Models\MaterialService::class) || auth()->user()->can('viewAny', \App\Models\WageService::class) || auth()->user()->can('viewAny', \App\Models\Vehicle::class))
                                <a id="navbarSettingsDropdown" class="nav-link dropdown-toggle d-inline-flex align-items-center pl-0 ml-n1" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="caret h-20"></span>
                                </a>

                                <div class="dropdown-menu" aria-labelledby="navbarSettingsDropdown">
                                    @can('viewAny', \App\Models\Employee::class)
                                        <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('employees.index') }}">
                                            <svg class="icon icon-16 mr-1">
                                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#users"></use>
                                            </svg>
                                            Mitarbeiter
                                        </a>
                                    @endcan
                                    @can('viewAny', \Spatie\Permission\Models\Role::class)
                                        <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('roles.index') }}">
                                            <svg class="icon icon-16 mr-1">
                                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#key"></use>
                                            </svg>
                                            Rollen
                                        </a>
                                    @endcan
                                    @if(auth()->user()->can('viewAny', \App\Models\MaterialService::class) || auth()->user()->can('viewAny', \App\Models\WageService::class))
                                        <a class="dropdown-item d-inline-flex align-items-center" href="{{ route( auth()->user()->can('viewAny', \App\Models\WageService::class) ? 'wage-services.index' : 'material-services.index') }}">
                                            <svg class="icon icon-16 mr-1">
                                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#cpu"></use>
                                            </svg>
                                            Leistungen
                                        </a>
                                    @endif
                                    @can('viewAny', \App\Models\Vehicle::class)
                                        <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('vehicles.index') }}">
                                            <svg class="icon icon-16 mr-1">
                                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#truck"></use>
                                            </svg>
                                            Fuhrpark
                                        </a>
                                    @endcan
                                </div>
                            @endif
                        </li>
                    @endif

                    @can('help-view')
                        <li class="nav-item dropdown">
                            <a class="nav-link d-inline-flex align-items-center pr-0" href="{{ route('help.index') }}">
                                <svg class="icon icon-20 mr-1">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#help-circle"></use>
                                </svg>
                                Hilfe
                            </a>
                            <a id="navbarHelpDropdown" class="nav-link dropdown-toggle d-inline-flex align-items-center pl-0 ml-n1" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="caret h-20"></span>
                            </a>

                            <div class="dropdown-menu" aria-labelledby="navbarHelpDropdown">
                                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('changelog.show') }}">
                                    <svg class="icon icon-16 mr-1">
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#info"></use>
                                    </svg>
                                    {{ config('app.name') }} @version('compact')
                                </a>
                            </div>
                        </li>
                    @endcan
                @endauth

            </ul>

            @auth

                @can('search')
                    <div class="d-none d-xl-inline-flex mx-2 flex-grow-1">
                        <div class="input-group global-search border rounded-sm flex-grow-1 ml-auto">
                            <span class="input-group-prepend">
                                <div class="input-group-text bg-transparent border-0">
                                    <svg class="icon icon-16 text-muted">
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#search"></use>
                                    </svg>
                                </div>
                            </span>
                            <form class="form-inline needs-validation global-search-form flex-grow-1" action="{{ route('search.index') }}" method="get" novalidate>
                                <input type="search" name="query" class="form-control global-search-input border-0 outline-none pl-0 flex-grow-1" placeholder="Suche" autocomplete="off" required>
                            </form>
                            <span class="input-group-append">
                                <button class="btn btn-outline-secondary border-0 text-gray-500 global-search-append-button" onclick="window.dispatchEvent(new CustomEvent('toggle-spotlight'))">
                                    <span class="lead">
                                        <svg class="icon icon-baseline">
                                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#command"></use>
                                        </svg>
                                        <span>K</span>
                                    </span>
                                </button>
                            </span>
                        </div>
                    </div>
                @endcan

            @endauth

            <ul class="navbar-nav ml-auto">
                <!-- Authentication Links -->
                @guest
                    <li class="nav-item">
                        <a class="nav-link d-inline-flex align-items-center" href="{{ route('login') }}">
                            <svg class="icon icon-20 mr-1">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#log-in"></use>
                            </svg>
                            {{ __('Login') }}
                        </a>
                    </li>
                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link d-inline-flex align-items-center" href="{{ route('register') }}">
                                <svg class="icon icon-20 mr-1">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#send"></use>
                                </svg>
                                {{ __('Register') }}
                            </a>
                        </li>
                    @endif
                @else
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-inline-flex align-items-center @if(Session::has('impersonatorId')) text-red @endif" id="navbarUserDropdown" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <svg class="icon icon-20 mr-1 @if(Auth::user()->unreadNotifications()->count()) text-red @endif" >
                                @if(Session::has('impersonatorId'))
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#user-plus"></use>
                                @elseif(Auth::user()->unreadNotifications()->count())
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#bell"></use>
                                @else
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#user"></use>
                                @endif
                            </svg>
                            {{ Auth::user()->person->first_name }}
                        </a>


                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarUserDropdown">
                            <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('home') }}">
                                <svg class="icon icon-16 mr-1">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#activity"></use>
                                </svg>
                                Übersicht
                            </a>
                            <a class="dropdown-item  d-inline-flex align-items-center @if(Auth::user()->unreadNotifications()->count()) text-red @endif" href="{{ route('notifications.index') }}">
                                <svg class="icon icon-16 mr-1">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#bell"></use>
                                </svg>
                                Benachrichtigungen
                            </a>
                            <a class="dropdown-item  d-inline-flex align-items-center" href="{{ route('user-settings.edit') }}">
                                <svg class="icon icon-16 mr-1">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#settings"></use>
                                </svg>
                                Einstellungen
                            </a>
                            @if(Session::has('impersonatorId'))
                                @can('impersonate', Auth::user()->employee)
                                    <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('employees.impersonate', Auth::user()->employee) }}">
                                        <svg class="icon icon-16 mr-1">
                                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#user-minus"></use>
                                        </svg>
                                        Zurück zum eigenen Benutzer
                                    </a>
                                @endcan
                            @else
                                <a class="dropdown-item  d-inline-flex align-items-center" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    <svg class="icon icon-16 mr-1">
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#log-out"></use>
                                    </svg>
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            @endif
                        </div>
                    </li>
                @endguest
            </ul>

        </div>
    </div>
</nav>
