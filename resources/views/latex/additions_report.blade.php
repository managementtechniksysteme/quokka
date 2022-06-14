@include('latex.partials.preamble')

@include('latex.partials.header')

\fancyfoot[L]{\footnotesize{Regiebericht Nr. {!! Latex::escape($additionsReport->number) !!} ({!! Latex::escape($additionsReport->project->name) !!}), erstellt am {!! Latex::escape(\Carbon\Carbon::today()) !!}}}
\fancyfoot[R]{\footnotesize{Seite \thepage\ von \pageref{LastPage}}}

\begin{document}
\begin{minipage}{0.07\textwidth}
\qrcode[height=1cm]{ {!! Latex::escape(route('additions-reports.show', $additionsReport)) !!} }
\end{minipage}
\begin{minipage}{0.93\textwidth}
\large{\textbf{Regiebericht Nr. {!! Latex::escape($additionsReport->number) !!}}} \\ \large{\textbf{ {!! Latex::escape($additionsReport->project->name) !!}}}
\end{minipage}
\\\\\\
\begin{tabular}{@{}lp{13.5cm}@{}}
\footnotesize{\textbf{Bauvorhaben:}} & \footnotesize{{!! Latex::escape($additionsReport->project->name) !!}} \\
\footnotesize{\textbf{Datum:}} & \footnotesize{{!! Latex::escape($additionsReport->services_provided_on) !!}} \\
\footnotesize{\textbf{Regiestunden:}} & \footnotesize{{!! Latex::escape(Number::toLocal($additionsReport->hours)) !!}} \\
\footnotesize{\textbf{Wetter:}} & \footnotesize{{!! Latex::escape(trans($additionsReport->weather)) !!}
({!! Latex::escape(Number::toLocal($additionsReport->minimum_temperature)) !!}@if($additionsReport->minimum_temperature !== $additionsReport->maximum_temperature) bis
{!! Latex::escape(Number::toLocal($additionsReport->maximum_temperature)) !!}@endif\textdegree{}C)} \\
\footnotesize{\textbf{Personalstand:}} &
@foreach($additionsReport->involvedEmployees as $employee)
\footnotesize{{!! Latex::escape($employee->person->name) !!}}@unless($loop->last)\footnotesize{, }@endunless
@endforeach
\\
@if($additionsReport->presentPeople->count() > 0)
\footnotesize{\textbf{Anwesende Personen:}} &
@foreach($additionsReport->presentPeople as $person)
\footnotesize{{!! Latex::escape($person->name) !!}}@unless($loop->last)\footnotesize{, }@endunless
@endforeach
\\
@endif
@if($additionsReport->other_visitors)
\footnotesize{\textbf{Sonstige Besucher:}} & \footnotesize{{!! Latex::escape($additionsReport->other_visitors) !!}} \\
@endif
@if($additionsReport->inspection_comment)
\footnotesize{\textbf{Güte- und Funktionsprüfung:}} & \footnotesize{{!! Latex::escape($additionsReport->inspection_comment) !!}} \\
@endif
@if($additionsReport->missing_documents)
\footnotesize{\textbf{Fehlende Ausführungsunterlagen:}} & \footnotesize{{!! Latex::escape($additionsReport->missing_documents) !!}} \\
@endif
@if($additionsReport->special_occurrences)
\footnotesize{\textbf{Besondere Vorkommnisse:}} & \footnotesize{{!! Latex::escape($additionsReport->special_occurrences) !!}} \\
@endif
@if($additionsReport->imminent_danger)
\footnotesize{\textbf{Gefahr in Verzug:}} & \footnotesize{{!! Latex::escape($additionsReport->imminent_danger) !!}} \\
@endif
@if($additionsReport->concerns)
\footnotesize{\textbf{Bedenken:}} & \footnotesize{{!! Latex::escape($additionsReport->concerns) !!}} \\
@endif
@if(Auth::check() && $additionsReport->status === 'finished')
\footnotesize{\textbf{\textcolor{success}{erledigt am:}}} & \footnotesize{\textcolor{success}{{!! Latex::escape($additionsReport->updated_at)!!}}}
@endif
\end{tabular}
\section{Leistungsfortschritt}
\footnotesize{{!! Latex::fromMarkdown($additionsReport->comment) !!}}
\vfill
\begin{minipage}[t][3cm]{0.5\textwidth}
\vfill
\centering
@if($additionsReport->employee->user->signature())
\includegraphics[height=2cm]{{!! Latex::escape($additionsReport->employee->user->signature()->getPath()) !!}}
\\
@endif
\footnotesize{Unterschrift @if($additionsReport->employee->user->signature())vom {!! Latex::escape($additionsReport->created_at) !!}@endif}
\\
\footnotesize{Auftragnehmer oder Beauftragter}
\end{minipage}
\begin{minipage}[t][3cm]{0.5\textwidth}
\vfill
\centering
@if($additionsReport->signature())
\includegraphics[height=2cm]{{!! Latex::escape($additionsReport->signature()->getPath()) !!}}
\\
@endif
\footnotesize{Unterschrift @if($additionsReport->signature())vom {!! Latex::escape($additionsReport->signature()->created_at) !!}@endif}
\\
\footnotesize{Bauherr oder bevollmächtigter Vertreter}
\end{minipage}
\\
\end{document}
