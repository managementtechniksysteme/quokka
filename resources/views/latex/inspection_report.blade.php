@include('latex.partials.preamble')

@include('latex.partials.header')

\fancyfoot[L]{\footnotesize{Prüfbericht vom {!! Latex::escape($inspectionReport->inspected_on) !!}, Anlagen-/Gerätenummer: {!! Latex::escape($inspectionReport->equipment_identifier) !!}, erstellt am {!! Latex::escape(\Carbon\Carbon::today()) !!}}}
\fancyfoot[R]{\footnotesize{Seite \thepage\ von \pageref{LastPage}}}

\begin{document}
\begin{minipage}{0.07\textwidth}
\qrcode[height=1cm]{ {!! Latex::escape(route('inspection-reports.show', $inspectionReport)) !!} }
\end{minipage}
\begin{minipage}{0.93\textwidth}
\large{\textbf{Prüfbericht vom {!! Latex::escape($inspectionReport->inspected_on) !!}}} \\ \large{\textbf{Anlagen-/Gerätenummer: {!! Latex::escape($inspectionReport->equipment_identifier) !!}}}
\end{minipage}
\\\\\\
\begin{tabular}{@{}p{4.05cm}p{4.05cm}p{4.05cm}p{4.05cm}@{}}
\footnotesize{\textbf{Kunde:}} & \multicolumn{3}{l}{\footnotesize{{!! Latex::escape($inspectionReport->project->company->name) !!}}} \\
\footnotesize{\textbf{Techniker:}} & \multicolumn{3}{l}{\footnotesize{{!! Latex::escape($inspectionReport->employee->person->name) !!}}} \\
@if(Auth::check() && $inspectionReport->status === 'finished')
\footnotesize{\textbf{erledigt am:}} & \multicolumn{3}{l}{\footnotesize{{!! Latex::escape($additionsReport->updated_at)!!}}} \\
@endif
\end{tabular}

\begin{tabular}{@{}p{4.05cm}p{4.05cm}p{4.05cm}p{4.05cm}@{}}
\footnotesize{\textbf{Datum:}} & \footnotesize{{!! Latex::escape($inspectionReport->inspected_on) !!}} & \footnotesize{\textbf{Anlagentyp:}} & \footnotesize{{!! Latex::escape($inspectionReport->equipment_type) !!}} \\
\footnotesize{\textbf{Wetter:}} & \footnotesize{{!! Latex::escape(trans($inspectionReport->weather)) !!}} & \footnotesize{\textbf{Anlagen-/Gerätenummer:}} & \footnotesize{{!! Latex::escape($inspectionReport->equipment_identifier) !!}} \\
\end{tabular}

\begin{tabular}{@{}p{4.05cm}p{4.05cm}p{4.05cm}p{4.05cm}@{}}
\multicolumn{2}{@{}l}{\footnotesize{\textbf{UVC Strahler}}} & \multicolumn{2}{l}{\footnotesize{\textbf{UVC Sensor}}} \\
\footnotesize{Anzahl, Typ:} & \footnotesize{{!! Latex::escape(Number::toLocal($inspectionReport->uvc_lamp_quantity)) !!} x {!! Latex::escape($inspectionReport->uvc_lamp_type) !!}} & \footnotesize{Typ:} & \footnotesize{{!! Latex::escape($inspectionReport->uvc_sensor_type) !!}} \\
\footnotesize{Betriebsstunden:} & \footnotesize{{!! Latex::escape(Number::toLocal($inspectionReport->uvc_lamp_operating_hours)) !!}{!! Latex::escape('h') !!}} & \footnotesize{Seriennummer:} & \footnotesize{{!! Latex::escape($inspectionReport->uvc_sensor_identifier) !!}} \\
\footnotesize{Impulse:} & \footnotesize{{!! Latex::escape(Number::toLocal($inspectionReport->uvc_lamp_impulses)) !!}} & \footnotesize{Voralarm:} & \footnotesize{{!! Latex::escape(Number::toLocal($inspectionReport->uvc_sensor_pre_alarm)) !!}{!! Latex::escape($inspectionReport->uvc_sensor_values_unit_string) !!}} \\
\footnotesize{UV Int. bei Ankunft, Abfahrt:} & \footnotesize{{!! Latex::escape(Number::toLocal($inspectionReport->uvc_lamp_uv_intensity_arrival)) !!}{!! Latex::escape($inspectionReport->uvc_lamp_values_unit_string) !!}, {!! Latex::escape(Number::toLocal($inspectionReport->uvc_lamp_uv_intensity_departure)) !!}{!! Latex::escape($inspectionReport->uvc_lamp_values_unit_string) !!}} & \footnotesize{Abschaltpunkt:} & \footnotesize{{!! Latex::escape(Number::toLocal($inspectionReport->uvc_sensor_cut_off_point)) !!}{!! Latex::escape($inspectionReport->uvc_sensor_values_unit_string) !!}} \\
\footnotesize{Ersatzstrahler vorhanden:} & \footnotesize{{!! Latex::escape($inspectionReport->uvc_lamp_replacement_available_string) !!}} & & \\
\end{tabular}

