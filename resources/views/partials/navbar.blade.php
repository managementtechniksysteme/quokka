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
                    @if(auth()->user()->can('viewAny', \App\Models\Company::class) || auth()->user()->can('viewAny', \App\Models\Person::class) || auth()->user()->can('viewAny', \App\Models\Address::class))
                        <li class="nav-item dropdown">
                            <a class="nav-link @cannot('viewAny', \App\Models\Company::class) disabled @endcannot d-inline-flex align-items-center pr-0" href="{{ route('companies.index') }}">
                                <svg class="icon icon-20 mr-2">
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
                                        <svg class="icon icon-16 mr-2">
                                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#users"></use>
                                        </svg>
                                        Personen
                                    </a>
                                @endcan
                                @can('viewAny', \App\Models\Address::class)
                                    <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('addresses.index') }}">
                                        <svg class="icon icon-16 mr-2">
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
                                <svg class="icon icon-20 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#clipboard"></use>
                                </svg>
                                Projekte
                            </a>
                        </li>
                    @endcan

                    @if(auth()->user()->can('viewAny', \App\Models\Task::class) || auth()->user()->can('viewAny', \App\Models\Memo::class) || auth()->user()->can('viewAny', \App\Models\ServiceReport::class))
                        <li class="nav-item dropdown">
                            <a class="nav-link @cannot('viewAny', \App\Models\Task::class) disabled @endcannot d-inline-flex align-items-center pr-0" href="{{ route('tasks.index') }}">
                                <svg class="icon icon-20 mr-2">
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
                                        <svg class="icon icon-16 mr-2">
                                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#voicemail"></use>
                                        </svg>
                                        Aktenvermerke
                                    </a>
                                    @endcan
                                    @can('viewAny', \App\Models\ServiceReport::class)
                                        <a class="dropdown-item  d-inline-flex align-items-center" href="{{ route('service-reports.index') }}">
                                            <svg class="icon icon-16 mr-2">
                                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#settings"></use>
                                            </svg>
                                            Serviceberichte
                                        </a>
                                    @endcan
                                </div>
                            @endif
                        </li>
                    @endif

                    @if(auth()->user()->can('viewAny', \App\Models\Accounting::class) || auth()->user()->can('viewAny', \App\Models\Logbook::class))
                        <li class="nav-item dropdown">
                            <a class="nav-link @cannot('viewAny', \App\Models\Accounting::class) disabled @endcannot d-inline-flex align-items-center pr-0" href="{{ route('accounting.index') }}">
                                <svg class="icon icon-20 mr-2">
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
                                        <svg class="icon icon-16 mr-2">
                                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#book"></use>
                                        </svg>
                                        Fahrtenbuch
                                    </a>
                                </div>
                            @endcan
                        </li>
                    @endif

                    @can('tools-scanqr')
                        <li class="nav-item dropdown">
                            <a id="navbarHelpDropdown" class="nav-link dropdown-toggle d-inline-flex align-items-center" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <svg class="icon icon-20 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#tool"></use>
                                </svg>
                                Tools
                            </a>

                            <div class="dropdown-menu" aria-labelledby="navbarHelpDropdown">
                                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('qr-scan.index') }}">
                                    <svg class="icon icon-16 mr-2">
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#camera"></use>
                                    </svg>
                                    QR-Code scannen
                                </a>
                            </div>
                        </li>
                    @endcan

                    @if(auth()->user()->can('application-settings-update') || auth()->user()->can('viewAny', \App\Models\Employee::class) || auth()->user()->can('viewAny', \Spatie\Permission\Models\Role::class) || auth()->user()->can('viewAny', \App\Models\MaterialService::class) || auth()->user()->can('viewAny', \App\Models\WageService::class) || auth()->user()->can('viewAny', \App\Models\Vehicle::class))
                        <li class="nav-item dropdown">
                            <a class="nav-link @cannot('application-settings-update') disabled @endcannot d-inline-flex align-items-center pr-0" href="{{ route('application-settings.edit') }}">
                                <svg class="icon icon-20 mr-2">
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
                                            <svg class="icon icon-16 mr-2">
                                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#users"></use>
                                            </svg>
                                            Mitarbeiter
                                        </a>
                                    @endcan
                                    @can('viewAny', \Spatie\Permission\Models\Role::class)
                                        <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('roles.index') }}">
                                            <svg class="icon icon-16 mr-2">
                                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#key"></use>
                                            </svg>
                                            Rollen
                                        </a>
                                    @endcan
                                    @if(auth()->user()->can('viewAny', \App\Models\MaterialService::class) || auth()->user()->can('viewAny', \App\Models\WageService::class))
                                        <a class="dropdown-item d-inline-flex align-items-center" href="{{ route( auth()->user()->can('viewAny', \App\Models\WageService::class) ? 'wage-services.index' : 'material-services.index') }}">
                                            <svg class="icon icon-16 mr-2">
                                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#cpu"></use>
                                            </svg>
                                            Leistungen
                                        </a>
                                    @endif
                                    @can('viewAny', \App\Models\Vehicle::class)
                                        <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('vehicles.index') }}">
                                            <svg class="icon icon-16 mr-2">
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
                                <svg class="icon icon-20 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#help-circle"></use>
                                </svg>
                                Hilfe
                            </a>
                            <a id="navbarHelpDropdown" class="nav-link dropdown-toggle d-inline-flex align-items-center pl-0 ml-n1" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="caret h-20"></span>
                            </a>

                            <div class="dropdown-menu" aria-labelledby="navbarHelpDropdown">
                                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('changelog.show') }}">
                                    <svg class="icon icon-16 mr-2">
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#info"></use>
                                    </svg>
                                    {{ config('app.name') }} @version('compact')
                                </a>
                            </div>
                        </li>
                    @endcan
                @endauth

            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
                <!-- Authentication Links -->
                @guest
                    <li class="nav-item">
                        <a class="nav-link d-inline-flex align-items-center" href="{{ route('login') }}">
                            <svg class="icon icon-20 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#log-in"></use>
                            </svg>
                            {{ __('Login') }}
                        </a>
                    </li>
                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link d-inline-flex align-items-center" href="{{ route('register') }}">
                                <svg class="icon icon-20 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#send"></use>
                                </svg>
                                {{ __('Register') }}
                            </a>
                        </li>
                    @endif
                @else
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-inline-flex align-items-center @if(Session::has('impersonatorId')) text-red @endif" id="navbarUserDropdown" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <svg class="icon icon-20 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#user"></use>
                            </svg>
                            {{ Auth::user()->person->first_name }}
                        </a>


                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarUserDropdown">
                            <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('home') }}">
                                <svg class="icon icon-16 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#activity"></use>
                                </svg>
                                Übersicht
                            </a>
                            <a class="dropdown-item  d-inline-flex align-items-center" href="{{ route('user-settings.edit') }}">
                                <svg class="icon icon-16 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#settings"></use>
                                </svg>
                                Einstellungen
                            </a>
                            @if(Session::has('impersonatorId'))
                                @if(\App\Models\User::find(Session::get('impersonatorId'))->can('impersonate', Auth::user()->employee))
                                    <form action="{{ route('employees.stop-impersonation', Auth::user()->employee) }}" method="post" >
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="dropdown-item d-inline-flex align-items-center">
                                            <svg class="icon icon-16 mr-2">
                                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#user-minus"></use>
                                            </svg>
                                            Zurück zum eigenen Benutzer
                                        </button>
                                    </form>
                                @endif
                            @else
                                <a class="dropdown-item  d-inline-flex align-items-center" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    <svg class="icon icon-16 mr-2">
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
