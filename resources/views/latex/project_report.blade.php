@include('latex.partials.preamble')

@include('latex.partials.header')

\fancyfoot[L]{\footnotesize{Projekt Auswertung {!! Latex::escape($project->name) !!}@if($start) von {!! Latex::escape($start) !!}@endif
@if($end)bis {!! Latex::escape($end) !!}@endif, erstellt am {!! Latex::escape(\Carbon\Carbon::today()) !!}}}
\fancyfoot[R]{\footnotesize{Seite \thepage\ von \pageref{LastPage}}}

\renewcommand\TruncateMarker{\textasciitilde}
\def\servicetruncate{\truncate{3cm}}
\newcolumntype{A}[1]{ >{\collectcell\servicetruncate}p{#1}<{\endcollectcell} }

\begin{document}
\begin{minipage}{0.07\textwidth}
\qrcode[height=1cm]{ {!! Latex::escape(route('projects.show', $project)) !!} }
\end{minipage}
\begin{minipage}{0.93\textwidth}
\large{\textbf{Projekt Auswertung {!! Latex::escape($project->name) !!}}} \\ \large{\textbf{@unless($start || $end)gesamter @endunless Zeitraum @if($start) von {!! Latex::escape($start) !!}@endif @if($end)bis {!! Latex::escape($end) !!}@endif}}
\end{minipage}
\\\\
@if($filterPeople || $filterServices)\footnotesize{\textbf{Weitere Filter:}}\\
@if($filterPeople)\footnotesize{Mitarbeiter:}
@foreach($filterPeople as $person)
\footnotesize{{!! Latex::escape($person->name) !!}}@unless($loop->last)\footnotesize{, }@endunless
@endforeach
@endif
@if($filterPeople && $filterServices)\\@endif
@if($filterServices)\footnotesize{Leistungen:}
@foreach($filterServices as $service)
\footnotesize{{!! Latex::escape($service->name_with_unit) !!}}@unless($loop->last)\footnotesize{, }@endunless
@endforeach
@endif
\\
@endif

@if(count($report) > 0)
\begin{longtable}{@{}p{2.2cm}A{3cm}p{0.6cm}p{1.2cm}p{8.8cm}@{}}
\hline
\footnotesize{\textbf{Datum}} & \footnotesize{\textbf{Leistung}} & \footnotesize{\textbf{MA}} & \footnotesize{\textbf{Menge}} & \footnotesize{\textbf{Bemerkungen}} \\
\hline
\hline
\endhead
@foreach($report as $entry)
\footnotesize{{!! Latex::escape(\Carbon\Carbon::parse($entry->service_provided_on)->translatedFormat('D d.m.Y')) !!}} & \footnotesize{{!! Latex::escape($entry->service) !!}} & \footnotesize{{!! Latex::escape(Str::upper($entry->username)) !!}} & \footnotesize{{!! Latex::escape(Number::toLocal($entry->amount)) !!}} & \footnotesize{{!! Latex::escape($entry->comment ? Str::replace("\n", ', ', $entry->comment) : '') !!}} \\
\hline
@endforeach
\end{longtable}
\footnotesize{\textbf{Summen:}}\\\\
@foreach($sums as $sum)
\footnotesize{{!! Latex::escape($sum->service) !!}}: \footnotesize{{!! Latex::escape(Number::toLocal($sum->amount)) !!}}\\
@endforeach
\\\\
\begin{scriptsize}
Mitarbeiterkürzel:
\setlist{nosep}
\begin{description}[labelwidth=0.7cm]
@foreach($people as $person)
\item[{!! Latex::escape(Str::upper($person->employee->user->username)) !!}:] {!! Latex::escape($person->name) !!}
@endforeach
\end{description}
\end{scriptsize}
@else
Es sind keine Abrechnungen passend dem Filter vorhanden.
@endif


\end{document}
