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
\footnotesize{\textbf{Summen:}}\\
@foreach($sums as $sum)
\footnotesize{{!! Latex::escape($sum->service) !!}}\footnotesize{: }\footnotesize{{!! Latex::escape(Number::toLocal($sum->amount)) !!}}\\
@endforeach
\\
\begin{ignorelinebreaks}
@if($project->costs || $project->billed_costs || $project->current_costs || $project->wage_costs || $project->current_wage_costs || $project->material_costs || $project->current_material_costs)
@can('projects.view.estimates')
\footnotesize{\textbf{Kosten ohne Filter:}}\\
@if($project->costs || $project->current_costs)
\footnotesize{Gesamtkosten: }\footnotesize{{!! Latex::escape($project->costs ? $currencyUnit . ' ' . Number::toLocal($project->costs) : '') !!}}
@if($project->current_costs)
@if($project->costs)\footnotesize{ - }@endif
\footnotesize{aktuell: }\footnotesize{{!! Latex::escape($currencyUnit . ' ' . Number::toLocal($project->current_costs)) !!}}
@if($project->current_costs_percentage)
\footnotesize{ (}\footnotesize{{!! Latex::escape(Number::toLocal($project->current_costs_percentage, 1)) !!}}\footnotesize{{!! Latex::escape('%') !!}}\footnotesize{) }
@switch($project->current_costs_status)
@case('success')
\textcolor{success}{\textbf{$\downarrow$}}
@break
@case('warning')
\textcolor{warning}{\textbf{$\searrow$}}
@break
@case('danger')
\textcolor{danger}{\textbf{$\uparrow$}}
@break
@endswitch
@endif
@endif
\\
@endif
@if($project->billed_costs || $project->current_costs)
\footnotesize{verrechnete Kosten: }\footnotesize{{!! Latex::escape($project->billed_costs ? $currencyUnit . ' ' . Number::toLocal($project->billed_costs) : '') !!}}
@if($project->current_costs)
@if($project->costs)\footnotesize{ - }@endif
\footnotesize{aktuell: }\footnotesize{{!! Latex::escape($currencyUnit . ' ' . Number::toLocal($project->current_costs)) !!}}
@if($project->current_billed_costs_percentage)
\footnotesize{ (}\footnotesize{{!! Latex::escape(Number::toLocal($project->current_billed_costs_percentage, 1)) !!}}\footnotesize{{!! Latex::escape('%') !!}}\footnotesize{) }
@switch($project->current_billed_costs_status)
@case('success')
\textcolor{success}{\textbf{$\downarrow$}}
@break
@case('warning')
\textcolor{warning}{\textbf{$\searrow$}}
@break
@case('danger')
\textcolor{danger}{\textbf{$\uparrow$}}
@break
@endswitch
@endif
@endif
\\
@endif
@if($project->wage_costs || $project->current_wage_costs)
\footnotesize{Lohnkosten: }\footnotesize{{!! Latex::escape($project->wage_costs ? $currencyUnit . ' ' . Number::toLocal($project->wage_costs) : '') !!}}
@if($project->current_wage_costs)
@if($project->wage_costs)\footnotesize{ - }@endif
\footnotesize{aktuell: }\footnotesize{{!! Latex::escape($currencyUnit . ' ' . Number::toLocal($project->current_wage_costs)) !!}}
@if($project->current_wage_costs_percentage)
\footnotesize{ (}\footnotesize{{!! Latex::escape(Number::toLocal($project->current_wage_costs_percentage, 1)) !!}}\footnotesize{{!! Latex::escape('%') !!}}\footnotesize{) }
@switch($project->current_wage_costs_status)
@case('success')
\textcolor{success}{\textbf{$\downarrow$}}
@break
@case('warning')
\textcolor{warning}{\textbf{$\searrow$}}
@break
@case('danger')
\textcolor{danger}{\textbf{$\uparrow$}}
@break
@endswitch
@endif
@endif
\\
@endif
@if($project->material_costs || $project->current_material_costs)
\footnotesize{Materialkosten: }\footnotesize{{!! Latex::escape($project->material_costs ? $currencyUnit . ' ' . Number::toLocal($project->material_costs) : '') !!}}
@if($project->current_material_costs)
@if($project->material_costs)\footnotesize{ - }@endif
\footnotesize{aktuell: }\footnotesize{{!! Latex::escape($currencyUnit . ' ' . Number::toLocal($project->current_material_costs)) !!}}
@if($project->current_material_costs_percentage)
\footnotesize{ (}\footnotesize{{!! Latex::escape(Number::toLocal($project->current_material_costs_percentage, 1)) !!}}\footnotesize{{!! Latex::escape('%') !!}}\footnotesize{) }
@switch($project->current_material_costs_status)
@case('success')
\textcolor{success}{\textbf{$\downarrow$}}
@break
@case('warning')
\textcolor{warning}{\textbf{$\searrow$}}
@break
@case('danger')
\textcolor{danger}{\textbf{$\uparrow$}}
@break
@endswitch
@endif
@endif
\\
@endif
@if($project->current_kiloemetre_costs)
\footnotesize{Fahrtkosten (}\footnotesize{{!! Latex::escape(Number::toLocal($project->current_kilemetres) . 'km') !!}}\footnotesize{) km}\footnotesize{{!! Latex::escape($currencyUnit . ' ' . Number::toLocal($project->current_kilemetre_costs)) !!}}
\\
@endif
@if($projectOverallCostsWarningPercentage || $projectBilledCostsWarningPercentage || $projectWageCostsWarningPercentage || $projectMaterialCostsWarningPercentage)
\footnotesize{Warnschwellen: }
@if($projectOverallCostsWarningPercentage)\footnotesize{Gesamt: }\footnotesize{{!! Latex::escape($projectOverallCostsWarningPercentage) !!}}\footnotesize{{!! Latex::escape('%') !!}}
@if($projectBilledCostsWarningPercentage || $projectWageCostsWarningPercentage || $projectMaterialCostsWarningPercentage)
\footnotesize{, }
@endif
@endif
@if($projectBilledCostsWarningPercentage)\footnotesize{verrechnet: }\footnotesize{{!! Latex::escape($projectBilledCostsWarningPercentage) !!}}\footnotesize{{!! Latex::escape('%') !!}}
@if($projectWageCostsWarningPercentage || $projectMaterialCostsWarningPercentage)
\footnotesize{, }
@endif
@endif
@if($projectWageCostsWarningPercentage)\footnotesize{Lohn: }\footnotesize{{!! Latex::escape($projectWageCostsWarningPercentage) !!}}\footnotesize{{!! Latex::escape('%') !!}}
@if($projectMaterialCostsWarningPercentage)
\footnotesize{, }
@endif
@endif
@if($projectMaterialCostsWarningPercentage)\footnotesize{Material: }\footnotesize{{!! Latex::escape($projectMaterialCostsWarningPercentage) !!}}\footnotesize{{!! Latex::escape('%') !!}}@endif
\\
@endif
\\
@endcan
@endif
\end{ignorelinebreaks}
\begin{footnotesize}
\textbf{Mitarbeiterkürzel:}
\setlist{nosep, topsep=-0.2cm}
\setdescription{font=\normalfont}
\begin{description}[labelwidth=0.8cm]
@foreach($people as $person)
\item[{!! Latex::escape(Str::upper($person->employee->user->username)) !!}:] {!! Latex::escape($person->name) !!}
@endforeach
\end{description}
\end{footnotesize}
@else
Es sind keine Abrechnungen passend dem Filter vorhanden.
@endif


\end{document}
