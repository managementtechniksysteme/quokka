@include('latex.partials.preamble')

@include('latex.partials.header')

\fancyfoot[L]{\footnotesize{Aufgabenliste}@if($company)\footnotesize{ ({!! Latex::escape($company->name) !!})}@elseif($project)\footnotesize{ ({!! Latex::escape($project->name) !!})}@endif\footnotesize{, erstellt am {!! Latex::escape(\Carbon\Carbon::today()) !!}}}
\fancyfoot[R]{\footnotesize{Seite \thepage\ von \pageref{LastPage}}}

\renewcommand\TruncateMarker{\textasciitilde}
\def\tasktruncate{\truncate{10.1cm}}
\newcolumntype{A}[1]{ >{\collectcell\tasktruncate}p{#1}<{\endcollectcell} }

\begin{document}
\begin{minipage}{0.07\textwidth}
\qrcode[height=1cm]{ {!! Latex::escape(route('tasks.index')) !!} }
\end{minipage}
\begin{minipage}{0.93\textwidth}
\large{\textbf{Aufgabenliste, erstellt am {!! Latex::escape(\Carbon\Carbon::today()) !!}}} \\ \large{\textbf{@if($company){!! Latex::escape('Firma ') !!}{!! Latex::escape($company->name) !!}@elseif($project){!! Latex::escape('Projekt ') !!}{!! Latex::escape($project->name) !!}@else{!! Latex::escape('alle Projekte') !!}@endif}}
\end{minipage}
\\\\\\
@if(count($companies) > 0 && $companies->sum('projects_count') > 0 && $totalTasks > 0)
@foreach($companies as $company)
@if(count($company->projects) > 0 && $company->projects->sum('tasks_count') > 0)
@foreach($company->projects as $project)
@if(count($project->tasks) > 0)
\textbf{{!! Latex::escape($company->name) !!} - {!! Latex::escape($project->name) !!}} ({!! Latex::escape(trans_choice('messages.projects', $project->tasks_count)) !!})
\begin{longtable}{@{}A{10.1cm}p{0.6cm}p{2.1cm}p{1cm}p{2cm}@{}}
\hline
\footnotesize{\textbf{Aufgabe}} & \footnotesize{\textbf{MA}} & \footnotesize{\textbf{Status}} & \footnotesize{\textbf{Priorität}} & \footnotesize{\textbf{fällig am}} \\
\hline
\hline
\endhead
@foreach($project->tasks as $task)
\footnotesize{{!! Latex::escape($task->name) !!}} & \footnotesize{{!! Latex::escape(Str::upper($task->responsibleEmployee->user->username)) !!}} & \footnotesize{{!! Latex::escape(trans($task->status)) !!}} & \footnotesize{{!! Latex::escape(trans($task->priority)) !!}} & \footnotesize{{!! Latex::escape($task->due_on ?? '') !!}} \\
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
