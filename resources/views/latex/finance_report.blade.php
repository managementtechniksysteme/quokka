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
\footnotesize{Aktuell offene Projekte} & \footnotesize{12.173.12e} & \footnotesize{\textcolor{red}{12.173.12e}} \\
\hline
\footnotesize{Projekte in der Vorphase} & \footnotesize{12.173.12e} & \footnotesize{\textcolor{red}{12.173.12e}} \\
\hline
\end{longtable}
\newpage
\section{Finanzgruppen Übersicht}
\section{Details zu manuellen Finanzgruppen}
\end{document}
