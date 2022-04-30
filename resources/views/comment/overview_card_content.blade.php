<div class="py-2">
    <div class="row">
        <div class="col-auto d-none d-md-block pr-0">
            <div class="avatar bg-{{ $comment->employee->user->settings->avatar_colour }}-200 rounded-circle d-inline-flex align-items-center justify-content-center">
                <h4 class="text-{{ $comment->employee->user->settings->avatar_colour }}-800 m-0">{{ $comment->employee->user->username_avatar_string }}</h4>
            </div>
        </div>
        <div class="col">
            <div class="rounded-top border bg-gray-100 px-2 py-1">
                <div class="row">
                    <div class="col-auto mr-auto">
                        <div class="lead">{{ $comment->employee->person->name }}</div>
                        <p class="text-muted d-inline-flex align-items-center m-0">
                            <svg class="feather feather-16 mr-1">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#calendar"></use>
                            </svg>
                            {{ $comment->created_at->format('d.m.Y, H:i') }}
                            @if($comment->created_at->lt($comment->updated_at))
                                <svg class="feather feather-16 ml-2 mr-1">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#edit-2"></use>
                                </svg>
                                {{ $comment->updated_at->format('d.m.Y, H:i') }}
                            @endif
                        </p>
                    </div>

                    @if(auth()->user()->can('update', $comment) || auth()->user()->can('delete', $comment))
                        <div class="col-auto d-inline-flex align-items-center">
                            <div class="dropdown d-inline">
                                <button class="btn btn-lg btn-link dropdown-toggle-vertical-points text-muted" type="button" id="commentOverviewDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="commentOverviewDropdown">
                                    @can('update', $comment)
                                        <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('comments.edit', $comment) }}">
                                            <svg class="feather feather-16 mr-2">
                                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#edit"></use>
                                            </svg>
                                            Bearbeiten
                                        </a>
                                    @endcan
                                    @can('delete', $comment)
                                        <form action="{{ route('comments.destroy', $comment) }}" method="post">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="dropdown-item dropdown-item-delete d-inline-flex align-items-center">
                                                <svg class="feather feather-16 mr-2">
                                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#trash-2"></use>
                                                </svg>
                                                Entfernen
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            </div>

            <div class="rounded-botom border border-top-0 px-2 py-2">
                <div class="row">
                    <div class="col">
                        {!! Html::fromMarkdown($comment->comment) !!}
                    </div>
                </div>

                @if($comment->attachments()->count() > 0)
                    <div class="row text-muted d-flex align-items-center mt-1">
                        <div class="col">
                            <div class="d-none d-md-inline-flex align-items-center">
                                <svg class="feather feather-16 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#paperclip"></use>
                                </svg>
                                Anhänge
                            </div>
                            <a class="d-inline-flex d-md-none d-inline-flex align-items-center" data-toggle="collapse" href="#collapseCommentAttachments-{{ $comment->id }}" role="button" aria-expanded="false" aria-controls="collapseCommentAttachments-{{ $comment->id }}">
                                <svg class="feather feather-16 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#paperclip"></use>
                                </svg>
                                Anhänge
                            </a>
                        </div>
                    </div>
                    <div class="d-none d-md-block">
                        <div class="row">
                            @foreach($comment->attachments() as $attachment)
                                <div class="col-12 col-md-6 col-lg-3 mt-1">
                                    <div class="attachment bg-gray-100 border d-inline-flex align-items-center position-relative w-100 h-100 p-1">
                                        @if($attachment->hasGeneratedConversion('thumbnail'))
                                            <img class="attachment-img-preview mr-2" src="{{ $attachment->getUrl('thumbnail') }}" alt="{{ $attachment->file_name }}" />
                                        @else
                                            <svg class="feather attachment-img-preview mr-2">
                                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#file-text"></use>
                                            </svg>
                                        @endif
                                        <div class="min-w-0">
                                            <div class="min-w-0 text-truncate">{{ $attachment->file_name }}</div>
                                            <div class="text-muted">{{ $attachment->human_readable_size }}</div>
                                        </div>
                                        <a href="{{ $attachment->getUrl() }}" class="stretched-link outline-none"></a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="collapse d-md-none" id="collapseCommentAttachments-{{ $comment->id }}">
                        <div class="row">
                            @foreach($comment->attachments() as $attachment)
                                <div class="col-12 col-md-6 col-lg-3 mt-1">
                                    <div class="attachment bg-gray-100 border d-inline-flex align-items-center position-relative w-100 h-100 p-1">
                                        @if($attachment->hasGeneratedConversion('thumbnail'))
                                            <img class="attachment-img-preview mr-2" src="{{ $attachment->getUrl('thumbnail') }}" alt="{{ $attachment->file_name }}" />
                                        @else
                                            <svg class="feather attachment-img-preview mr-2">
                                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#file-text"></use>
                                            </svg>
                                        @endif
                                        <div class="min-w-0">
                                            <div class="min-w-0 text-truncate">{{ $attachment->file_name }}</div>
                                            <div class="text-muted">{{ $attachment->human_readable_size }}</div>
                                        </div>
                                        <a href="{{ $attachment->getUrl() }}" class="stretched-link outline-none"></a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

</div>
