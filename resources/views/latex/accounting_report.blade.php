@include('latex.partials.preamble')

@include('latex.partials.header')

\fancyfoot[L]{\footnotesize{Abrechnung Auswertung {!! Latex::escape($employee->person->name) !!}@if($start) von {!! Latex::escape($start) !!}@endif
@if($end)bis {!! Latex::escape($end) !!}@endif, erstellt am {!! Latex::escape(\Carbon\Carbon::today()) !!}}}
\fancyfoot[R]{\footnotesize{Seite \thepage\ von \pageref{LastPage}}}

\begin{document}
\begin{minipage}{0.07\textwidth}
\qrcode[height=1cm]{ {!! Latex::escape(route('accounting.index')) !!} }
\end{minipage}
\begin{minipage}{0.93\textwidth}
\large{\textbf{Abrechnung Auswertung {!! Latex::escape($employee->person->name) !!}}} \\ \large{\textbf{@unless($start || $end)gesamter @endunless Zeitraum @if($start) von {!! Latex::escape($start) !!}@endif @if($end)bis {!! Latex::escape($end) !!}@endif}}
\end{minipage}
\\\\\\
@if($project || $service)\footnotesize{\textbf{Weitere Filter:}}
@if($project)\footnotesize{Projekt: {!! Latex::escape($project->name) !!}}@endif
@if($project && $service), @endif
@if($service)\footnotesize{Leistung: {!! Latex::escape($service->name_with_unit) !!}}@endif
\\
@endif
\footnotesize{\textbf{Verfügbarer Urlaub:} {!! Latex::escape(Number::toLocal($employee->holidays)) !!} {!! Latex::escape($holidayService->unit) !!}}
@if(count($report) > 0)
\begin{longtable}{@{}p{2.2cm}p{5.9cm}p{0.6cm}p{1.2cm}p{1.2cm}p{1.4cm}p{0.9cm}p{1.1cm}@{}}
\hline
\footnotesize{\textbf{Datum}} & \footnotesize{\textbf{Projekt}} & \footnotesize{\textbf{Std.}} & \footnotesize{\textbf{Diä. (h)}} & \footnotesize{\textbf{ÜS 50\%}} & \footnotesize{\textbf{ÜS 100\%}} & \footnotesize{\textbf{ZA (h)}} & \footnotesize{\textbf{Url. (T)}} \\
\hline
\hline
\endhead
@foreach($report as $entry)
\footnotesize{{!! Latex::escape(\Carbon\Carbon::parse($entry->date)->translatedFormat('D d.m.Y')) !!}} & \footnotesize{{!! Latex::escape($entry->project) !!}} & \footnotesize{{!! Latex::escape($entry->amount_hours ? Number::toLocal($entry->amount_hours) : '') !!}} & \footnotesize{{!! Latex::escape($entry->amount_allowances ? Number::toLocal($entry->amount_allowances) : '') !!}} & \footnotesize{{!! Latex::escape($entry->amount_overtime_50 ?Number::toLocal($entry->amount_overtime_50) : '') !!}} & \footnotesize{{!! Latex::escape($entry->amount_overtime_100 ? Number::toLocal($entry->amount_overtime_100) : '') !!}} & \footnotesize{{!! Latex::escape($entry->amount_time_balance ? Number::toLocal($entry->amount_time_balance) : '') !!}} & \footnotesize{{!! Latex::escape($entry->amount_holidays ? Number::toLocal($entry->amount_holidays) : '') !!}}  \\
\hline
@endforeach
\footnotesize{\textbf{Summe}} & \footnotesize{} & \footnotesize{\textbf{{!! Latex::escape($report->sum('amount_hours') > 0 ? Number::toLocal($report->sum('amount_hours')) : '') !!}}} & \footnotesize{\textbf{@if($report->sum('amount_allowances') > 0){!! Latex::escape(Number::toLocal($report->sum('amount_allowances'))) !!} @if($allowancesService && $allowancesService->costs > 0) \newline ({!! Latex::escape(Number::toLocal($report->sum('amount_allowances') * $allowancesService->costs) . $currencyUnit) !!})@endif @endif}} & \footnotesize{\textbf{{!! Latex::escape($report->sum('amount_overtime_50') > 0 ? Number::toLocal($report->sum('amount_overtime_50')) : '') !!}}} & \footnotesize{\textbf{{!! Latex::escape($report->sum('amount_overtime_100') > 0 ? Number::toLocal($report->sum('amount_overtime_100')) : '') !!}}} & \footnotesize{\textbf{{!! Latex::escape($report->sum('amount_time_balance') > 0 ? Number::toLocal($report->sum('amount_time_balance')) : '') !!}}} & \footnotesize{\textbf{{!! Latex::escape($report->sum('amount_holidays') > 0 ? Number::toLocal($report->sum('amount_holidays')) : '') !!}}} \\
\hline
\end{longtable}
@else
Es sind keine Abrechnungen passend dem Filter vorhanden.
@endif
\end{document}
