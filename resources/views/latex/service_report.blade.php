@include('latex.partials.preamble')

@include('latex.partials.header')

\fancyfoot[L]{\footnotesize{Servicebericht Nr. {!! Latex::escape($serviceReport->number) !!}, erstellt am {!! Latex::escape(\Carbon\Carbon::today()) !!}}}
\fancyfoot[R]{\footnotesize{Seite \thepage\ von \pageref{LastPage}}}

\begin{document}
\begin{minipage}{0.07\textwidth}
\qrcode[height=1cm]{ {!! Latex::escape(route('service-reports.show', $serviceReport)) !!} }
\end{minipage}
\begin{minipage}{0.93\textwidth}
\large{\textbf{Servicebericht Nr. {!! Latex::escape($serviceReport->number) !!}}} \\ \large{\textbf{ {!! Latex::escape($serviceReport->project->name) !!}}}
\end{minipage}
\\\\\\
\begin{tabular}{@{}p{3cm}p{5.5cm}p{3cm}p{5.5cm}@{}}
@if($serviceReport->project->company->address->first())
\footnotesize{\textbf{Kunde:}} & \footnotesize{{!! Latex::escape($serviceReport->project->company->address->first()->name) !!}} & \footnotesize{\textbf{Betreiber:}} & \footnotesize{{!! Latex::escape(optional($serviceReport->project->company->operatorAddress->first())->name ?? $serviceReport->project->company->address->first()->name) !!}} \\
\footnotesize{\textbf{PLZ, Ort:}} & \footnotesize{{!! Latex::escape($serviceReport->project->company->address->first()->postcode) !!}, {!! Latex::escape($serviceReport->project->company->address->first()->city) !!}} & \footnotesize{\textbf{PLZ, Ort:}} & \footnotesize{{!! Latex::escape(optional($serviceReport->project->company->operatorAddress->first())->postcode ?? $serviceReport->project->company->address->first()->postcode) !!}, {!! Latex::escape(optional($serviceReport->project->company->operatorAddress->first())->city ?? $serviceReport->project->company->address->first()->city) !!}} \\
\footnotesize{\textbf{Straße, Nr:}} & \footnotesize{{!! Latex::escape($serviceReport->project->company->address->first()->street_number) !!}} & \footnotesize{\textbf{Straße, Nr:}} & \footnotesize{{!! Latex::escape(optional($serviceReport->project->company->operatorAddress->first())->street_number ?? $serviceReport->project->company->address->first()->street_number) !!}} \\
&&& \\
@else
\footnotesize{\textbf{Kunde:}} & \footnotesize{nicht angegeben} & \footnotesize{\textbf{Betreiber:}} & \footnotesize{nicht angegeben} \\
@endif
\footnotesize{\textbf{Techniker MTS:}} & \footnotesize{{!! Latex::escape($serviceReport->employee->person->name) !!}} & @if($serviceReport->status === 'finished')erledigt am: @endif & @if($serviceReport->status === 'finished'){!! Latex::escape($service->updated_on) !!} @endif \\
\end{tabular}
\\\\\\\\
\footnotesize{\textbf{Kurzbericht}} \\
\footnotesize{{!! Latex::fromMarkdown($serviceReport->comment) !!}}
\\\\
\footnotesize{\textbf{Vollbrachte Leistungen}}
\begin{longtable}{@{}|p{3.06cm}|p{3.06cm}|p{3.06cm}|p{3.06cm}|p{3.06cm}|@{}}
\hline \footnotesize{Tag} & \footnotesize{Datum} & \footnotesize{Stunden} & \footnotesize{Diäten} & \footnotesize{gefahrene KM} \\
\hline
\hline
@foreach($serviceReport->services as $service)
{!! Latex::escape($service->provided_on->translatedFormat("l")) !!} & \footnotesize{{!! Latex::escape($service->provided_on) !!}} & \footnotesize{{!! Latex::escape($service->hours) !!}} & \footnotesize{{!! Latex::escape($service->allowances) !!}} & \footnotesize{{!! Latex::escape($service->kilometres) !!}} \\
\hline
@endforeach
\caption*{\footnotesize{Stunden inklusive Rückreiseaufwand}}\\
\end{longtable}
\vfill
\begin{minipage}[t][2.5cm]{0.5\textwidth}
\vfill
\centering
{{--
@if($serviceReport->employee->user->signature())
\includegraphics[height=2cm]{{!! Latex::escape($serviceReport->employee->user->signature()->getPath()) !!}}
@endif
--}}
\footnotesize{Unterschrift Techniker{{-- @if($serviceReport->employee->user->signature())vom {!! Latex::escape($serviceReport->created_at) !!} @endif--}}}
\end{minipage}
\begin{minipage}[t][2.5cm]{0.5\textwidth}
\vfill
\centering
@if($serviceReport->signature())
\includegraphics[height=2cm]{{!! Latex::escape($serviceReport->signature()->getPath()) !!}}
@endif
\footnotesize{Unterschrift Kunde @if($serviceReport->signature())vom {!! Latex::escape($serviceReport->signature()->created_at) !!} @endif}
\end{minipage}
\\
\end{document}
