@include('latex.partials.preamble')

@include('latex.partials.header')

\fancyfoot[L]{\footnotesize{Abrechnung Auswertung @if(count($employees) === 1){!! Latex::escape($employees[0]->person->name) !!}@endif
@if($start) von {!! Latex::escape($start) !!}@endif
@if($end)bis {!! Latex::escape($end) !!}@endif, erstellt am {!! Latex::escape(\Carbon\Carbon::today()) !!}}}
\fancyfoot[R]{\footnotesize{Seite \thepage\ von \pageref{LastPage}}}

\renewcommand\TruncateMarker{\textasciitilde}
\def\projecttruncate{\truncate{5.3cm}}
\newcolumntype{A}[1]{ >{\collectcell\projecttruncate}p{#1}<{\endcollectcell} }

\begin{document}
\begin{minipage}{0.07\textwidth}
\qrcode[height=1cm]{ {!! Latex::escape(route('accounting.index')) !!} }
\end{minipage}
\begin{minipage}{0.93\textwidth}
\large{\textbf{Abrechnung Auswertung {!! Latex::escape(count($employees) === 1 ? $employees[0]->person->name : '') !!}}} \\ \large{\textbf{@unless($start || $end)gesamter @endunless Zeitraum @if($start) von {!! Latex::escape($start) !!}@endif @if($end)bis {!! Latex::escape($end) !!}@endif}}
\end{minipage}
\\\\
@if(count($employees) > 1 || $project || $service)\footnotesize{\textbf{Weitere Filter:}}
@if(count($employees) > 1)
\footnotesize{Mitarbeiter: }
@foreach($employees as $employee)
\footnotesize{{!! Latex::escape(Str::upper($employee->user->username)) !!}}@unless($loop->last)\footnotesize{, }@endunless
@endforeach
@if($project || $service)\footnotesize{, }@endif
@endif
@if($project)\footnotesize{Projekt: {!! Latex::escape($project->name) !!}}@endif
@if($project && $service)\footnotesize{, }@endif
@if($service)\footnotesize{Leistung: {!! Latex::escape($service->name_with_unit) !!}}@endif
\\
@endif
@if(count($employees) === 1)
\footnotesize{\textbf{Verfügbarer Urlaub:} {!! Latex::escape(Number::toLocal($employees[0]->holidays)) !!} {!! Latex::escape($holidayService->unit) !!}}
@endif
@if(count($report) > 0)
@if(count($employees) === 1)
\begin{longtable}{@{}p{2.2cm}p{5.9cm}p{0.6cm}p{1.2cm}p{1.2cm}p{1.4cm}p{0.9cm}p{1.1cm}@{}}
@else
\begin{longtable}{@{}p{2.2cm}A{5.3cm}p{0.6cm}p{0.6cm}p{1.2cm}p{1.2cm}p{1.4cm}p{0.9cm}p{1.1cm}@{}}
@endif
\hline
@if(count($employees) === 1)
\footnotesize{\textbf{Datum}} & \footnotesize{\textbf{Projekt}} & \footnotesize{\textbf{Std.}} & \footnotesize{\textbf{Diä. (h)}} & \footnotesize{\textbf{ÜS 50\%}} & \footnotesize{\textbf{ÜS 100\%}} & \footnotesize{\textbf{ZA (h)}} & \footnotesize{\textbf{Url. (T)}} \\
@else
\footnotesize{\textbf{Datum}} & \footnotesize{\textbf{Projekt}} & \footnotesize{\textbf{MA}} & \footnotesize{\textbf{Std.}} & \footnotesize{\textbf{Diä. (h)}} & \footnotesize{\textbf{ÜS 50\%}} & \footnotesize{\textbf{ÜS 100\%}} & \footnotesize{\textbf{ZA (h)}} & \footnotesize{\textbf{Url. (T)}} \\
@endif
\hline
\hline
\endhead
@foreach($report as $entry)
@if(count($employees) === 1)
\footnotesize{{!! Latex::escape(\Carbon\Carbon::parse($entry->date)->translatedFormat('D d.m.Y')) !!}} & \footnotesize{{!! Latex::escape($entry->project) !!}} & \footnotesize{{!! Latex::escape($entry->amount_hours ? Number::toLocal($entry->amount_hours) : '') !!}} & \footnotesize{{!! Latex::escape($entry->amount_allowances ? Number::toLocal($entry->amount_allowances) : '') !!}} & \footnotesize{{!! Latex::escape($entry->amount_overtime_50 ?Number::toLocal($entry->amount_overtime_50) : '') !!}} & \footnotesize{{!! Latex::escape($entry->amount_overtime_100 ? Number::toLocal($entry->amount_overtime_100) : '') !!}} & \footnotesize{{!! Latex::escape($entry->amount_time_balance ? Number::toLocal($entry->amount_time_balance) : '') !!}} & \footnotesize{{!! Latex::escape($entry->amount_holidays ? Number::toLocal($entry->amount_holidays) : '') !!}} \\
@else
\footnotesize{{!! Latex::escape(\Carbon\Carbon::parse($entry->date)->translatedFormat('D d.m.Y')) !!}} & \footnotesize{{!! Latex::escape($entry->project) !!}} & \footnotesize{{!! Latex::escape(Str::upper($entry->username)) !!}} & \footnotesize{{!! Latex::escape($entry->amount_hours ? Number::toLocal($entry->amount_hours) : '') !!}} & \footnotesize{{!! Latex::escape($entry->amount_allowances ? Number::toLocal($entry->amount_allowances) : '') !!}} & \footnotesize{{!! Latex::escape($entry->amount_overtime_50 ?Number::toLocal($entry->amount_overtime_50) : '') !!}} & \footnotesize{{!! Latex::escape($entry->amount_overtime_100 ? Number::toLocal($entry->amount_overtime_100) : '') !!}} & \footnotesize{{!! Latex::escape($entry->amount_time_balance ? Number::toLocal($entry->amount_time_balance) : '') !!}} & \footnotesize{{!! Latex::escape($entry->amount_holidays ? Number::toLocal($entry->amount_holidays) : '') !!}} \\
@endif
\hline
@endforeach
@if(count($employees) === 1)
\footnotesize{\textbf{Summe}} & \footnotesize{} & \footnotesize{\textbf{{!! Latex::escape($report->sum('amount_hours') > 0 ? Number::toLocal($report->sum('amount_hours')) : '') !!}}} & \footnotesize{\textbf{@if($report->sum('amount_allowances') > 0){!! Latex::escape(Number::toLocal($report->sum('amount_allowances'))) !!} @if($allowancesService && $allowancesService->costs > 0) \newline ({!! Latex::escape(Number::toLocal($report->sum('amount_allowances') * $allowancesService->costs) . $currencyUnit) !!})@endif @endif}} & \footnotesize{\textbf{{!! Latex::escape($report->sum('amount_overtime_50') > 0 ? Number::toLocal($report->sum('amount_overtime_50')) : '') !!}}} & \footnotesize{\textbf{{!! Latex::escape($report->sum('amount_overtime_100') > 0 ? Number::toLocal($report->sum('amount_overtime_100')) : '') !!}}} & \footnotesize{\textbf{{!! Latex::escape($report->sum('amount_time_balance') > 0 ? Number::toLocal($report->sum('amount_time_balance')) : '') !!}}} & \footnotesize{\textbf{{!! Latex::escape($report->sum('amount_holidays') > 0 ? Number::toLocal($report->sum('amount_holidays')) : '') !!}}} \\
@else
\footnotesize{\textbf{Summe}} & \footnotesize{} & \footnotesize{} & \footnotesize{\textbf{{!! Latex::escape($report->sum('amount_hours') > 0 ? Number::toLocal($report->sum('amount_hours')) : '') !!}}} & \footnotesize{\textbf{@if($report->sum('amount_allowances') > 0){!! Latex::escape(Number::toLocal($report->sum('amount_allowances'))) !!} @if($allowancesService && $allowancesService->costs > 0) \newline ({!! Latex::escape(Number::toLocal($report->sum('amount_allowances') * $allowancesService->costs) . $currencyUnit) !!})@endif @endif}} & \footnotesize{\textbf{{!! Latex::escape($report->sum('amount_overtime_50') > 0 ? Number::toLocal($report->sum('amount_overtime_50')) : '') !!}}} & \footnotesize{\textbf{{!! Latex::escape($report->sum('amount_overtime_100') > 0 ? Number::toLocal($report->sum('amount_overtime_100')) : '') !!}}} & \footnotesize{\textbf{{!! Latex::escape($report->sum('amount_time_balance') > 0 ? Number::toLocal($report->sum('amount_time_balance')) : '') !!}}} & \footnotesize{\textbf{{!! Latex::escape($report->sum('amount_holidays') > 0 ? Number::toLocal($report->sum('amount_holidays')) : '') !!}}} \\
@endif
\hline
\end{longtable}
@else
Es sind keine Abrechnungen passend dem Filter vorhanden.
@endif
@if($kilometre_costs > 0)
\footnotesize{\textbf{Privatkilometer: }
@if(count($private_kilometres) === 1)
\footnotesize{{!! Latex::escape(Number::toLocal($private_kilometres[array_key_first($private_kilometres)])) !!} ({!! Latex::escape(Number::toLocal($private_kilometres[array_key_first($private_kilometres)] * $kilometre_costs)) . $currencyUnit !!})}
@else
@foreach($private_kilometres as $username => $kilometres)
\footnotesize{{!! Latex::escape(Str::upper($username)) !!}: {!! Latex::escape(Number::toLocal($kilometres)) !!} ({!! Latex::escape(Number::toLocal($kilometres * $kilometre_costs)) . $currencyUnit !!}), }
@endforeach
\footnotesize{Summe: {!! Latex::escape(Number::toLocal(array_sum($private_kilometres))) !!} ({!! Latex::escape(Number::toLocal(array_sum($private_kilometres) * $kilometre_costs)) . $currencyUnit !!})}
@endif
@endif
@if(count($employees) > 1)
\\\\
\begin{footnotesize}
\textbf{Mitarbeiterkürzel:}
\setlist{nosep, topsep=-0.2cm}
\setdescription{font=\normalfont}
\begin{description}[labelwidth=0.8cm]
@foreach($employees as $employee)
\item[{!! Latex::escape(Str::upper($employee->user->username)) !!}:] {!! Latex::escape($employee->person->name) !!}
@endforeach
\end{description}
\end{footnotesize}
@endif
\end{document}