\begin{tabular}{@{}p{4.05cm}p{4.05cm}p{4.05cm}p{4.05cm}@{}}
\multicolumn{2}{@{}l}{\footnotesize{\textbf{Wasser}}} & \multicolumn{2}{l}{\footnotesize{\textbf{Überprüfung der Quarzschutzrohre}}} \\
\footnotesize{Durchfluss:} & \footnotesize{{!! Latex::escape(Number::toLocal($inspectionReport->water_flow_rate)) !!} {!! Latex::escape('m³/h') !!}} & \footnotesize{Verschmutzung:} & \footnotesize{{!! Latex::escape($inspectionReport->quartz_tube_contaminated_string) !!}} \\
\footnotesize{min., gem. Trans.[100mm]:} & \footnotesize{{!! Latex::escape(Number::toLocal($inspectionReport->water_minimum_uv_transmission)) !!}{!! Latex::escape('%') !!}, {!! Latex::escape(Number::toLocal($inspectionReport->water_measured_uv_transmission)) !!}{!! Latex::escape('%') !!}} & \footnotesize{Undicht:} & \footnotesize{{!! Latex::escape($inspectionReport->quartz_tube_leaking_string) !!}} \\
\footnotesize{Schwebestoffe sichtbar:} & \footnotesize{{!! Latex::escape($inspectionReport->water_suspended_load_visible_string) !!}} & & \\
\footnotesize{Luftblasenfrei:} & \footnotesize{{!! Latex::escape($inspectionReport->water_air_bubble_free_string) !!}} & & \\
\end{tabular}
\section{Durchgeführte Arbeiten und Bemerkungen}
\footnotesize{{!! Latex::fromMarkdown($inspectionReport->comment) !!}}
\vfill
\begin{minipage}[t][2.5cm]{0.5\textwidth}
\vfill
\centering
@if($inspectionReport->employee->user->signature())
\includegraphics[height=2cm]{{!! Latex::escape($inspectionReport->employee->user->signature()->getPath()) !!}}
\\
@endif
\footnotesize{Unterschrift Techniker @if($inspectionReport->employee->user->signature())vom {!! Latex::escape($inspectionReport->created_at) !!} @endif}
\end{minipage}
\begin{minipage}[t][2.5cm]{0.5\textwidth}
\vfill
\centering
@if($inspectionReport->signature())
\includegraphics[height=2cm]{{!! Latex::escape($inspectionReport->signature()->getPath()) !!}}
\\
@endif
\footnotesize{Unterschrift Kunde @if($inspectionReport->signature())vom {!! Latex::escape($inspectionReport->signature()->created_at) !!} @endif}
\end{minipage}
\\
\end{document}
