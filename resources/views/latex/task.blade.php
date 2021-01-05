@include('latex.partials.preamble')

@include('latex.partials.header')

\fancyfoot[L]{\footnotesize{Aufgabe {!! Latex::escape($task->name) !!} ({!! Latex::escape($task->project->name) !!}), erstellt am {!! Latex::escape(\Carbon\Carbon::today()) !!}}}
\fancyfoot[R]{\footnotesize{Seite \thepage\ von \pageref{LastPage}}}

\begin{document}
\begin{minipage}{0.07\textwidth}
\qrcode[height=1cm]{ {!! Latex::escape(route('tasks.show', $task)) !!} }
\end{minipage}
\begin{minipage}{0.93\textwidth}
\large{\textbf{Aufgabe {!! Latex::escape($task->name) !!}}} \\ \large{\textbf{ {!! Latex::escape($task->project->name) !!}}}
\end{minipage}
\\\\\\
\begin{tabular}{@{}lp{13.5cm}@{}}
@if($task->starts_on || $task->ends_on)
\footnotesize{\textbf{Zeitraum:}} & \footnotesize{{!! $task->starts_on ? Latex::escape($task->starts_on) : 'nicht angegeben' !!}}@if($task->ends_on)\footnotesize{ - {!! Latex::escape($task->ends_on) !!}} @endif \\
@endif
@if($task->due_on)
\footnotesize{\textbf{Fälligkeitsdatum:}} & \footnotesize{{!! Latex::escape($task->due_on) !!}} \\
@endif
\footnotesize{\textbf{Priorität:}} & \footnotesize{{!! Latex::escape(trans($task->priority)) !!}} \\
\footnotesize{\textbf{Status:}} & \footnotesize{{!! Latex::escape(trans($task->status)) !!}} \\
\footnotesize{\textbf{Verrechnugnstatus:}} & \footnotesize{{!! Latex::escape(trans($task->billed_string)) !!}} \\
\footnotesize{\textbf{Verantwortlich:}} & \footnotesize{{!! Latex::escape($task->responsibleEmployee->person->name) !!}} \\
@if($task->involvedEmployees->count() > 0)
\footnotesize{\textbf{Beteiligte:}} &
@foreach($task->involvedEmployees as $employee)
\footnotesize{{!! Latex::escape($employee->person->name) !!}}@unless($loop->last)\footnotesize{, }@endunless
@endforeach
\\
@endif
\end{tabular}
@if($task->comment)
\section{Bemerkungen}
\footnotesize{{!! Latex::fromMarkdown($task->comment) !!}}
@endif
@if($task->comments->count() > 0)
\section{Kommentare}
@foreach($task->comments as $comment)
\subsection{\footnotesize{{!! Latex::escape($comment->employee->person->name) !!}} am {!! Latex::escape($comment->created_at) !!}}
\footnotesize{{!! Latex::fromMarkdown($comment->comment) !!}}
@endforeach
@endif
\end{document}
