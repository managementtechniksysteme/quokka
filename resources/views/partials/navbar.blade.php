<nav class="navbar navbar-expand-xl fixed-top q-topbar">
    <div class="container-fluid">
        <a class="navbar-brand me-0 me-xl-2" href="{{ route('home') }}">
            <span class="q-brand-badge">{{ Str::substr(config('app.name'), 0, 1) }}</span>
            {{ config('app.name') }}
        </a>

        @auth

            @can('search')
                <div class="d-inline-flex d-xl-none flex-grow-1 mx-4">
                    <div class="input-group global-search global-search-centered border rounded-1 flex-grow-1 mx-auto">
                            <div class="input-group-text bg-transparent border-0">
                                <svg class="icon-bs icon-16 text-muted">
                                    <use href="{{ asset('svg/bootstrap-icons.svg') }}#search"></use>
                                </svg>
                            </div>
                        <form class="d-flex needs-validation global-search-form flex-grow-1" action="{{ route('search.index') }}" method="get" novalidate>
                            <input type="search" name="query" class="form-control global-search-input outline-none border-0 ps-0 rounded-0 flex-grow-1" placeholder="Suche" autocomplete="off" required>
                        </form>
                            <button class="btn btn-outline-secondary border-0 text-gray-500 global-search-append-button" onclick="window.dispatchEvent(new CustomEvent('toggle-spotlight'))">
                                <span class="lead">
                                    <svg class="icon-bs icon-baseline">
                                        <use href="{{ asset('svg/bootstrap-icons.svg') }}#chevron-up"></use>
                                    </svg>
                                    <span>K</span>
                                </span>
                            </button>
                    </div>
                </div>
            @endcan

        @endauth

        <button class="p-2 bg-transparent border rounded-1 outline-none d-inline-flex d-xl-none position-relative" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <svg class="icon-bs icon-24 align-self-center">
                <use href="{{ asset('svg/bootstrap-icons.svg') }}#list"></use>
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
                        <li @class(['nav-item', 'dropdown', 'active' => request()->routeIs('companies.*', 'people.*', 'addresses.*')])>
                            <a class="nav-link @cannot('viewAny', \App\Models\Company::class) disabled @endcannot d-inline-flex align-items-center pe-0" href="{{ route('companies.index') }}">
                                <svg class="icon-bs icon-20 me-1">
                                    <use href="{{ asset('svg/bootstrap-icons.svg') }}#briefcase"></use>
                                </svg>
                                Firmen
                            </a>
                            @if(auth()->user()->can('viewAny', \App\Models\Person::class) || auth()->user()->can('viewAny', \App\Models\Address::class))
                                <a id="navbarCompaniesDropdown" class="nav-link dropdown-toggle d-inline-flex align-items-center ps-0" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="caret h-20"></span>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarCompaniesDropdown">
                                @can('viewAny', \App\Models\Person::class)
                                    <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('people.index') }}">
                                        <svg class="icon-bs icon-16 me-1">
                                            <use href="{{ asset('svg/bootstrap-icons.svg') }}#people"></use>
                                        </svg>
                                        Personen
                                    </a>
                                @endcan
                                @can('viewAny', \App\Models\Address::class)
                                    <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('addresses.index') }}">
                                        <svg class="icon-bs icon-16 me-1">
                                            <use href="{{ asset('svg/bootstrap-icons.svg') }}#geo-alt"></use>
                                        </svg>
                                        Adressen
                                    </a>
                                @endcan
                                </div>
                            @endif
                        </li>
                    @endif

                    @if(auth()->user()->can('viewAny', \App\Models\Project::class) || auth()->user()->can('finances-view'))
                        <li @class(['nav-item', 'dropdown', 'active' => request()->routeIs('projects.*', 'project-controlling.*')])>
                            <a class="nav-link @cannot('viewAny', \App\Models\Project::class) disabled @endcan d-inline-flex align-items-center pe-0" href="{{ route('projects.index') }}">
                                <svg class="icon-bs icon-20 me-1">
                                    <use href="{{ asset('svg/bootstrap-icons.svg') }}#clipboard"></use>
                                </svg>
                                Projekte
                            </a>
                            @can('finances-view')
                                <a id="navbarCompaniesDropdown" class="nav-link dropdown-toggle d-inline-flex align-items-center ps-0" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="caret h-20"></span>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarCompaniesDropdown">
                                    <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('project-controlling.index') }}">
                                        <svg class="icon-bs icon-16 me-1">
                                            <use href="{{ asset('svg/bootstrap-icons.svg') }}#bar-chart"></use>
                                        </svg>
                                        Controlling
                                    </a>
                                </div>
                            @endcan
                        </li>
                    @endif

                    @if(auth()->user()->can('viewAny', \App\Models\Task::class) || auth()->user()->can('viewAny', \App\Models\Memo::class) || auth()->user()->can('viewAny', \App\Models\ServiceReport::class) || auth()->user()->can('viewAny', \App\Models\AdditionsReport::class) || auth()->user()->can('viewAny', \App\Models\InspectionReport::class) || auth()->user()->can('viewAny', \App\Models\FlowMeterInspectionReport::class) || auth()->user()->can('viewAny', \App\Models\ConstructionReport::class) || auth()->user()->can('viewAny', \App\Models\DeliveryNote::class))
                        <li @class(['nav-item', 'dropdown', 'active' => request()->routeIs('tasks.*', 'memos.*', 'service-reports.*', 'additions-reports.*', 'inspection-reports.*', 'flow-meter-inspection-reports.*', 'construction-reports.*', 'delivery-notes.*')])>
                            <a class="nav-link @cannot('viewAny', \App\Models\Task::class) disabled @endcannot d-inline-flex align-items-center pe-0" href="{{ route('tasks.index') }}">
                                <svg class="icon-bs icon-20 me-1">
                                    <use href="{{ asset('svg/bootstrap-icons.svg') }}#check2-square"></use>
                                </svg>
                                Aufgaben
                            </a>
                            @if(auth()->user()->can('viewAny', \App\Models\Memo::class) || auth()->user()->can('viewAny', \App\Models\ServiceReport::class))
                                <a id="navbarTasksDropdown" class="nav-link dropdown-toggle d-inline-flex align-items-center ps-0" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="caret h-20"></span>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarTasksDropdown">
                                    @can('viewAny', \App\Models\Memo::class)
                                    <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('memos.index') }}">
                                        <svg class="icon-bs icon-16 me-1">
                                            <use href="{{ asset('svg/bootstrap-icons.svg') }}#voicemail"></use>
                                        </svg>
                                        Aktenvermerke
                                    </a>
                                    @endcan
                                    @can('viewAny', \App\Models\ServiceReport::class)
                                        <a class="dropdown-item  d-inline-flex align-items-center" href="{{ route('service-reports.index') }}">
                                            <svg class="icon-bs icon-16 me-1">
                                                <use href="{{ asset('svg/bootstrap-icons.svg') }}#gear"></use>
                                            </svg>
                                            Serviceberichte
                                        </a>
                                    @endcan
                                    @can('viewAny', \App\Models\AdditionsReport::class)
                                        <a class="dropdown-item  d-inline-flex align-items-center" href="{{ route('additions-reports.index') }}">
                                            <svg class="icon-bs icon-16 me-1">
                                                <use href="{{ asset('svg/bootstrap-icons.svg') }}#tools"></use>
                                            </svg>
                                            Regieberichte
                                        </a>
                                    @endcan
                                    @can('viewAny', \App\Models\InspectionReport::class)
                                        <a class="dropdown-item  d-inline-flex align-items-center" href="{{ route('inspection-reports.index') }}">
                                            <svg class="icon-bs icon-16 me-1">
                                                <use href="{{ asset('svg/bootstrap-icons.svg') }}#patch-check"></use>
                                            </svg>
                                            Prüfberichte
                                        </a>
                                    @endcan
                                    @can('viewAny', \App\Models\FlowMeterInspectionReport::class)
                                        <a class="dropdown-item  d-inline-flex align-items-center" href="{{ route('flow-meter-inspection-reports.index') }}">
                                            <svg class="icon-bs icon-16 me-1">
                                                <use href="{{ asset('svg/bootstrap-icons.svg') }}#patch-check"></use>
                                            </svg>
                                            Prüfberichte für Durchflussmesseinrichtungen
                                        </a>
                                    @endcan
                                    @can('viewAny', \App\Models\ConstructionReport::class)
                                        <a class="dropdown-item  d-inline-flex align-items-center" href="{{ route('construction-reports.index') }}">
                                            <svg class="icon-bs icon-16 me-1">
                                                <use href="{{ asset('svg/bootstrap-icons.svg') }}#hammer"></use>
                                            </svg>
                                            Bautagesberichte
                                        </a>
                                    @endcan
                                    @can('viewAny', \App\Models\DeliveryNote::class)
                                        <a class="dropdown-item  d-inline-flex align-items-center" href="{{ route('delivery-notes.index') }}">
                                            <svg class="icon-bs icon-16 me-1">
                                                <use href="{{ asset('svg/bootstrap-icons.svg') }}#box-seam"></use>
                                            </svg>
                                            Lieferscheine
                                        </a>
                                    @endcan
                                </div>
                            @endif
                        </li>
                    @endif

                    @if(auth()->user()->can('viewAny', \App\Models\Accounting::class) || auth()->user()->can('viewAny', \App\Models\Logbook::class))
                        <li @class(['nav-item', 'dropdown', 'active' => request()->routeIs('accounting.*', 'logbook.*')])>
                            <a class="nav-link @cannot('viewAny', \App\Models\Accounting::class) disabled @endcannot d-inline-flex align-items-center pe-0" href="{{ route('accounting.index') }}">
                                <svg class="icon-bs icon-20 me-1">
                                    <use href="{{ asset('svg/bootstrap-icons.svg') }}#clock"></use>
                                </svg>
                                Abrechnung
                            </a>
                            @can('viewAny', \App\Models\Logbook::class)
                                <a id="navbarAccountingDropdown" class="nav-link dropdown-toggle d-inline-flex align-items-center ps-0" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="caret h-20"></span>
                                </a>

                                <div class="dropdown-menu" aria-labelledby="navbarAccountingDropdown">
                                    <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('logbook.index') }}">
                                        <svg class="icon-bs icon-16 me-1">
                                            <use href="{{ asset('svg/bootstrap-icons.svg') }}#journal"></use>
                                        </svg>
                                        Fahrtenbuch
                                    </a>
                                </div>
                            @endcan
                        </li>
                    @endif

                    @if(auth()->user()->can('finances-view') || auth()->user()->can('viewAny', \App\Models\FinanceGroup::class))
                        <li @class(['nav-item', 'dropdown', 'active' => request()->routeIs('finances.*', 'project-finances.*', 'finance-groups.*')])>
                            <a class="nav-link @cannot('finances-view') disabled @endcannot d-inline-flex align-items-center pe-0" href="{{ route('finances.index') }}">
                                <svg class="icon-bs icon-20 me-1">
                                    <use href="{{ asset('svg/bootstrap-icons.svg') }}#currency-euro"></use>
                                </svg>
                                Finanzen
                            </a>

                            <a id="navbarFinancesDropdown" class="nav-link dropdown-toggle d-inline-flex align-items-center ps-0" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="caret h-20"></span>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarFinancesDropdown">
                            @can('finances-view')
                                    <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('project-finances.index') }}">
                                        <svg class="icon-bs icon-16 me-1">
                                            <use href="{{ asset('svg/bootstrap-icons.svg') }}#clipboard"></use>
                                        </svg>
                                        Projektübersicht
                                    </a>
                            @endcan
                            @can('viewAny', \App\Models\FinanceGroup::class)
                                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('finance-groups.index') }}">
                                    <svg class="icon-bs icon-16 me-1">
                                        <use href="{{ asset('svg/bootstrap-icons.svg') }}#list"></use>
                                    </svg>
                                    Manuelle Einträge
                                </a>
                            @endcan
                            @if(auth()->user()->can('finances-view') || auth()->user()->can('viewAny', \App\Models\FinanceGroup::class))
                                </div>
                            @endif
                        </li>
                    @endif


                    <li @class(['nav-item', 'dropdown', 'active' => request()->routeIs('quokka-mobile.*', 'latest-changes.*', 'sent-emails.*', 'qr-scan.*', 'exceptions.*')])>
                        <a id="navbarHelpDropdown" class="nav-link dropdown-toggle d-inline-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <svg class="icon-bs icon-20 me-1">
                                <use href="{{ asset('svg/bootstrap-icons.svg') }}#tools"></use>
                            </svg>
                            Tools
                        </a>

                        <div class="dropdown-menu" aria-labelledby="navbarHelpDropdown">
                            <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('quokka-mobile.index') }}">
                                <svg class="icon-bs icon-16 me-1">
                                    <use href="{{ asset('svg/bootstrap-icons.svg') }}#phone"></use>
                                </svg>
                                Quokka Mobile
                            </a>
                            @can('tools-viewlatestchanges')
                                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('latest-changes.index') }}">
                                    <svg class="icon-bs icon-16 me-1">
                                        <use href="{{ asset('svg/bootstrap-icons.svg') }}#activity"></use>
                                    </svg>
                                    Letzte Änderungen
                                </a>
                            @endcan
                            @can('tools-viewsentemails')
                                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('sent-emails.index') }}">
                                    <svg class="icon-bs icon-16 me-1">
                                        <use href="{{ asset('svg/bootstrap-icons.svg') }}#envelope"></use>
                                    </svg>
                                    Gesendete Emails
                                </a>
                            @endcan
                            @can('tools-scanqr')
                                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('qr-scan.index') }}">
                                    <svg class="icon-bs icon-16 me-1">
                                        <use href="{{ asset('svg/bootstrap-icons.svg') }}#camera"></use>
                                    </svg>
                                    QR-Code scannen
                                </a>
                            @endcan
                            @can('tools-viewexceptions')
                                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('exceptions.index') }}">
                                    <svg class="icon-bs icon-16 me-1">
                                        <use href="{{ asset('svg/bootstrap-icons.svg') }}#exclamation-triangle"></use>
                                    </svg>
                                    Fehlerdateien
                                </a>
                            @endcan
                        </div>
                    </li>


                    @if(auth()->user()->can('application-settings-update') || auth()->user()->can('viewAny', \App\Models\Employee::class) || auth()->user()->can('viewAny', \Spatie\Permission\Models\Role::class) || auth()->user()->can('viewAny', \App\Models\MaterialService::class) || auth()->user()->can('viewAny', \App\Models\WageService::class) || auth()->user()->can('viewAny', \App\Models\Vehicle::class))
                        <li @class(['nav-item', 'dropdown', 'active' => request()->routeIs('application-settings.*', 'employees.*', 'roles.*', 'wage-services.*', 'material-services.*', 'vehicles.*')])>
                            <a class="nav-link @cannot('application-settings-update') disabled @endcannot d-inline-flex align-items-center pe-0" href="{{ route('application-settings.edit') }}">
                                <svg class="icon-bs icon-20 me-1">
                                    <use href="{{ asset('svg/bootstrap-icons.svg') }}#gear"></use>
                                </svg>
                                Einstellungen
                            </a>
                            @if(auth()->user()->can('viewAny', \App\Models\Employee::class) || auth()->user()->can('viewAny', \Spatie\Permission\Models\Role::class) || auth()->user()->can('viewAny', \App\Models\MaterialService::class) || auth()->user()->can('viewAny', \App\Models\WageService::class) || auth()->user()->can('viewAny', \App\Models\Vehicle::class))
                                <a id="navbarSettingsDropdown" class="nav-link dropdown-toggle d-inline-flex align-items-center ps-0" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="caret h-20"></span>
                                </a>

                                <div class="dropdown-menu" aria-labelledby="navbarSettingsDropdown">
                                    @can('viewAny', \App\Models\Employee::class)
                                        <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('employees.index') }}">
                                            <svg class="icon-bs icon-16 me-1">
                                                <use href="{{ asset('svg/bootstrap-icons.svg') }}#people"></use>
                                            </svg>
                                            Mitarbeiter
                                        </a>
                                    @endcan
                                    @can('viewAny', \Spatie\Permission\Models\Role::class)
                                        <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('roles.index') }}">
                                            <svg class="icon-bs icon-16 me-1">
                                                <use href="{{ asset('svg/bootstrap-icons.svg') }}#key"></use>
                                            </svg>
                                            Rollen
                                        </a>
                                    @endcan
                                    @if(auth()->user()->can('viewAny', \App\Models\MaterialService::class) || auth()->user()->can('viewAny', \App\Models\WageService::class))
                                        <a class="dropdown-item d-inline-flex align-items-center" href="{{ route( auth()->user()->can('viewAny', \App\Models\WageService::class) ? 'wage-services.index' : 'material-services.index') }}">
                                            <svg class="icon-bs icon-16 me-1">
                                                <use href="{{ asset('svg/bootstrap-icons.svg') }}#cpu"></use>
                                            </svg>
                                            Leistungen
                                        </a>
                                    @endif
                                    @can('viewAny', \App\Models\Vehicle::class)
                                        <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('vehicles.index') }}">
                                            <svg class="icon-bs icon-16 me-1">
                                                <use href="{{ asset('svg/bootstrap-icons.svg') }}#truck"></use>
                                            </svg>
                                            Fuhrpark
                                        </a>
                                    @endcan
                                </div>
                            @endif
                        </li>
                    @endif

                    @can('help-view')
                        <li @class(['nav-item', 'dropdown', 'active' => request()->routeIs('help.*', 'changelog.*')])>
                            <a class="nav-link d-inline-flex align-items-center pe-0" href="{{ route('help.index') }}">
                                <svg class="icon-bs icon-20 me-1">
                                    <use href="{{ asset('svg/bootstrap-icons.svg') }}#question-circle"></use>
                                </svg>
                                Hilfe
                            </a>
                            <a id="navbarHelpDropdown" class="nav-link dropdown-toggle d-inline-flex align-items-center ps-0" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="caret h-20"></span>
                            </a>

                            <div class="dropdown-menu" aria-labelledby="navbarHelpDropdown">
                                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('changelog.show') }}">
                                    <svg class="icon-bs icon-16 me-1">
                                        <use href="{{ asset('svg/bootstrap-icons.svg') }}#info-circle"></use>
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
                        <div class="input-group global-search border rounded-1 flex-grow-1 ms-auto">
                                <div class="input-group-text bg-transparent border-0">
                                    <svg class="icon-bs icon-16 text-muted">
                                        <use href="{{ asset('svg/bootstrap-icons.svg') }}#search"></use>
                                    </svg>
                                </div>
                            <form class="d-flex needs-validation global-search-form flex-grow-1" action="{{ route('search.index') }}" method="get" novalidate>
                                <input type="search" name="query" class="form-control global-search-input border-0 outline-none ps-0 flex-grow-1" placeholder="Suche" autocomplete="off" required>
                            </form>
                                <button class="btn btn-outline-secondary border-0 text-gray-500 global-search-append-button" onclick="window.dispatchEvent(new CustomEvent('toggle-spotlight'))">
                                    <span class="lead">
                                        <svg class="icon-bs icon-baseline">
                                            <use href="{{ asset('svg/bootstrap-icons.svg') }}#chevron-up"></use>
                                        </svg>
                                        <span>K</span>
                                    </span>
                                </button>
                        </div>
                    </div>
                @endcan

            @endauth

            <ul class="navbar-nav ms-auto">
                <!-- Authentication Links -->
                @guest
                    <li class="nav-item">
                        <a class="nav-link d-inline-flex align-items-center" href="{{ route('login') }}">
                            <svg class="icon-bs icon-20 me-1">
                                <use href="{{ asset('svg/bootstrap-icons.svg') }}#box-arrow-in-right"></use>
                            </svg>
                            {{ __('Login') }}
                        </a>
                    </li>
                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link d-inline-flex align-items-center" href="{{ route('register') }}">
                                <svg class="icon-bs icon-20 me-1">
                                    <use href="{{ asset('svg/bootstrap-icons.svg') }}#send"></use>
                                </svg>
                                {{ __('Register') }}
                            </a>
                        </li>
                    @endif
                @else
                    <li class="nav-item dropdown">
                        @php $avatarHex = \App\Models\UserSettings::avatarColourFromName(Auth::user()->settings->avatar_colour ?? 'gray')['color']; @endphp
                        <a class="nav-link d-inline-flex align-items-center gap-2 outline-none" id="navbarUserDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            @if(Auth::user()->unreadNotifications()->count())
                                <svg class="icon-bs icon-20 q-nav-bell"><use href="{{ asset('svg/bootstrap-icons.svg') }}#bell"></use></svg>
                            @endif
                            <span class="q-user-avatar @if(Session::has('impersonatorId')) border border-2 border-danger @endif" style="background: color-mix(in srgb, {{ $avatarHex }} 20%, transparent); color: {{ $avatarHex }};">{{ Auth::user()->username_avatar_string }}</span>
                        </a>


                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarUserDropdown">
                            <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('home') }}">
                                <svg class="icon-bs icon-16 me-1">
                                    <use href="{{ asset('svg/bootstrap-icons.svg') }}#activity"></use>
                                </svg>
                                Übersicht
                            </a>
                            <a class="dropdown-item  d-inline-flex align-items-center @if(Auth::user()->unreadNotifications()->count()) dropdown-item-danger @endif" href="{{ route('notifications.index') }}">
                                <svg class="icon-bs icon-16 me-1">
                                    <use href="{{ asset('svg/bootstrap-icons.svg') }}#bell"></use>
                                </svg>
                                Benachrichtigungen
                            </a>
                            @can('viewAny', \App\Models\Note::class)
                                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('notes.index') }}">
                                    <svg class="icon-bs icon-16 me-1">
                                        <use href="{{ asset('svg/bootstrap-icons.svg') }}#book"></use>
                                    </svg>
                                    Notizbuch
                                </a>
                            @endcan
                            <a class="dropdown-item  d-inline-flex align-items-center" href="{{ route('user-settings.edit') }}">
                                <svg class="icon-bs icon-16 me-1">
                                    <use href="{{ asset('svg/bootstrap-icons.svg') }}#gear"></use>
                                </svg>
                                Einstellungen
                            </a>

                            <div class="dropdown-divider"></div>
                            <button type="button" class="dropdown-item q-theme-opt q-theme-opt--system d-inline-flex align-items-center" onclick="setQuokkaTheme('system')">
                                <svg class="icon-bs icon-16 me-1">
                                    <use href="{{ asset('svg/bootstrap-icons.svg') }}#display"></use>
                                </svg>
                                System
                                <svg class="icon-bs icon-16 q-theme-check">
                                    <use href="{{ asset('svg/bootstrap-icons.svg') }}#check"></use>
                                </svg>
                            </button>
                            <button type="button" class="dropdown-item q-theme-opt q-theme-opt--light d-inline-flex align-items-center" onclick="setQuokkaTheme('light')">
                                <svg class="icon-bs icon-16 me-1">
                                    <use href="{{ asset('svg/bootstrap-icons.svg') }}#sun"></use>
                                </svg>
                                Hell
                                <svg class="icon-bs icon-16 q-theme-check">
                                    <use href="{{ asset('svg/bootstrap-icons.svg') }}#check"></use>
                                </svg>
                            </button>
                            <button type="button" class="dropdown-item q-theme-opt q-theme-opt--dark d-inline-flex align-items-center" onclick="setQuokkaTheme('dark')">
                                <svg class="icon-bs icon-16 me-1">
                                    <use href="{{ asset('svg/bootstrap-icons.svg') }}#moon"></use>
                                </svg>
                                Dunkel
                                <svg class="icon-bs icon-16 q-theme-check">
                                    <use href="{{ asset('svg/bootstrap-icons.svg') }}#check"></use>
                                </svg>
                            </button>
                            <div class="dropdown-divider"></div>

                            @if(Session::has('impersonatorId'))
                                @can('impersonate', Auth::user()->employee)
                                    <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('employees.impersonate', Auth::user()->employee) }}">
                                        <svg class="icon-bs icon-16 me-1">
                                            <use href="{{ asset('svg/bootstrap-icons.svg') }}#person-dash"></use>
                                        </svg>
                                        Zurück zum eigenen Benutzer
                                    </a>
                                @endcan
                            @else
                                <a class="dropdown-item  d-inline-flex align-items-center" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    <svg class="icon-bs icon-16 me-1">
                                        <use href="{{ asset('svg/bootstrap-icons.svg') }}#box-arrow-right"></use>
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
