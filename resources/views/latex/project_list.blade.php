@include('latex.partials.preamble')

@include('latex.partials.header')

\fancyfoot[L]{\footnotesize{Projektliste, erstellt am {!! Latex::escape(\Carbon\Carbon::today()) !!}}}
\fancyfoot[R]{\footnotesize{Seite \thepage\ von \pageref{LastPage}}}

\renewcommand\TruncateMarker{\textasciitilde}
\def\projecttruncate{\truncate{11.8cm}}
\newcolumntype{A}[1]{ >{\collectcell\projecttruncate}p{#1}<{\endcollectcell} }

\begin{document}
\begin{minipage}{0.07\textwidth}
\qrcode[height=1cm]{ {!! Latex::escape(route('projects.index')) !!} }
\end{minipage}
\begin{minipage}{0.93\textwidth}
\large{\textbf{Projektliste, erstellt am {!! Latex::escape(\Carbon\Carbon::today()) !!}}} \\ \large{\textbf{@if($company){!! Latex::escape($company->name) !!}@else{!! Latex::escape('alle Firmen') !!}@endif}}
\end{minipage}
\\\\\\
@if(count($companies) > 0 && $companies->sum('projects_count') > 0)
@foreach($companies as $company)
@if(count($company->projects) > 0)
\textbf{{!! Latex::escape($company->name) !!}} ({!! Latex::escape($company->projects_count) !!} Projekte)
\begin{longtable}{@{}A{11.8cm}p{2cm}p{2cm}@{}}
\hline
\footnotesize{\textbf{Projekt}} & \footnotesize{\textbf{Start}} & \footnotesize{\textbf{Ende}} \\
\hline
\hline
\endhead
@foreach($company->projects->sortBy('name') as $project)
\footnotesize{{!! Latex::escape($project->name) !!}}
@can('projects.view.estimates')
\begin{ignorelinebreaks}
@if($project->current_costs_percentage)
\tiny{ G}
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
@if($project->current_billedcosts_percentage)
\tiny{ V}
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
@if($project->current_wage_costs_percentage)
\tiny{ L}
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
@if($project->current_material_costs_percentage)
\tiny{ M}
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
\end{ignorelinebreaks}
@endcan
& \footnotesize{{!! Latex::escape($project->starts_on ?? '') !!}} & \footnotesize{{!! Latex::escape($project->ends_on ?? '') !!}} \\
\hline
@endforeach
\end{longtable}
@endif
@endforeach
@can('projects.view.estimates')
@if($projectOverwallCostsWarningPercentage || $projectBilledCostsWarningPercentage || $projectWageCostsWarningPercentage || $projectMaterialCostsWarningPercentage)
\begin{singlespace}
\begin{scriptsize}
\begin{ignorelinebreaks}
Die Pfeile für die Gesamt, verrechneten, Lohn und Materialosten zeigen folgende Information:
\setlist{nosep, topsep=-0.2cm}
\setdescription{font=\normalfont}
\begin{description}[labelwidth=0.3cm]
\item[\textcolor{success}{\textbf{$\downarrow$}}] Die aktuellen Kosten liegen unter der Warnschwelle.
\item[\textcolor{warning}{\textbf{$\searrow$}}] Die aktuellen Kosten liegen zwischen der Warnschwelle und den geschätzten Kosten.
\item[\textcolor{danger}{\textbf{$\uparrow$}}] Die aktuellen Kosten liegen über den geschätzten Kosten.
\end{description}
Warnschwellen:~
@if($projectOverwallCostsWarningPercentage)
Gesamt: {!! Latex::escape($projectOverwallCostsWarningPercentage) !!}{!! Latex::escape('%') !!}
@if($projectBilledCostsWarningPercentage || $projectWageCostsWarningPercentage || $projectMaterialCostsWarningPercentage), @endif
@endif
@if($projectBilledCostsWarningPercentage)
verrechnet: {!! Latex::escape($projectBilledCostsWarningPercentage) !!}{!! Latex::escape('%') !!}
@if($projectWageCostsWarningPercentage || $projectMaterialCostsWarningPercentage), @endif
@endif
@if($projectWageCostsWarningPercentage)
Lohn: {!! Latex::escape($projectWageCostsWarningPercentage) !!}{!! Latex::escape('%') !!}
@if($projectMaterialCostsWarningPercentage), @endif
@endif
@if($projectMaterialCostsWarningPercentage)
Material: {!! Latex::escape($projectMaterialCostsWarningPercentage) !!}{!! Latex::escape('%') !!}
@endif
\end{ignorelinebreaks}
\end{scriptsize}
\end{singlespace}
@endif
@endcan
@else
Es sind keine Projekte passend dem Filter vorhanden.
@endif
\end{document}
