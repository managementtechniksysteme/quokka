@include('latex.partials.preamble')

@include('latex.partials.header')

\fancyfoot[L]{\footnotesize{Serviceberichtliste}@if($company)\footnotesize{ ({!! Latex::escape($company->name) !!})}@elseif($project)\footnotesize{ ({!! Latex::escape($project->name) !!})}@endif\footnotesize{, erstellt am {!! Latex::escape(\Carbon\Carbon::today()) !!}}}
\fancyfoot[R]{\footnotesize{Seite \thepage\ von \pageref{LastPage}}}

\begin{document}
\begin{minipage}{0.07\textwidth}
\qrcode[height=1cm]{ {!! Latex::escape(route('service-reports.index')) !!} }
\end{minipage}
\begin{minipage}{0.93\textwidth}
\large{\textbf{Serviceberichtliste, erstellt am {!! Latex::escape(\Carbon\Carbon::today()) !!}}} \\ \large{\textbf{@if($company){!! Latex::escape('Firma ') !!}{!! Latex::escape($company->name) !!}@elseif($project){!! Latex::escape('Projekt ') !!}{!! Latex::escape($project->name) !!}@else{!! Latex::escape('alle Projekte') !!}@endif}}
\end{minipage}
\\\\\\
@if(count($companies) > 0 && $companies->sum('projects_count') > 0 && $totalServiceReports > 0)
@foreach($companies as $company)
@if(count($company->projects) > 0 && $company->projects->sum('service_reports_count') > 0)
@foreach($company->projects as $project)
@if(count($project->serviceReports) > 0)
\textbf{{!! Latex::escape($company->name) !!} - {!! Latex::escape($project->name) !!}} ({!! Latex::escape(trans_choice('messages.service_reports', $project->service_reports_count)) !!})
\begin{longtable}{@{}p{2cm}p{2.4cm}p{2.4cm}p{2.2cm}p{2.2cm}p{1.4cm}p{2.4cm}@{}}
\hline
\footnotesize{\textbf{Nummer}} & \footnotesize{\textbf{Start}} & \footnotesize{\textbf{Ende}} & \footnotesize{\textbf{Stunden}} & \footnotesize{\textbf{gef. KM}} & \footnotesize{\textbf{MA}} & \footnotesize{\textbf{Status}} \\
\hline
\hline
\endhead
@foreach($project->serviceReports as $serviceReport)
\footnotesize{{!! Latex::escape($serviceReport->number) !!}} & \footnotesize{{!! Latex::escape($serviceReport->services_min_provided_on ? \Carbon\Carbon::parse($serviceReport->services_min_provided_on) : '') !!}} & \footnotesize{{!! Latex::escape($serviceReport->services_max_provided_on ? \Carbon\Carbon::parse($serviceReport->services_max_provided_on) : '') !!}} & \footnotesize{{!! Latex::escape($serviceReport->services_sum_hours ?? '') !!}} & \footnotesize{{!! Latex::escape($serviceReport->services_sum_kilometres ?? '') !!}} & \footnotesize{{!! Latex::escape(Str::upper($serviceReport->employee->user->username)) !!}} & \footnotesize{{!! Latex::escape(trans($serviceReport->status)) !!}} \\
\hline
@endforeach
\end{longtable}
@endif
@endforeach
@endif
@endforeach
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
Es sind keine Aufgaben passend dem Filter vorhanden.
@endif
\end{document}
