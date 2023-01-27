@include('latex.partials.preamble')

@include('latex.partials.header')

\fancyfoot[L]{\footnotesize{Notiz vom {!! Latex::escape($note->created_at->format('d.m.Y, H:i')) !!}, erstellt am {!! Latex::escape(\Carbon\Carbon::today()) !!}}}
\fancyfoot[R]{\footnotesize{Seite \thepage\ von \pageref{LastPage}}}

\begin{document}
\begin{minipage}{0.07\textwidth}
\qrcode[height=1cm]{ {!! Latex::escape(route('notes.show', $note)) !!} }
\end{minipage}
\begin{minipage}{0.93\textwidth}
\large{\textbf{Notiz vom {!! Latex::escape($note->created_at->format('d.m.Y, H:i')) !!}}}
\end{minipage}
\\\\\\
@if($note->title)
\section{Titel: {!! Latex::escape($note->title) !!}}
@endif
\section{Notiz}
\footnotesize{{!! Latex::fromMarkdown($task->comment) !!}}
\end{document}
