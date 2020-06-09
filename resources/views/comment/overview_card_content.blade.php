<div class="overview-card rounded p-1 mt-2">
    <div class="row">
        <div class="col-auto">
            <div class="avatar bg-primary rounded-circle d-inline-flex align-items-center justify-content-center">
                <h4 class="text-white m-0">{{ $comment->employee->user->username_avatar_string }}</h4>
            </div>
        </div>
        <div class="col">
            <div class="row">
                <div class="col-auto mr-auto">
                    <h5>{{ $comment->employee->person->name }}</h5>
                    <p class="text-muted d-inline-flex align-items-center m-0">
                        <svg class="feather feather-16 mr-1">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#calendar"></use>
                        </svg>
                        {{ $comment->created_at->format('d.m.Y, H:i') }}
                    </p>
                </div>

                <div class="col-auto">
                    <div class="dropdown d-inline">
                        <button class="btn btn-lg btn-link dropdown-toggle-vertical-points text-muted" type="button" id="companyOverviewDropdown" data-toggle="dropdown"></button>

                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('comments.edit', $comment) }}">
                                <svg class="feather feather-16 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#edit"></use>
                                </svg>
                                Kommentar bearbeiten
                            </a>

                            <form action="{{ route('comments.destroy', $comment) }}" method="post">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="dropdown-item dropdown-item-delete d-inline-flex align-items-center">
                                    <svg class="feather feather-16 mr-2">
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#trash-2"></use>
                                    </svg>
                                    Kommentar entfernen
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
            <div class="row d-none d-md-block mt-2">
                <div class="col">
                    @markdown ($comment->comment)
                </div>
            </div>
        </div>
    </div>
    <div class="row d-md-none mt-2">
        <div class="col">
            @markdown ($comment->comment)
        </div>
    </div>
</div>
