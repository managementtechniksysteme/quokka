@include('latex.partials.preamble')

@include('latex.partials.header')

\fancyfoot[L]{\footnotesize{Finanzübersicht, erstellt am {!! Latex::escape(\Carbon\Carbon::today()) !!}}}
\fancyfoot[R]{\footnotesize{Seite \thepage\ von \pageref{LastPage}}}

\begin{document}
\begin{minipage}{0.07\textwidth}
\qrcode[height=1cm]{ {!! Latex::escape(route('finances.index')) !!} }
\end{minipage}
\begin{minipage}{0.93\textwidth}
\large{\textbf{Finanzübersicht\\vom {!! Latex::escape(\Carbon\Carbon::today()) !!}}}
\end{minipage}
\section{Allgemeine Übersicht}
\begin{longtable}{@{}p{9cm}p{2cm}p{2cm}p{2cm}@{}}
\hline
\footnotesize{\textbf{Bezeichnung}} & \footnotesize{\textbf{Einnahmen}} & \footnotesize{\textbf{Ausgaben}} & \footnotesize{\textbf{Differenz}} \\
\hline
\hline
\endhead
\footnotesize{Aktuell offene Projekte} & \footnotesize{{!! Latex::escape(Number::toLocal($report['currentlyOpenProjectsData']['revenue']) . $currencyUnit) !!}} & \footnotesize{{!! Latex::escape(Number::toLocal($report['currentlyOpenProjectsData']['expense']) . $currencyUnit) !!}} & \footnotesize{@if($report['currentlyOpenProjectsData']['revenue']+$report['currentlyOpenProjectsData']['expense']>0) \footnotesize{{!! Latex::escape(Number::toLocal($report['currentlyOpenProjectsData']['revenue']+$report['currentlyOpenProjectsData']['expense'])) . $currencyUnit) !!}} @else \footnotesize{\textcolor{red}{{!! Latex::escape(Number::toLocal($report['currentlyOpenProjectsData']['revenue']+$report['currentlyOpenProjectsData']['expense'])) . $currencyUnit) !!}}} @endif} \\
\hline
\footnotesize{Projekte in der Vorphase} & \footnotesize{{!! Latex::escape(Number::toLocal($report['preExecutionProjectsData']['revenue']) . $currencyUnit) !!}} & \footnotesize{{!! Latex::escape(Number::toLocal($report['preExecutionProjectsData']['expense']) . $currencyUnit) !!}} & \footnotesize{@if($report['preExecutionProjectsData']['revenue']+$report['preExecutionProjectsData']['expense']>0) \footnotesize{{!! Latex::escape(Number::toLocal($report['preExecutionProjectsData']['revenue']+$report['preExecutionProjectsData']['expense'])) . $currencyUnit) !!}} @else \footnotesize{\textcolor{red}{{!! Latex::escape(Number::toLocal($report['preExecutionProjectsData']['revenue']+$report['preExecutionProjectsData']['expense'])) . $currencyUnit) !!}}} @endif}  \\
\hline
\end{longtable}
\newpage
\section{Finanzgruppen Übersicht}
\begin{longtable}{@{}p{9cm}p{2cm}p{2cm}p{2cm}@{}}
\hline
\footnotesize{\textbf{Finanzgruppe}} & \footnotesize{\textbf{Einnahmen}} & \footnotesize{\textbf{Ausgaben}} & \footnotesize{\textbf{Differenz}} \\
\hline
\hline
\endhead
@foreach($data['groupData'] as $title => $groupRow)
\footnotesize{{!! Latex::escape($title) !!}} & \footnotesize{{!! Latex::escape(Number::toLocal($groupRow['revenue']) . $currencyUnit) !!}} & \footnotesize{{!! Latex::escape(Number::toLocal($groupRow['expense']) . $currencyUnit) !!}} & \footnotesize{@if($groupRow['revenue']+$groupRow['expense']>0) \footnotesize{{!! Latex::escape(Number::toLocal($groupRow['revenue']+$groupRow['expense'])) . $currencyUnit) !!}} @else \footnotesize{\textcolor{red}{{!! Latex::escape(Number::toLocal($groupRow['revenue']+$groupRow['expense'])) . $currencyUnit) !!}}} @endif} \\
\hline
@endforeach
\end{longtable}
\section{Details zu manuellen Finanzgruppen}
@foreach($data['manualGroupData'] as $groupTitle => $group)
\subsection{{!! Latex::escape($groupTitle) !!}}



\begin{longtable}{@{}p{2cm}p{11cm}p{2cm}@{}}
\hline
\footnotesize{\textbf{Datum}} & \footnotesize{\textbf{Titel}} & \footnotesize{\textbf{Summe}} \\
\hline
\hline
\endhead
@foreach($group as $groupRow)
\footnotesize{{!! Latex::escape(\Carbon\Carbon::parse($groupRow['billed_on'])->translatedFormat('d.m.Y')) !!}} & \footnotesize{{!! Latex::escape($groupRow['title']) !!}} & \footnotesize{@if($groupRow['amount']>=0) \footnotesize{{!! Latex::escape(Number::toLocal($groupRow['amount'])) . $currencyUnit) !!}} @else \footnotesize{\textcolor{red}{{!! Latex::escape(Number::toLocal($groupRow['amount'])) . $currencyUnit) !!}}} @endif} \\
\hline
@endforeach
\end{longtable}


@endforeach
\end{document}
