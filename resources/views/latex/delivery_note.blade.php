\documentclass[a4paper]{article}

\usepackage{graphicx}
\usepackage{tikz}
\usepackage{pdfpages}

\newcount\totalpages

\begin{document}

\pdfximage{{!! $deliveryNote->document()->getPath() !!}}
\totalpages=\pdflastximagepages

\newsavebox{\additions}
\sbox{\additions}{
\begin{tikzpicture}[overlay,remember picture,shift=(current page.south west)]
\node[anchor=south] at (5.25cm, 5cm) {{!! $deliveryNote->signature()->created_at !!}};
\end{tikzpicture}
\begin{tikzpicture}[overlay,remember picture,shift=(current page.south east)]
\node[anchor=south] at (-5.25cm, 5cm) {\includegraphics[height=2cm]{{!! $deliveryNote->signature()->getPath()  !!}}};
\end{tikzpicture}
}

\ifnum\totalpages>1
\includepdf[pages={1-\the\numexpr\totalpages-1\relax}, pagecommand={\thispagestyle{empty}}]{{!! $deliveryNote->document()->getPath() !!}}
\fi
\includepdf[pages={\the\totalpages}, pagecommand={\thispagestyle{empty}\usebox\additions}]{{!! $deliveryNote->document()->getPath() !!}}

\end{document}
