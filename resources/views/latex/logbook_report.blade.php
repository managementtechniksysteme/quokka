@include('latex.partials.preamble')

@include('latex.partials.header')

\fancyfoot[L]{\footnotesize{{!! Latex::escape('Fahrtenbuch Auswertung') !!}@if($vehicle) {!! Latex::escape($vehicle->registration_identifier) !!}@endif
@if($start) von {!! Latex::escape($start) !!}@endif
@if($end) bis {!! Latex::escape($end) !!}@endif, erstellt am {!! Latex::escape(\Carbon\Carbon::today()) !!}}}
\fancyfoot[R]{\footnotesize{Seite \thepage\ von \pageref{LastPage}}}

\renewcommand\TruncateMarker{\textasciitilde}
\def\projecttruncate{\truncate{2.1cm}}
\newcolumntype{A}[1]{ >{\collectcell\projecttruncate}p{#1}<{\endcollectcell} }
\def\placetruncate{\truncate{2.2cm}}
\newcolumntype{B}[1]{ >{\collectcell\placetruncate}p{#1}<{\endcollectcell} }

\begin{document}
\begin{minipage}{0.07\textwidth}
\qrcode[height=1cm]{ {!! Latex::escape(route('accounting.index')) !!} }
\end{minipage}
\begin{minipage}{0.93\textwidth}
\large{\textbf{Fahrtenbuch Auswertung @if($vehicle) {!! Latex::escape($vehicle->registration_identifier) !!}@endif}} \\ \large{\textbf{@unless($start || $end)gesamter @endunless Zeitraum @if($start) von {!! Latex::escape($start) !!}@endif @if($end)bis {!! Latex::escape($end) !!}@endif}}
\end{minipage}
\\\\
@if($employee || $project)\footnotesize{\textbf{Weitere Filter:}}
@if($employee)\footnotesize{Mitarbeiter: {!! Latex::escape($employee->person->name) !!}}@endif
@if($employee && $project), @endif
@if($project)\footnotesize{Projekt: {!! Latex::escape($project->name) !!}}@endif
\\
@endif
@if(count($report) > 0)
\begin{longtable}{@{}p{1.5cm}p{1.9cm}p{0.6cm}A{2.1cm}B{2.1cm}B{2.1cm}p{1cm}p{1cm}p{0.5cm}p{0.9cm}@{}}
\hline
\multirow{2}{*}{\footnotesize{\textbf{Fahrzeug}}} & \multirow{2}{*}{\footnotesize{\textbf{Datum}}} & \multirow{2}{*}{\footnotesize{\textbf{MA}}} & \multirow{2}{*}{\footnotesize{\textbf{Projekt}}} & \multirow{2}{*}{\footnotesize{\textbf{Start}}} & \multirow{2}{*}{\footnotesize{\textbf{Ziel}}} & \multicolumn{3}{c}{\footnotesize{\textbf{Kilometer}}} & \multirow{2}{*}{\footnotesize{\textbf{get. L}}} \\
\cmidrule{7-9}
& & & & & & \footnotesize{\textbf{Start}} & \footnotesize{\textbf{Ende}} & \footnotesize{\textbf{gef.}} & \\
\hline
\hline
\endhead
@foreach($report as $entry)
\footnotesize{{!! Latex::escape($entry->vehicle->registration_identifier) !!}} & \footnotesize{{!! Latex::escape($entry->driven_on->translatedFormat('D d.m.y')) !!}} & \footnotesize{{!! Latex::escape(Str::upper($entry->employee->user->username)) !!}} &  \footnotesize{{!! Latex::escape(optional($entry->project)->short_name ?? '') !!}} & \footnotesize{{!! Latex::escape($entry->origin) !!}} & \footnotesize{{!! Latex::escape($entry->destination) !!}} & \footnotesize{{!! Latex::escape(Number::toLocal($entry->start_kilometres)) !!}} & \footnotesize{{!! Latex::escape(Number::toLocal($entry->end_kilometres)) !!}} & \footnotesize{{!! Latex::escape(Number::toLocal($entry->driven_kilometres)) !!}} & \footnotesize{{!! Latex::escape($entry->litres_refuelled ? Number::toLocal($entry->litres_refuelled) : '') !!}} \\
\hline
@endforeach
\footnotesize{\textbf{Summe}} & \footnotesize{} & \footnotesize{} & \footnotesize{} & \footnotesize{} & \footnotesize{} & \footnotesize{} & \footnotesize{} & \footnotesize{\textbf{{!! Latex::escape($report->sum('driven_kilometres') > 0 ? Number::toLocal($report->sum('driven_kilometres')) : '') !!}}} & \footnotesize{\textbf{{!! Latex::escape($report->sum('litres_refuelled') > 0 ? Number::toLocal($report->sum('litres_refuelled')) : '') !!}}} \\
\hline
\end{longtable}
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
Es sind keine Fahrten passend dem Filter vorhanden.
@endif
\end{document}
