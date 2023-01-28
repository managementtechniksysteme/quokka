@include('latex.partials.preamble')

@include('latex.partials.header')

\fancyfoot[L]{\footnotesize{Notizbuch von {!! Latex::escape(Auth::user()->employee->person->name) !!}, erstellt am {!! Latex::escape(\Carbon\Carbon::today()) !!}}}
\fancyfoot[R]{\footnotesize{Seite \thepage\ von \pageref{LastPage}}}

\begin{document}
\begin{minipage}{0.07\textwidth}
\qrcode[height=1cm]{ {!! Latex::escape(route('notes.index')) !!} }
\end{minipage}
\begin{minipage}{0.93\textwidth}
\large{\textbf{Notizbuch, erstellt am {!! Latex::escape(\Carbon\Carbon::today()) !!}}} \\ \large{\textbf{{!! Latex::escape(Auth::user()->employee->person->name) !!}}}
\end{minipage}
\\\\\\
@if(count($notes) > 0)
@foreach($notes as $note)
\section{{!! Latex::escape($note->created_at->format('d.m.Y, H:i')) !!}}
@if($note->title)
\subsection{Titel: {!! Latex::escape($note->title) !!}}
@endif
\subsection{Notiz}
\footnotesize{{!! Latex::fromMarkdown($note->comment) !!}}
@endforeach
@else
Es sind keine Notizen vorhanden.
@endif
\end{document}
