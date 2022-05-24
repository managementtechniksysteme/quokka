\documentclass[a4paper, 11pt]{article}
\usepackage[singlelinecheck=false]{caption}
\usepackage[utf8]{inputenc}
\usepackage[T1]{fontenc}
\usepackage{amssymb}
\usepackage{array}
\usepackage{booktabs}
\usepackage{calc}
\usepackage{color}
\usepackage{etoolbox}
\usepackage{enumitem}
\usepackage{fancyhdr}
\usepackage{fancyvrb}
\usepackage{framed}
\usepackage{geometry}
\usepackage{graphicx}
\usepackage{helvet}
\usepackage{hyperref}
\usepackage{lastpage}
\usepackage{longtable}
\usepackage{multirow}
\usepackage{parskip}
\usepackage{qrcode}
\usepackage{setspace}
\usepackage{textcomp}
\usepackage[compact]{titlesec}
\usepackage[normalem]{ulem}
\usepackage{upquote}
\usepackage{xcolor}
\usepackage{colortbl}
\usepackage[fit, breakall]{truncate}
\usepackage{collcell}

% heading formats
\titleformat{\section}{\large\bfseries}{\thesection}{2em}{}
\titlespacing*{\section}{0ex}{3ex}{1ex}
\titleformat{\subsection}{\normalsize\bfseries}{\thesection}{2em}{}
\titlespacing*{\subsection}{0ex}{2ex}{1ex}
\titleformat{\subsubsection}{\small\bfseries}{\thesection}{2em}{}
\titlespacing*{\subsubsection}{0ex}{2ex}{1ex}
\titleformat{\paragraph}{\footnotesize\bfseries}{\thesection}{2em}{}
\titlespacing*{\paragraph}{0ex}{2ex}{1ex}
\titleformat{\subparagraph}{\footnotesize\bfseries}{\thesection}{2em}{}
\titlespacing*{\subparagraph}{0ex}{2ex}{1ex}

% hide url borders in pdf
\hypersetup{hidelinks}
\urlstyle{same}

% pandoc disable section numbering
\setcounter{secnumdepth}{-\maxdimen}

% pandoc syntax highlighting macros
\newcommand{\VerbBar}{|}
\newcommand{\VERB}{\Verb[commandchars=\\\{\}]}
\DefineVerbatimEnvironment{Highlighting}{Verbatim}{commandchars=\\\{\}}
% Add ',fontsize=\small' for more characters per line
\newenvironment{Shaded}{}{}
\newcommand{\AlertTok}[1]{\textcolor[rgb]{1.00,0.00,0.00}{\textbf{#1}}}
\newcommand{\AnnotationTok}[1]{\textcolor[rgb]{0.38,0.63,0.69}{\textbf{\textit{#1}}}}
\newcommand{\AttributeTok}[1]{\textcolor[rgb]{0.49,0.56,0.16}{#1}}
\newcommand{\BaseNTok}[1]{\textcolor[rgb]{0.25,0.63,0.44}{#1}}
\newcommand{\BuiltInTok}[1]{#1}
\newcommand{\CharTok}[1]{\textcolor[rgb]{0.25,0.44,0.63}{#1}}
\newcommand{\CommentTok}[1]{\textcolor[rgb]{0.38,0.63,0.69}{\textit{#1}}}
\newcommand{\CommentVarTok}[1]{\textcolor[rgb]{0.38,0.63,0.69}{\textbf{\textit{#1}}}}
\newcommand{\ConstantTok}[1]{\textcolor[rgb]{0.53,0.00,0.00}{#1}}
\newcommand{\ControlFlowTok}[1]{\textcolor[rgb]{0.00,0.44,0.13}{\textbf{#1}}}
\newcommand{\DataTypeTok}[1]{\textcolor[rgb]{0.56,0.13,0.00}{#1}}
\newcommand{\DecValTok}[1]{\textcolor[rgb]{0.25,0.63,0.44}{#1}}
\newcommand{\DocumentationTok}[1]{\textcolor[rgb]{0.73,0.13,0.13}{\textit{#1}}}
\newcommand{\ErrorTok}[1]{\textcolor[rgb]{1.00,0.00,0.00}{\textbf{#1}}}
\newcommand{\ExtensionTok}[1]{#1}
\newcommand{\FloatTok}[1]{\textcolor[rgb]{0.25,0.63,0.44}{#1}}
\newcommand{\FunctionTok}[1]{\textcolor[rgb]{0.02,0.16,0.49}{#1}}
\newcommand{\ImportTok}[1]{#1}
\newcommand{\InformationTok}[1]{\textcolor[rgb]{0.38,0.63,0.69}{\textbf{\textit{#1}}}}
\newcommand{\KeywordTok}[1]{\textcolor[rgb]{0.00,0.44,0.13}{\textbf{#1}}}
\newcommand{\NormalTok}[1]{#1}
\newcommand{\OperatorTok}[1]{\textcolor[rgb]{0.40,0.40,0.40}{#1}}
\newcommand{\OtherTok}[1]{\textcolor[rgb]{0.00,0.44,0.13}{#1}}
\newcommand{\PreprocessorTok}[1]{\textcolor[rgb]{0.74,0.48,0.00}{#1}}
\newcommand{\RegionMarkerTok}[1]{#1}
\newcommand{\SpecialCharTok}[1]{\textcolor[rgb]{0.25,0.44,0.63}{#1}}
\newcommand{\SpecialStringTok}[1]{\textcolor[rgb]{0.73,0.40,0.53}{#1}}
\newcommand{\StringTok}[1]{\textcolor[rgb]{0.25,0.44,0.63}{#1}}
\newcommand{\VariableTok}[1]{\textcolor[rgb]{0.10,0.09,0.49}{#1}}
\newcommand{\VerbatimStringTok}[1]{\textcolor[rgb]{0.25,0.44,0.63}{#1}}
\newcommand{\WarningTok}[1]{\textcolor[rgb]{0.38,0.63,0.69}{\textbf{\textit{#1}}}}

% pandoc tighter list styles
\providecommand{\tightlist}{%
\setlength{\itemsep}{0pt}\setlength{\parskip}{0pt}}

% pandoc left align tables
\setlength{\LTleft}{0em}

\renewcommand{\familydefault}{\sfdefault}
\renewcommand{\baselinestretch}{1.00}\normalsize

\geometry{a4paper, left=2.5cm, right=1cm, top=1cm, textheight=23cm, includeheadfoot}

\definecolor{grey}{rgb}{0.5, 0.5, 0.5}

\pagenumbering{arabic}

\onehalfspacing

\pagestyle{fancy}
\fancyhf{}
\renewcommand{\headrulewidth}{0cm}
\renewcommand{\footrulewidth}{0cm}
\headheight 2.6cm
\footskip 1cm
