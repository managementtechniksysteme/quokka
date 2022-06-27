@include('latex.partials.preamble')

@include('latex.partials.header')

\fancyfoot[L]{\footnotesize{Bautagesbericht Nr. {!! Latex::escape($constructionReport->number) !!} ({!! Latex::escape($constructionReport->project->name) !!}), erstellt am {!! Latex::escape(\Carbon\Carbon::today()) !!}}}
\fancyfoot[R]{\footnotesize{Seite \thepage\ von \pageref{LastPage}}}

\begin{document}
\begin{minipage}{0.07\textwidth}
\qrcode[height=1cm]{ {!! Latex::escape(route('construction-reports.show', $constructionReport)) !!} }
\end{minipage}
\begin{minipage}{0.93\textwidth}
\large{\textbf{Bautagesbericht Nr. {!! Latex::escape($constructionReport->number) !!}}} \\ \large{\textbf{ {!! Latex::escape($constructionReport->project->name) !!}}}
\end{minipage}
\\\\\\
\begin{tabular}{@{}lp{13.5cm}@{}}
\footnotesize{\textbf{Bauvorhaben:}} & \footnotesize{{!! Latex::escape($constructionReport->project->name) !!}} \\
\footnotesize{\textbf{Datum:}} & \footnotesize{{!! Latex::escape($constructionReport->services_provided_on) !!}} \\
\footnotesize{\textbf{Wetter:}} & \footnotesize{{!! Latex::escape(trans($constructionReport->weather)) !!}
({!! Latex::escape(Number::toLocal($constructionReport->minimum_temperature)) !!}@if($constructionReport->minimum_temperature !== $constructionReport->maximum_temperature) bis
{!! Latex::escape(Number::toLocal($constructionReport->maximum_temperature)) !!}@endif\textdegree{}C)} \\
\footnotesize{\textbf{Personalstand:}} &
@foreach($constructionReport->involvedEmployees as $employee)
\footnotesize{{!! Latex::escape($employee->person->name) !!}}@unless($loop->last)\footnotesize{, }@endunless
@endforeach
\\
@if($constructionReport->presentPeople->count() > 0)
\footnotesize{\textbf{Anwesende Personen:}} &
@foreach($constructionReport->presentPeople as $person)
\footnotesize{{!! Latex::escape($person->name) !!}}@unless($loop->last)\footnotesize{, }@endunless
@endforeach
\\
@endif
@if($constructionReport->other_visitors)
\footnotesize{\textbf{Sonstige Besucher:}} & \footnotesize{{!! Latex::escape($constructionReport->other_visitors) !!}} \\
@endif
@if($constructionReport->inspection_comment)
\footnotesize{\textbf{Güte- und Funktionsprüfung:}} & \footnotesize{{!! Latex::escape($constructionReport->inspection_comment) !!}} \\
@endif
@if($constructionReport->missing_documents)
\footnotesize{\textbf{Fehlende Ausführungsunterlagen:}} & \footnotesize{{!! Latex::escape($constructionReport->missing_documents) !!}} \\
@endif
@if($constructionReport->special_occurrences)
\footnotesize{\textbf{Besondere Vorkommnisse:}} & \footnotesize{{!! Latex::escape($constructionReport->special_occurrences) !!}} \\
@endif
@if($constructionReport->imminent_danger)
\footnotesize{\textbf{Gefahr in Verzug:}} & \footnotesize{{!! Latex::escape($constructionReport->imminent_danger) !!}} \\
@endif
@if($constructionReport->concerns)
\footnotesize{\textbf{Bedenken:}} & \footnotesize{{!! Latex::escape($constructionReport->concerns) !!}} \\
@endif
@if(Auth::check() && $constructionReport->status === 'finished')
\footnotesize{\textbf{\textcolor{success}{erledigt am:}}} & \footnotesize{\textcolor{success}{{!! Latex::escape($constructionReport->updated_at)!!}@if($constructionReport->activities->last()) ({!! Latex::escape(Str::upper($constructionReport->activities->last()->causer->username)) !!})@endif}}
@endif
\end{tabular}
\section{Leistungsfortschritt}
\footnotesize{{!! Latex::fromMarkdown($constructionReport->comment) !!}}
\vfill
\begin{minipage}[t][3cm]{0.5\textwidth}
\vfill
\centering
@if($constructionReport->employee->user->signature())
\includegraphics[height=2cm]{{!! Latex::escape($constructionReport->employee->user->signature()->getPath()) !!}}
\\
@endif
\footnotesize{Unterschrift @if($constructionReport->employee->user->signature())vom {!! Latex::escape($constructionReport->created_at) !!}@endif}
\\
\footnotesize{Auftragnehmer oder Beauftragter}
\end{minipage}
\begin{minipage}[t][3cm]{0.5\textwidth}
\vfill
\centering
@if($constructionReport->signature())
\includegraphics[height=2cm]{{!! Latex::escape($constructionReport->signature()->getPath()) !!}}
\\
@endif
\footnotesize{Unterschrift @if($constructionReport->signature())vom {!! Latex::escape($constructionReport->signature()->created_at) !!}@endif}
\\
\footnotesize{Bauherr oder bevollmächtigter Vertreter}
\end{minipage}
\\
\end{document}
