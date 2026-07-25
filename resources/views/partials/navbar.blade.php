{{-- d-none d-md-flex unconditional, not @auth-gated (2026-07-24 fix) — this
     desktop bar and the mobile .q-appbar/.q-tabbar chrome below are meant to
     be mutually exclusive at every breakpoint ("only one shows at a time",
     see the .q-appbar SCSS comment), but the toggle used to live inside
     @auth while the mobile chrome's own header was ALSO entirely @auth-gated
     — so a guest on a narrow viewport (login, OTP, password reset) got
     neither: no d-none/d-md-flex to hide this bar, and no mobile app bar to
     replace it with, just the plain desktop navbar at full width acting as
     a mobile top bar (2026-07-24, user: "auth sites... normal bootstrap bar
     design is used"). --}}
<nav class="navbar navbar-expand-xl fixed-top q-topbar d-none d-md-flex">
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

        {{-- Guests only ever have "Anmelden" behind this toggle (register is
             disabled — routes/web.php `Auth::routes(['register' => false])`),
             and it's always a self-referential dead end on the auth pages
             themselves (login/otp/password-reset) — gate the toggle to
             @auth entirely rather than reveal an empty/pointless menu
             (2026-07-21 user report: "empty kebab" on the login page). --}}
        @auth
            <button class="p-2 bg-transparent border rounded-1 outline-none d-inline-flex d-xl-none position-relative" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                <svg class="icon-bs icon-24 align-self-center">
                    <use href="{{ asset('svg/bootstrap-icons.svg') }}#list"></use>
                </svg>
                @if(Auth::user()->unreadNotifications()->count())
                    <span class="notification-badge"></span>
                @endif
            </button>
        @endauth

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


                    <li @class(['nav-item', 'dropdown', 'active' => request()->routeIs('latest-changes.*', 'sent-emails.*', 'qr-scan.*', 'exceptions.*')])>
                        <a id="navbarHelpDropdown" class="nav-link dropdown-toggle d-inline-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <svg class="icon-bs icon-20 me-1">
                                <use href="{{ asset('svg/bootstrap-icons.svg') }}#tools"></use>
                            </svg>
                            Tools
                        </a>

                        <div class="dropdown-menu" aria-labelledby="navbarHelpDropdown">
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

        {{-- Guest branch removed (2026-07-21) — was just a redundant "Anmelden"
             link, only ever reachable via the toggle above, which is now
             @auth-only too. See that comment for the full reasoning. --}}
        @auth
            <ul class="navbar-nav ms-auto">
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
            </ul>
        @endauth

        </div>
    </div>
</nav>

{{-- ========================================================================
     Mobile chrome (< md): app bar + bottom tab bar + "Mehr" sheet, replacing
     the desktop navbar above. See Quokka Mobile.dc.html / QUOKKA-MOBILE-VUE.md
     in the Claude Design project for the reference. Search is a direct inline
     input here (2026-07-20 decision), not the spotlight command palette —
     spotlight stays desktop-only (⌘K).

     The app bar itself (badge + title) is NOT @auth-gated (2026-07-24 fix) —
     guests (login/OTP/password-reset) get the same translucent bar, just
     without the search/bell controls, which need an authenticated user and
     stay wrapped in their own @auth below. The tab bar + Mehr sheet further
     down stay fully @auth-only: a guest has nothing to navigate to.
======================================================================== --}}
    {{-- Coarse section label for the app bar title (2026-07-20) — NOT a
         per-page exact title (a company's own name, a task's own name, etc.):
         that needs a convention threaded through every view/controller and
         is real detail work, deferred until the detail-page app bar itself
         is redesigned (Design's mockup shows detail pages replacing this
         whole bar with a back-button + record name + kebab, which would make
         the persistent search/bell inaccessible there — a decision for when
         we get to detail pages, not now). This is shell-only: route-name
         pattern matching, same technique as the tab-bar active states below,
         covering the same groups the Mehr sheet already labels. Falls back
         to the brand name for anything unmapped (today's behaviour, so this
         is a strict improvement with no regression). --}}
    @php
        // Icons reuse the exact glyphs already used for this section in the
        // tab bar / Mehr sheet below — one icon per section, never redefined.
        $mobilePageLabels = [
            'home' => ['Übersicht', null],
            // Split out from a single 'companies.*|people.*|addresses.*' =>
            // Firmen entry (2026-07-21, user: "the section headers are
            // wrong on both" — people/addresses inherited the Firmen tab's
            // own coarse label, never caught before since only company
            // itself got tested during Phase 1). Icons reused verbatim
            // from the Mehr sheet's own Personen/Adressen rows.
            'companies.*' => ['Firmen', 'briefcase'],
            'people.*' => ['Personen', 'people'],
            'addresses.*' => ['Adressen', 'geo-alt'],
            'projects.*' => ['Projekte', 'clipboard'],
            // Own entry, not lumped into 'projects.*' above (2026-07-22,
            // user: page still showed "Projekte" in the app bar) — icon
            // matches the page's own desktop header (bar-chart).
            'project-controlling.*' => ['Projektcontrolling', 'bar-chart'],
            'tasks.*' => ['Aufgaben', 'check2-square'],
            'memos.*' => ['Aktenvermerke', 'voicemail'],
            'service-reports.*' => ['Serviceberichte', 'gear'],
            'additions-reports.*' => ['Regieberichte', 'tools'],
            'inspection-reports.*' => ['Prüfberichte', 'patch-check'],
            'flow-meter-inspection-reports.*' => ['Prüfberichte für DM', 'patch-check'],
            'construction-reports.*' => ['Bautagesberichte', 'hammer'],
            'delivery-notes.*' => ['Lieferscheine', 'box-seam'],
            'accounting.*' => ['Abrechnung', 'clock'],
            'logbook.*' => ['Fahrtenbuch', 'journal'],
            // Split the same way people/addresses were split off Firmen
            // (2026-07-21, user: "Finanzgruppen should get its own title as
            // well, not Finanzen") — labels/icons reused verbatim from the
            // Mehr sheet's own rows for this section.
            'finances.*' => ['Finanzübersicht', 'currency-euro'],
            'project-finances.*' => ['Projektübersicht', 'clipboard'],
            'finance-groups.*' => ['Manuelle Einträge', 'list'],
            'notifications.*' => ['Benachrichtigungen', 'bell'],
            'notes.*' => ['Notizbuch', 'book'],
            'user-settings.*|application-settings.*' => ['Einstellungen', 'gear'],
            'employees.*' => ['Mitarbeiter', 'people'],
            'roles.*' => ['Rollen', 'key'],
            'material-services.*|wage-services.*' => ['Leistungen', 'cpu'],
            'vehicles.*' => ['Fuhrpark', 'truck'],
            'latest-changes.*' => ['Letzte Änderungen', 'activity'],
            'sent-emails.*' => ['Gesendete Emails', 'envelope'],
            'qr-scan.*' => ['QR-Code scannen', 'camera'],
            'exceptions.*' => ['Fehlerdateien', 'exclamation-triangle'],
            'reauthenticate' => ['Sicherheitsprüfung', 'key'],
            'help.*' => ['Hilfe', 'question-circle'],
            'changelog.*' => ['Versionshinweise', 'info-circle'],
            'search.*' => ['Suche', 'search'],
            // No entries for login/otp/password.* (2026-07-24) — a guest
            // hasn't navigated anywhere yet, so there's no "section" to
            // label the way there is for an authenticated route; falls
            // through to the brand badge/name below, same as the dashboard's
            // own fallback for any unmapped route (2026-07-24, user: "more
            // prominent" than a contextual icon+label restating the button
            // right below it).
        ];
        $mobilePageTitle = config('app.name');
        $mobilePageIcon = null;
        foreach ($mobilePageLabels as $patterns => $entry) {
            if (request()->routeIs(...explode('|', $patterns))) {
                [$mobilePageTitle, $mobilePageIcon] = $entry;
                break;
            }
        }
        // Badge always shows now (accent square before the title) — the
        // section's own icon (reusing the tab bar/Mehr sheet's own glyph),
        // or the "Q" brand letter as the fallback for the dashboard and any
        // unmapped route. Reintroduced 2026-07-20 after first dropping it
        // everywhere but the dashboard: once the redundant page-content icon
        // was ALSO removed (see company/index.blade.php), the content header
        // read too bare without an icon anywhere near the top — this fills
        // that gap without recreating the earlier 3-copies-of-one-icon
        // redundancy (tab bar + app bar now, content header no longer shows it).
    @endphp

    {{-- Bell lives OUTSIDE .q-appbar__row (stays put, always visible) so
         .q-appbar__search can be absolutely positioned anchored exactly at
         the search icon's own slot (immediately left of the bell) and grow
         leftward from there — see the SCSS: this also keeps it out of the
         parent's flex layout entirely, so its collapsed 0-ish width never
         reserves a phantom flex `gap`. --}}
    <header class="q-appbar d-md-none" id="mobileAppbar">
        {{-- Detail pages (show.blade.php) push their own back+name+kebab
             here instead of the standard badge/title/search/bell — per the
             Claude Design mobile mockup's "Detail" frame (2026-07-21): a
             record's own page replaces search/bell with navigation back to
             its list and its own actions, same as every other mobile app
             uses a back button + record name + kebab for a detail screen. --}}
        @hasSection('mobile-detail-bar')
            <div class="q-appbar__row">
                @yield('mobile-detail-bar')
            </div>
        @else
            <div class="q-appbar__row">
                <span class="q-appbar__badge @if($mobilePageIcon) q-appbar__badge--tint @endif">
                    @if($mobilePageIcon)
                        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#{{ $mobilePageIcon }}"></use></svg>
                    @else
                        {{-- Dashboard, or any unmapped route — same brand-letter
                             fallback as $mobilePageTitle falling back to the app
                             name, so the badge is never left empty. --}}
                        {{ Str::substr(config('app.name'), 0, 1) }}
                    @endif
                </span>
                <span class="q-appbar__title">{{ $mobilePageTitle }}</span>

                @can('search')
                    <button type="button" class="q-appbar__btn" aria-label="Suche"
                            onclick="document.getElementById('mobileAppbar').classList.add('is-searching'); document.getElementById('mobileAppbarSearchInput').focus();">
                        <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#search"></use></svg>
                    </button>
                @endcan
            </div>

            @can('search')
                {{-- type="text", not "search" — a native type="search" input adds its
                     own browser clear-icon, which doubled up with our cancel button. --}}
                <form class="q-appbar__search" action="{{ route('search.index') }}" method="get">
                    <svg class="icon-bs icon-16 text-muted flex-shrink-0"><use href="{{ asset('svg/bootstrap-icons.svg') }}#search"></use></svg>
                    <input type="text" name="query" id="mobileAppbarSearchInput" class="q-appbar__search-input"
                           placeholder="Suche" autocomplete="off">
                    <button type="button" class="q-appbar__search-clear" aria-label="Abbrechen"
                            onclick="document.getElementById('mobileAppbar').classList.remove('is-searching'); document.getElementById('mobileAppbarSearchInput').value = '';">
                        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#x-lg"></use></svg>
                    </button>
                </form>
            @endcan

            {{-- No separate unread dot — the bell colour alone signals unread,
                 same principle as the Mehr sheet's Benachrichtigungen row below.
                 @auth-guarded (not just @can('search') above): Auth::user() is
                 null for a guest and unreadNotifications() would throw, whereas
                 the two @can('search') blocks already resolve to false safely
                 with no authenticated user. --}}
            @auth
                <a href="{{ route('notifications.index') }}" class="q-appbar__btn" aria-label="Benachrichtigungen">
                    <svg class="icon-bs icon-20 @if(Auth::user()->unreadNotifications()->count()) q-appbar__btn--unread @endif">
                        <use href="{{ asset('svg/bootstrap-icons.svg') }}#bell"></use>
                    </svg>
                </a>
            @endauth
        @endif
    </header>

    @auth
    {{-- Detail pages' own action sheets (see mobile-detail-bar above) render
         here, NOT inside .q-appbar — .q-appbar is `position:fixed` with its
         own z-index (1020), which caps any descendant's stacking context
         below it, so a sheet nested inside it renders BEHIND .q-tabbar
         (z-index 1030) and its lowest rows end up hidden under the tab bar
         (2026-07-21, user: "the remove button hidden by the nav bar"). Same
         fix as the Mehr sheet below already has by virtue of sitting at this
         same top level, not nested in the header. --}}
    @yield('mobile-detail-sheets')

    @php
        $isStartTabActive    = request()->routeIs('home');
        $isFirmenTabActive   = request()->routeIs('companies.*', 'people.*', 'addresses.*');
        $isProjekteTabActive = request()->routeIs('projects.*', 'project-controlling.*');
        $isAufgabenTabActive = request()->routeIs('tasks.*', 'memos.*', 'service-reports.*', 'additions-reports.*', 'inspection-reports.*', 'flow-meter-inspection-reports.*', 'construction-reports.*', 'delivery-notes.*');
        $isMehrTabActive     = !$isStartTabActive && !$isFirmenTabActive && !$isProjekteTabActive && !$isAufgabenTabActive;
    @endphp

    <nav class="q-tabbar d-md-none" id="mobileTabbar">
        <a href="{{ route('home') }}" class="q-tabbar__item @if($isStartTabActive) active @endif">
            <svg class="icon-bs"><use href="{{ asset('svg/bootstrap-icons.svg') }}#activity"></use></svg>
            Übersicht
        </a>

        @if(auth()->user()->can('viewAny', \App\Models\Company::class) || auth()->user()->can('viewAny', \App\Models\Person::class) || auth()->user()->can('viewAny', \App\Models\Address::class))
            <a href="{{ route('companies.index') }}" class="q-tabbar__item @if($isFirmenTabActive) active @endif">
                <svg class="icon-bs"><use href="{{ asset('svg/bootstrap-icons.svg') }}#briefcase"></use></svg>
                Firmen
            </a>
        @endif

        @if(auth()->user()->can('viewAny', \App\Models\Project::class) || auth()->user()->can('finances-view'))
            <a href="{{ route('projects.index') }}" class="q-tabbar__item @if($isProjekteTabActive) active @endif">
                <svg class="icon-bs"><use href="{{ asset('svg/bootstrap-icons.svg') }}#clipboard"></use></svg>
                Projekte
            </a>
        @endif

        @if(auth()->user()->can('viewAny', \App\Models\Task::class) || auth()->user()->can('viewAny', \App\Models\Memo::class) || auth()->user()->can('viewAny', \App\Models\ServiceReport::class) || auth()->user()->can('viewAny', \App\Models\AdditionsReport::class) || auth()->user()->can('viewAny', \App\Models\InspectionReport::class) || auth()->user()->can('viewAny', \App\Models\FlowMeterInspectionReport::class) || auth()->user()->can('viewAny', \App\Models\ConstructionReport::class) || auth()->user()->can('viewAny', \App\Models\DeliveryNote::class))
            <a href="{{ route('tasks.index') }}" class="q-tabbar__item @if($isAufgabenTabActive) active @endif">
                <svg class="icon-bs"><use href="{{ asset('svg/bootstrap-icons.svg') }}#check2-square"></use></svg>
                Aufgaben
            </a>
        @endif

        <button type="button" class="q-tabbar__item @if($isMehrTabActive) active @endif"
                data-bs-toggle="offcanvas" data-bs-target="#mehrSheet" aria-controls="mehrSheet">
            <svg class="icon-bs"><use href="{{ asset('svg/bootstrap-icons.svg') }}#three-dots"></use></svg>
            Mehr
        </button>
    </nav>

    {{-- "Mehr" sheet — a Bootstrap offcanvas (backdrop/ESC/focus-trap all its
         own JS; tapping the backdrop or Escape dismisses it — no header row
         with a title/close button, matching Quokka Mobile.dc.html's own
         frame 9, which is just a drag-handle bar (.q-sheet::before) straight
         into the rows). aria-label replaces the header's aria-labelledby
         since there's no visible title element to point at. Rows reuse the
         .q-row + .q-avatar list-row pattern (icon tile + bold label), grouped
         under the same labels as their desktop parent nav items. Icon tile
         colour: accent = content/navigation, muted = settings & system
         actions — matches the mockup. --}}
    <div class="offcanvas offcanvas-bottom q-sheet" tabindex="-1" id="mehrSheet" aria-label="Mehr">
        <div class="q-sheet__handle" aria-hidden="true"><span class="q-sheet__handle-bar"></span></div>
        <div class="offcanvas-body">

            @if(auth()->user()->can('viewAny', \App\Models\Person::class) || auth()->user()->can('viewAny', \App\Models\Address::class))
                <div class="q-sheet__label">Firmen</div>
                @can('viewAny', \App\Models\Person::class)
                    <a class="q-row" href="{{ route('people.index') }}">
                        <span class="q-avatar"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#people"></use></svg></span>
                        <span class="q-row__title">Personen</span>
                    </a>
                @endcan
                @can('viewAny', \App\Models\Address::class)
                    <a class="q-row" href="{{ route('addresses.index') }}">
                        <span class="q-avatar"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#geo-alt"></use></svg></span>
                        <span class="q-row__title">Adressen</span>
                    </a>
                @endcan
            @endif

            @can('finances-view')
                <div class="q-sheet__label">Projekte</div>
                <a class="q-row" href="{{ route('project-controlling.index') }}">
                    <span class="q-avatar"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#bar-chart"></use></svg></span>
                    <span class="q-row__title">Controlling</span>
                </a>
            @endcan

            @if(auth()->user()->can('viewAny', \App\Models\Memo::class) || auth()->user()->can('viewAny', \App\Models\ServiceReport::class) || auth()->user()->can('viewAny', \App\Models\AdditionsReport::class) || auth()->user()->can('viewAny', \App\Models\InspectionReport::class) || auth()->user()->can('viewAny', \App\Models\FlowMeterInspectionReport::class) || auth()->user()->can('viewAny', \App\Models\ConstructionReport::class) || auth()->user()->can('viewAny', \App\Models\DeliveryNote::class))
                <div class="q-sheet__label">Berichte</div>
                @can('viewAny', \App\Models\Memo::class)
                    <a class="q-row" href="{{ route('memos.index') }}">
                        <span class="q-avatar"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#voicemail"></use></svg></span>
                        <span class="q-row__title">Aktenvermerke</span>
                    </a>
                @endcan
                @can('viewAny', \App\Models\ServiceReport::class)
                    <a class="q-row" href="{{ route('service-reports.index') }}">
                        <span class="q-avatar"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#gear"></use></svg></span>
                        <span class="q-row__title">Serviceberichte</span>
                    </a>
                @endcan
                @can('viewAny', \App\Models\AdditionsReport::class)
                    <a class="q-row" href="{{ route('additions-reports.index') }}">
                        <span class="q-avatar"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#tools"></use></svg></span>
                        <span class="q-row__title">Regieberichte</span>
                    </a>
                @endcan
                @can('viewAny', \App\Models\InspectionReport::class)
                    <a class="q-row" href="{{ route('inspection-reports.index') }}">
                        <span class="q-avatar"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#patch-check"></use></svg></span>
                        <span class="q-row__title">Prüfberichte</span>
                    </a>
                @endcan
                @can('viewAny', \App\Models\FlowMeterInspectionReport::class)
                    <a class="q-row" href="{{ route('flow-meter-inspection-reports.index') }}">
                        <span class="q-avatar"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#patch-check"></use></svg></span>
                        <span class="q-row__title">Prüfberichte für DM</span>
                    </a>
                @endcan
                @can('viewAny', \App\Models\ConstructionReport::class)
                    <a class="q-row" href="{{ route('construction-reports.index') }}">
                        <span class="q-avatar"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#hammer"></use></svg></span>
                        <span class="q-row__title">Bautagesberichte</span>
                    </a>
                @endcan
                @can('viewAny', \App\Models\DeliveryNote::class)
                    <a class="q-row" href="{{ route('delivery-notes.index') }}">
                        <span class="q-avatar"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#box-seam"></use></svg></span>
                        <span class="q-row__title">Lieferscheine</span>
                    </a>
                @endcan
            @endif

            @if(auth()->user()->can('viewAny', \App\Models\Accounting::class) || auth()->user()->can('viewAny', \App\Models\Logbook::class))
                <div class="q-sheet__label">Abrechnung</div>
                @can('viewAny', \App\Models\Accounting::class)
                    <a class="q-row" href="{{ route('accounting.index') }}">
                        <span class="q-avatar"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#clock"></use></svg></span>
                        <span class="q-row__title">Leistungsabrechnung</span>
                    </a>
                @endcan
                @can('viewAny', \App\Models\Logbook::class)
                    <a class="q-row" href="{{ route('logbook.index') }}">
                        <span class="q-avatar"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#journal"></use></svg></span>
                        <span class="q-row__title">Fahrtenbuch</span>
                    </a>
                @endcan
            @endif

            @if(auth()->user()->can('finances-view') || auth()->user()->can('viewAny', \App\Models\FinanceGroup::class))
                <div class="q-sheet__label">Finanzen</div>
                @can('finances-view')
                    <a class="q-row" href="{{ route('finances.index') }}">
                        <span class="q-avatar"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#currency-euro"></use></svg></span>
                        <span class="q-row__title">Finanzen</span>
                    </a>
                    <a class="q-row" href="{{ route('project-finances.index') }}">
                        <span class="q-avatar"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#clipboard"></use></svg></span>
                        <span class="q-row__title">Projektübersicht</span>
                    </a>
                @endcan
                @can('viewAny', \App\Models\FinanceGroup::class)
                    <a class="q-row" href="{{ route('finance-groups.index') }}">
                        <span class="q-avatar"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#list"></use></svg></span>
                        <span class="q-row__title">Manuelle Einträge</span>
                    </a>
                @endcan
            @endif

            @if(auth()->user()->can('tools-viewlatestchanges') || auth()->user()->can('tools-viewsentemails') || auth()->user()->can('tools-scanqr') || auth()->user()->can('tools-viewexceptions'))
                <div class="q-sheet__label">Werkzeuge</div>
                @can('tools-viewlatestchanges')
                    <a class="q-row" href="{{ route('latest-changes.index') }}">
                        <span class="q-avatar q-avatar--muted"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#activity"></use></svg></span>
                        <span class="q-row__title">Letzte Änderungen</span>
                    </a>
                @endcan
                @can('tools-viewsentemails')
                    <a class="q-row" href="{{ route('sent-emails.index') }}">
                        <span class="q-avatar q-avatar--muted"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#envelope"></use></svg></span>
                        <span class="q-row__title">Gesendete Emails</span>
                    </a>
                @endcan
                @can('tools-scanqr')
                    <a class="q-row" href="{{ route('qr-scan.index') }}">
                        <span class="q-avatar q-avatar--muted"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#camera"></use></svg></span>
                        <span class="q-row__title">QR-Code scannen</span>
                    </a>
                @endcan
                @can('tools-viewexceptions')
                    <a class="q-row" href="{{ route('exceptions.index') }}">
                        <span class="q-avatar q-avatar--muted"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#exclamation-triangle"></use></svg></span>
                        <span class="q-row__title">Fehlerdateien</span>
                    </a>
                @endcan
            @endif

            @if(auth()->user()->can('application-settings-update') || auth()->user()->can('viewAny', \App\Models\Employee::class) || auth()->user()->can('viewAny', \Spatie\Permission\Models\Role::class) || auth()->user()->can('viewAny', \App\Models\MaterialService::class) || auth()->user()->can('viewAny', \App\Models\WageService::class) || auth()->user()->can('viewAny', \App\Models\Vehicle::class))
                <div class="q-sheet__label">Einstellungen</div>
                @can('application-settings-update')
                    <a class="q-row" href="{{ route('application-settings.edit') }}">
                        <span class="q-avatar q-avatar--muted"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#gear"></use></svg></span>
                        <span class="q-row__title">Einstellungen</span>
                    </a>
                @endcan
                @can('viewAny', \App\Models\Employee::class)
                    <a class="q-row" href="{{ route('employees.index') }}">
                        <span class="q-avatar q-avatar--muted"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#people"></use></svg></span>
                        <span class="q-row__title">Mitarbeiter</span>
                    </a>
                @endcan
                @can('viewAny', \Spatie\Permission\Models\Role::class)
                    <a class="q-row" href="{{ route('roles.index') }}">
                        <span class="q-avatar q-avatar--muted"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#key"></use></svg></span>
                        <span class="q-row__title">Rollen</span>
                    </a>
                @endcan
                @if(auth()->user()->can('viewAny', \App\Models\MaterialService::class) || auth()->user()->can('viewAny', \App\Models\WageService::class))
                    <a class="q-row" href="{{ route(auth()->user()->can('viewAny', \App\Models\WageService::class) ? 'wage-services.index' : 'material-services.index') }}">
                        <span class="q-avatar q-avatar--muted"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#cpu"></use></svg></span>
                        <span class="q-row__title">Leistungen</span>
                    </a>
                @endif
                @can('viewAny', \App\Models\Vehicle::class)
                    <a class="q-row" href="{{ route('vehicles.index') }}">
                        <span class="q-avatar q-avatar--muted"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#truck"></use></svg></span>
                        <span class="q-row__title">Fuhrpark</span>
                    </a>
                @endcan
            @endif

            @can('help-view')
                <div class="q-sheet__label">Hilfe</div>
                <a class="q-row" href="{{ route('help.index') }}">
                    <span class="q-avatar q-avatar--muted"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#question-circle"></use></svg></span>
                    <span class="q-row__title">Hilfe</span>
                </a>
                <a class="q-row" href="{{ route('changelog.show') }}">
                    <span class="q-avatar q-avatar--muted"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#info-circle"></use></svg></span>
                    <span class="q-row__title">{{ config('app.name') }} @version('compact')</span>
                </a>
            @endcan

            <div class="q-sheet__label">Konto</div>
            {{-- Unread state shown by tinting the icon tile red — no separate
                 dot, same principle as the app bar bell above. --}}
            <a class="q-row" href="{{ route('notifications.index') }}">
                <span class="q-avatar @if(Auth::user()->unreadNotifications()->count()) q-avatar--red @endif">
                    <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#bell"></use></svg>
                </span>
                <span class="q-row__title">Benachrichtigungen</span>
            </a>
            @can('viewAny', \App\Models\Note::class)
                <a class="q-row" href="{{ route('notes.index') }}">
                    <span class="q-avatar"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#book"></use></svg></span>
                    <span class="q-row__title">Notizbuch</span>
                </a>
            @endcan
            <a class="q-row" href="{{ route('user-settings.edit') }}">
                <span class="q-avatar q-avatar--muted"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#gear"></use></svg></span>
                <span class="q-row__title">Einstellungen</span>
            </a>

            <div class="q-sheet__divider"></div>
            <button type="button" class="q-row q-theme-opt q-theme-opt--system" onclick="setQuokkaTheme('system')">
                <span class="q-avatar q-avatar--muted"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#display"></use></svg></span>
                <span class="q-row__title">System</span>
                <svg class="icon-bs icon-18 q-theme-check ms-auto"><use href="{{ asset('svg/bootstrap-icons.svg') }}#check"></use></svg>
            </button>
            <button type="button" class="q-row q-theme-opt q-theme-opt--light" onclick="setQuokkaTheme('light')">
                <span class="q-avatar q-avatar--muted"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#sun"></use></svg></span>
                <span class="q-row__title">Hell</span>
                <svg class="icon-bs icon-18 q-theme-check ms-auto"><use href="{{ asset('svg/bootstrap-icons.svg') }}#check"></use></svg>
            </button>
            <button type="button" class="q-row q-theme-opt q-theme-opt--dark" onclick="setQuokkaTheme('dark')">
                <span class="q-avatar q-avatar--muted"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#moon"></use></svg></span>
                <span class="q-row__title">Dunkel</span>
                <svg class="icon-bs icon-18 q-theme-check ms-auto"><use href="{{ asset('svg/bootstrap-icons.svg') }}#check"></use></svg>
            </button>
            <div class="q-sheet__divider"></div>

            @if(Session::has('impersonatorId'))
                @can('impersonate', Auth::user()->employee)
                    <a class="q-row" href="{{ route('employees.impersonate', Auth::user()->employee) }}">
                        <span class="q-avatar q-avatar--muted"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#person-dash"></use></svg></span>
                        <span class="q-row__title">Zurück zum eigenen Benutzer</span>
                    </a>
                @endcan
            @else
                <a class="q-row" href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();">
                    <span class="q-avatar q-avatar--muted"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#box-arrow-right"></use></svg></span>
                    <span class="q-row__title">{{ __('Logout') }}</span>
                </a>
                <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            @endif
        </div>
    </div>
@endauth
