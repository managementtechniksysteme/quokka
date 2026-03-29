@include('latex.partials.preamble')

@include('latex.partials.header')

\fancyfoot[L]{\footnotesize{Projekt Finanzübersicht, erstellt am {!! Latex::escape(\Carbon\Carbon::today()) !!}}}
\fancyfoot[R]{\footnotesize{Seite \thepage\ von \pageref{LastPage}}}

\begin{document}
\begin{minipage}{0.07\textwidth}
\qrcode[height=1cm]{ {!! Latex::escape(route('finances.index')) !!} }
\end{minipage}
\begin{minipage}{0.93\textwidth}
\large{\textbf{Projekt Finanzübersicht\\vom {!! Latex::escape(\Carbon\Carbon::today()) !!}}}
\end{minipage}
\section{Allgemeine Übersicht}
\begin{longtable}{@{}p{9cm}p{2.4cm}p{2.4cm}p{2.4cm}@{}}
\hline
\footnotesize{\textbf{Bezeichnung}} & \footnotesize{\textbf{Auftragsvolumen}} & \footnotesize{\textbf{verrechnet}} & \footnotesize{\textbf{offen}} \\
\hline
\hline
\endhead
\footnotesize{Aktuell offene Projekte} & \footnotesize{{!! Latex::escape(Number::toLocal($report['currentlyOpenProjectsData']['total_volume']) . $currencyUnit) !!}} & \footnotesize{{!! Latex::escape(Number::toLocal($report['currentlyOpenProjectsData']['billed_volume']) . $currencyUnit) !!}} & \footnotesize{@if($report['currentlyOpenProjectsData']['total_volume']+$report['currentlyOpenProjectsData']['billed_volume']>0) \footnotesize{{!! Latex::escape(Number::toLocal($report['currentlyOpenProjectsData']['total_volume']+$report['currentlyOpenProjectsData']['billed_volume']) . $currencyUnit) !!}} @else \footnotesize{\textcolor{red}{{!! Latex::escape(Number::toLocal($report['currentlyOpenProjectsData']['total_volume']+$report['currentlyOpenProjectsData']['billed_volume']) . $currencyUnit) !!}}} @endif} \\
\hline
\footnotesize{Projekte in der Vorphase} & \footnotesize{{!! Latex::escape(Number::toLocal($report['preExecutionProjectsData']['total_volume']) . $currencyUnit) !!}} & \footnotesize{{!! Latex::escape(Number::toLocal($report['preExecutionProjectsData']['billed_volume']) . $currencyUnit) !!}} & \footnotesize{@if($report['preExecutionProjectsData']['total_volume']+$report['preExecutionProjectsData']['billed_volume']>0) \footnotesize{{!! Latex::escape(Number::toLocal($report['preExecutionProjectsData']['total_volume']+$report['preExecutionProjectsData']['billed_volume']) . $currencyUnit) !!}} @else \footnotesize{\textcolor{red}{{!! Latex::escape(Number::toLocal($report['preExecutionProjectsData']['total_volume']+$report['preExecutionProjectsData']['billed_volume']) . $currencyUnit) !!}}} @endif}  \\
\hline
\end{longtable}
\begin{minipage}{.5\textwidth}
\subsection{Aktuell offene Projekte}
\begin{tikzpicture}
\begin{axis}[
axis line style={draw=none},
tick style={draw=none},
ticklabel style={font=\tiny},
ybar,
enlargelimits=0.2,
ymajorgrids=true,
bar width=32pt,
bar shift=0pt,
xtick={1, 2, 3},
xticklabels={Auftragsvolumen, verrechnet, offen},
ylabel near ticks,
yticklabel style={font=\tiny, /pgf/number format/.cd,fixed},
yticklabel pos=left,
scaled y ticks=false,
nodes near coords,
nodes near coords style={font=\scriptsize, /pgf/number format/.cd,fixed},
nodes near coords align={vertical}
]
\addplot[fill=green, draw=none] coordinates {
(1, {!! $report['currentlyOpenProjectsData']['total_volume'] !!})
};
\addplot[fill=red, draw=none] coordinates {
(2, {!! $report['currentlyOpenProjectsData']['billed_volume'] !!})
};
\addplot[@if($report['currentlyOpenProjectsData']['total_volume']+$report['currentlyOpenProjectsData']['billed_volume']>0)fill=green @else fill=red @endif, draw=none] coordinates {
(3, {!! $report['currentlyOpenProjectsData']['total_volume']+$report['currentlyOpenProjectsData']['billed_volume'] !!})
};
\end{axis}
\end{tikzpicture}
\end{minipage}
\begin{minipage}{.5\textwidth}
\subsection{Projekte in der Vorphase}
\begin{tikzpicture}
\begin{axis}[
axis line style={draw=none},
tick style={draw=none},
ticklabel style={font=\tiny},
ybar,
enlargelimits=0.2,
ymajorgrids=true,
bar width=32pt,
bar shift=0pt,
xtick={1, 2, 3},
xticklabels={Auftragsvolumen, verrechnet, offen},
ylabel near ticks,
yticklabel style={font=\tiny, /pgf/number format/.cd,fixed},
yticklabel pos=left,
scaled y ticks=false,
nodes near coords,
nodes near coords style={font=\scriptsize, /pgf/number format/.cd,fixed},
nodes near coords align={vertical}
]
\addplot[fill=green, draw=none] coordinates {
(1, {!! $report['preExecutionProjectsData']['total_volume'] !!})
};
\addplot[fill=red, draw=none] coordinates {
(2, {!! $report['preExecutionProjectsData']['billed_volume'] !!})
};
\addplot[@if($report['preExecutionProjectsData']['total_volume']+$report['preExecutionProjectsData']['billed_volume']>0)fill=green @else fill=red @endif, draw=none] coordinates {
(3, {!! $report['preExecutionProjectsData']['total_volume']+$report['preExecutionProjectsData']['billed_volume'] !!})
};
\end{axis}
\end{tikzpicture}
\end{minipage}
\section{Übersicht einzelner Projekte}
\begin{longtable}{@{}p{9cm}p{2.4cm}p{2.4cm}p{2.4cm}@{}}
\hline
\footnotesize{\textbf{Projekt}} & \footnotesize{\textbf{Auftragsvolumen}} & \footnotesize{\textbf{verrechnet}} & \footnotesize{\textbf{offen}} \\
\hline
\hline
\endhead
@foreach($report['projectData'] as $title => $projectRow)
\footnotesize{{!! Latex::escape($title) !!}} & \footnotesize{{!! Latex::escape(Number::toLocal($projectRow['total_volume']) . $currencyUnit) !!}} & \footnotesize{{!! Latex::escape(Number::toLocal($projectRow['billed_volume']) . $currencyUnit) !!}} & \footnotesize{@if($projectRow['total_volume']+$projectRow['billed_volume']>0) \footnotesize{{!! Latex::escape(Number::toLocal($projectRow['total_volume']+$projectRow['billed_volume']) . $currencyUnit) !!}} @else \footnotesize{\textcolor{red}{{!! Latex::escape(Number::toLocal($projectRow['total_volume']+$projectRow['billed_volume']) . $currencyUnit) !!}}} @endif} \\
\hline
@endforeach
\end{longtable}
\end{document}
