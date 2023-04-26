\documentclass[a4paper]{article}

\usepackage{graphicx}
\usepackage{tikz}
\usepackage{pdfpages}
\usepackage{lastpage}

\begin{document}
\newsavebox{\additions}
\sbox{\additions}{
\begin{tikzpicture}[overlay,remember picture,shift=(current page.south west)]
\node[anchor=south] at (5.25cm, 5cm) {{!! $deliveryNote->signature()->created_at !!}};
\end{tikzpicture}
\begin{tikzpicture}[overlay,remember picture,shift=(current page.south east)]
\node[anchor=south] at (-5.25cm, 5cm) {\includegraphics[height=2cm]{{!! $deliveryNote->signature()->getPath()  !!}};
\end{tikzpicture}
}
\includepdf[pages={1-}, pagecommand={\thispagestyle{empty}\if\thepage\pageref{LastPage}\usebox\additions\fi}]{{!! $deliveryNote->document()->getPath() !!}}
\end{document}
