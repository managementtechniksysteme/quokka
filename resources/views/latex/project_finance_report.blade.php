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
\footnotesize{\textbf{Bezeichnung}} & \footnotesize{\textbf{Einnahmen}} & \footnotesize{\textbf{Ausgaben}} & \footnotesize{\textbf{Differenz}} \\
\hline
\hline
\endhead
\footnotesize{Aktuell offene Projekte} & \footnotesize{{!! Latex::escape(Number::toLocal($report['currentlyOpenProjectsData']['revenue']) . $currencyUnit) !!}} & \footnotesize{{!! Latex::escape(Number::toLocal($report['currentlyOpenProjectsData']['expense']) . $currencyUnit) !!}} & \footnotesize{@if($report['currentlyOpenProjectsData']['revenue']+$report['currentlyOpenProjectsData']['expense']>0) \footnotesize{{!! Latex::escape(Number::toLocal($report['currentlyOpenProjectsData']['revenue']+$report['currentlyOpenProjectsData']['expense']) . $currencyUnit) !!}} @else \footnotesize{\textcolor{red}{{!! Latex::escape(Number::toLocal($report['currentlyOpenProjectsData']['revenue']+$report['currentlyOpenProjectsData']['expense']) . $currencyUnit) !!}}} @endif} \\
\hline
\footnotesize{Projekte in der Vorphase} & \footnotesize{{!! Latex::escape(Number::toLocal($report['preExecutionProjectsData']['revenue']) . $currencyUnit) !!}} & \footnotesize{{!! Latex::escape(Number::toLocal($report['preExecutionProjectsData']['expense']) . $currencyUnit) !!}} & \footnotesize{@if($report['preExecutionProjectsData']['revenue']+$report['preExecutionProjectsData']['expense']>0) \footnotesize{{!! Latex::escape(Number::toLocal($report['preExecutionProjectsData']['revenue']+$report['preExecutionProjectsData']['expense']) . $currencyUnit) !!}} @else \footnotesize{\textcolor{red}{{!! Latex::escape(Number::toLocal($report['preExecutionProjectsData']['revenue']+$report['preExecutionProjectsData']['expense']) . $currencyUnit) !!}}} @endif}  \\
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
xticklabels={Einnahmen, Ausgaben, Differenz},
ylabel near ticks,
yticklabel style={font=\tiny, /pgf/number format/.cd,fixed},
yticklabel pos=left,
scaled y ticks=false,
nodes near coords,
nodes near coords style={font=\scriptsize, /pgf/number format/.cd,fixed},
nodes near coords align={vertical}
]
\addplot[fill=green, draw=none] coordinates {
(1, {!! $report['currentlyOpenProjectsData']['revenue'] !!})
};
\addplot[fill=red, draw=none] coordinates {
(2, {!! $report['currentlyOpenProjectsData']['expense'] !!})
};
\addplot[@if($report['currentlyOpenProjectsData']['revenue']+$report['currentlyOpenProjectsData']['expense']>0)fill=green @else fill=red @endif, draw=none] coordinates {
(3, {!! $report['currentlyOpenProjectsData']['revenue']+$report['currentlyOpenProjectsData']['expense'] !!})
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
xticklabels={Einnahmen, Ausgaben, Differenz},
ylabel near ticks,
yticklabel style={font=\tiny, /pgf/number format/.cd,fixed},
yticklabel pos=left,
scaled y ticks=false,
nodes near coords,
nodes near coords style={font=\scriptsize, /pgf/number format/.cd,fixed},
nodes near coords align={vertical}
]
\addplot[fill=green, draw=none] coordinates {
(1, {!! $report['preExecutionProjectsData']['revenue'] !!})
};
\addplot[fill=red, draw=none] coordinates {
(2, {!! $report['preExecutionProjectsData']['expense'] !!})
};
\addplot[@if($report['preExecutionProjectsData']['revenue']+$report['preExecutionProjectsData']['expense']>0)fill=green @else fill=red @endif, draw=none] coordinates {
(3, {!! $report['preExecutionProjectsData']['revenue']+$report['preExecutionProjectsData']['expense'] !!})
};
\end{axis}
\end{tikzpicture}
\end{minipage}
\section{Übersicht einzelner Projekte}
\begin{longtable}{@{}p{9cm}p{2.4cm}p{2.4cm}p{2.4cm}@{}}
\hline
\footnotesize{\textbf{Finanzgruppe}} & \footnotesize{\textbf{Einnahmen}} & \footnotesize{\textbf{Ausgaben}} & \footnotesize{\textbf{Differenz}} \\
\hline
\hline
\endhead
@foreach($report['projectData'] as $title => $projectRow)
\footnotesize{{!! Latex::escape($title) !!}} & \footnotesize{{!! Latex::escape(Number::toLocal($projectRow['revenue']) . $currencyUnit) !!}} & \footnotesize{{!! Latex::escape(Number::toLocal($projectRow['expense']) . $currencyUnit) !!}} & \footnotesize{@if($projectRow['revenue']+$projectRow['expense']>0) \footnotesize{{!! Latex::escape(Number::toLocal($projectRow['revenue']+$projectRow['expense']) . $currencyUnit) !!}} @else \footnotesize{\textcolor{red}{{!! Latex::escape(Number::toLocal($projectRow['revenue']+$projectRow['expense']) . $currencyUnit) !!}}} @endif} \\
\hline
@endforeach
\end{longtable}
\end{document}
