@include('latex.partials.preamble')

@include('latex.partials.header')

\fancyfoot[L]{\footnotesize{Aktenvermerk Nr. {!! Latex::escape($memo->number) !!} ({!! Latex::escape($memo->project->name) !!}), erstellt am {!! Latex::escape(\Carbon\Carbon::today()) !!}}}
\fancyfoot[R]{\footnotesize{Seite \thepage\ von \pageref{LastPage}}}

\begin{document}
\begin{minipage}{0.07\textwidth}
\qrcode[height=1cm]{ {!! Latex::escape(route('memos.show', $memo)) !!} }
\end{minipage}
\begin{minipage}{0.93\textwidth}
\large{\textbf{Aktenvermek Nr. {!! Latex::escape($memo->number) !!}}} \\ \large{\textbf{ {!! Latex::escape($memo->project->name) !!}}}
\end{minipage}
\\\\\\
\begin{tabular}{@{}lp{13.5cm}@{}}
\footnotesize{\textbf{Projekt:}} & \footnotesize{{!! Latex::escape($memo->project->name) !!}} \\
\footnotesize{\textbf{Titel:}} & \footnotesize{{!! Latex::escape($memo->title) !!}} \\
\footnotesize{\textbf{Datum:}} & \footnotesize{{!! Latex::escape($memo->meeting_held_on) !!}} \\
\footnotesize{\textbf{Verfassungsdatum:}} & \footnotesize{{!! Latex::escape($memo->created_at) !!}} \\
@if($memo->next_meeting_on)
\footnotesize{\textbf{Nächster Termin:}} & \footnotesize{{!! Latex::escape($memo->next_meeting_on) !!}} \\
@endif
\footnotesize{\textbf{Von:}} & \footnotesize{{!! Latex::escape($memo->employeeComposer->person->name) !!}} \\
@if($memo->personRecipient)
\footnotesize{\textbf{An:}} & \footnotesize{{!! Latex::escape($memo->personRecipient->name) !!}} \\
@endif
@if($memo->presentPeople->count() > 0)
\footnotesize{\textbf{Anwesende Personen:}} &
@foreach($memo->presentPeople as $person)
\footnotesize{{!! Latex::escape($person->name) !!}}@unless($loop->last)\footnotesize{, }@endunless
@endforeach
\\
@endif
@if($memo->notifiedPeople->count() > 0)
\footnotesize{\textbf{Verteiler:}} &
@foreach($memo->notifiedPeople as $person)
\footnotesize{{!! Latex::escape($person->name) !!}}@unless($loop->last)\footnotesize{, }@endunless
@endforeach
\\
@endif
\end{tabular}
\section{Vermerk}
\footnotesize{{!! Latex::fromMarkdown($memo->comment) !!}}
\end{document}
