@include('latex.partials.preamble')

@include('latex.partials.header')

\titleformat{\section}{\large\bfseries}{\thesection}{2em}{\uuline}
\titleformat{\subsection}{\normalsize\bfseries}{\thesection}{2em}{\underline}

\fancyfoot[L]{\footnotesize{Prüfbericht vom {!! Latex::escape($flowMeterInspectionReport->inspected_on) !!}, Anlage: {!! Latex::escape($flowMeterInspectionReport->equipment_identifier) !!}, {!! Latex::escape($flowMeterInspectionReport->measuring_point) !!}, erstellt am {!! Latex::escape(\Carbon\Carbon::today()) !!}}}
\fancyfoot[R]{\footnotesize{Seite \thepage\ von \pageref{LastPage}}}

\begin{document}

\begin{titlepage}
\begin{center}
\begin{figure}
\centering
\includegraphics[width=0.5\textwidth]{{!! base_path() !!}/resources/views/latex/images/mts_logo.png}
\end{figure}
\vspace*{1cm}
\huge{\textbf{Prüfbericht für Durchflussmesseinrichtungen in geschlossenen Profilen}}
\vfill
\large{{!! Latex::escape($flowMeterInspectionReport->equipment_identifier) !!}, {!! Latex::escape($flowMeterInspectionReport->measuring_point) !!}} \\
\vspace*{0.5cm}
\large{{!! Latex::escape($flowMeterInspectionReport->project->company->name) !!}} \\
\vspace*{0.5cm}
\large{{!! Latex::escape($flowMeterInspectionReport->project->name) !!}}
\end{center}
\end{titlepage}

\begin{minipage}{0.07\textwidth}
\qrcode[height=1cm]{ {!! Latex::escape(route('flow-meter-inspection-reports.show', $flowMeterInspectionReport)) !!} }
\end{minipage}
\begin{minipage}{0.93\textwidth}
\large{\textbf{Prüfbericht für Durchflussmesseinrichtungen in geschlossenen Profilen}} \\ \large{\textbf{Anlage: {!! Latex::escape($flowMeterInspectionReport->equipment_identifier) !!}, {!! Latex::escape($flowMeterInspectionReport->measuring_point) !!}}}
\end{minipage}
\\\\\\
\section{Allgemeine Angaben zur Überprüfung}

\subsection{Prüfstelle}

\begin{tabular}{@{}p{8.1cm}p{8.1cm}@{}}
\footnotesize{\textbf{Name:}} & \footnotesize{{!! Latex::escape($company->name) !!}} \\
\footnotesize{\textbf{Adresse:}} & \footnotesize{{!! Latex::escape($company->address->first()->street_number) !!}} \\
\footnotesize{\textbf{PLZ, Ort:}} & \footnotesize{{!! Latex::escape($company->address->first()->postcode) !!} {!! Latex::escape($company->address->first()->city) !!}} \\
\footnotesize{\textbf{Telefon:}} & \footnotesize{{!! Latex::escape($company->phone) !!}} \\
\footnotesize{\textbf{Email:}} & \footnotesize{{!! Latex::escape($company->email) !!}} \\
\footnotesize{\textbf{Sachbearbeiter:}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->employee->person->name) !!}} \\
\end{tabular}

\subsection{Angaben zum Auftrag}

\begin{tabular}{@{}p{8.1cm}p{8.1cm}@{}}
\footnotesize{\textbf{Projekt:}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->project->name) !!}} \\
@if(Auth::check() && $flowMeterInspectionReport->status === 'finished')
\footnotesize{\textbf{\textcolor{success}{erledigt am:}}} & \footnotesize{\textcolor{success}{{!! Latex::escape($flowMeterInspectionReport->updated_at)!!}@if($flowMeterInspectionReport->activities->last()) ({!! Latex::escape(Str::upper($flowMeterInspectionReport->activities->last()->causer->username)) !!})@endif}} \\
@endif
\end{tabular}

\subsection{Auftraggeber}

\begin{tabular}{@{}p{8.1cm}p{8.1cm}@{}}
\footnotesize{\textbf{Name:}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->project->company->name) !!}} \\
\footnotesize{\textbf{Adresse:}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->project->company->address->first()->street_number) !!}} \\
\footnotesize{\textbf{PLZ, Ort:}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->project->company->address->first()->postcode) !!} {!! Latex::escape($company->address->first()->city) !!}} \\
\footnotesize{\textbf{Telefon:}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->project->company->phone ?? '') !!}} \\
\footnotesize{\textbf{Email:}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->project->company->email ?? '') !!}} \\
\end{tabular}

\subsection{Zu überprüfende Anlage}

\begin{tabular}{@{}p{8.1cm}p{8.1cm}@{}}
\footnotesize{\textbf{Anlage:}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->equipment_identifier) !!}} \\
\footnotesize{\textbf{Bereich 1:}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->area_1 ?? '') !!}} \\
\footnotesize{\textbf{Bereich 2:}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->area_2 ?? '') !!}} \\
\footnotesize{\textbf{Bereich 3:}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->area_3 ?? '') !!}} \\
\footnotesize{\textbf{Bezeichnung der Messstelle:}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->measuring_point) !!}} \\
\footnotesize{\textbf{Datum der Überprüfung:}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->inspected_on) !!}} \\
\footnotesize{\textbf{Ausbaugröße (Bemessungswert):}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->treatment_plant_size ?? '') !!} {!! $flowMeterInspectionReport->treatment_plant_size ? 'EW\textsubscript{60}' : '' !!}} \\
\end{tabular}

\section{Angaben zur stationären Messeinrichtung}

\subsection{Beschreibung der Messstelle}

\begin{tabular}{@{}p{8.1cm}p{8.1cm}@{}}
\footnotesize{\textbf{Bezeichnung der Messstelle:}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->measuring_point) !!}} \\
\footnotesize{\textbf{Einbauort:}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->installation_point) !!}} \\
\footnotesize{\textbf{Medium:}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->medium) !!}} \\
\footnotesize{\textbf{Jahr der Inbetriebnahme:}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->commissioning_year ?? '') !!}} \\
\footnotesize{\textbf{Zuständiger Mitarbeiter des Betriebspersonals für die Messeinrichtung:}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->responsible_person) !!}} \\
\footnotesize{\textbf{eingeschult am:}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->responsible_person_instructed_on) !!}} \\
\footnotesize{\textbf{eingeschult durch:}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->instructor) !!}} \\
\footnotesize{\textbf{Auskunft gebende Mitarbeiter:}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->information_providing_people ?? '') !!}} \\
\footnotesize{\textbf{Wetter:}} & \footnotesize{{!! Latex::escape(trans($flowMeterInspectionReport->weather)) !!} ({!! Latex::escape(trans($flowMeterInspectionReport->temperature)) !!}\textdegree{}C)} \\
\footnotesize{\textbf{Datum der letzten Vollprüfung:}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->last_inspected_on ?? '') !!}} \\
\footnotesize{\textbf{durchgeführt von (Prüfstelle):}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->last_inspected_by ?? '') !!}} \\
\footnotesize{\textbf{Projektnummer/Prüfnummer:}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->last_inspection_project ?? '') !!}} \\
\end{tabular}

\subsection{Beschreibung der stationären Messeinrichtung}

\begin{tabular}{@{}p{8.1cm}p{8.1cm}@{}}
\footnotesize{\textbf{Auśendurchmesser des Profils:}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->profile_outer_diameter) !!} {!! Latex::escape('mm') !!}} \\
\footnotesize{\textbf{Wandstärke des Profils:}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->profile_wall_thickness) !!} {!! Latex::escape('mm') !!}} \\
\footnotesize{\textbf{Material des Profils:}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->profile_material) !!}} \\
\footnotesize{\textbf{Ohne/mit Querschnittsverengung:}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->without_cross_section_reduction_string) !!}} \\
\footnotesize{\textbf{vollgefüllt/teilgefüllt:}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->fully_filled_string) !!}} \\
\footnotesize{\textbf{Messart:}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->speed_measurement_type === 'other' ? $flowMeterInspectionReport->speed_measurement_type_other : trans($flowMeterInspectionReport->speed_measurement_type)) !!}} \\
\footnotesize{\textbf{Art der Wasserstandsmessung (bei teilgefüllten Messstrecken):}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->water_level_measurement_type ?? '') !!}} \\
\end{tabular}

\section{Funktionskontrolle Bauwerk}

\begin{tabular}{@{}p{8.1cm}p{8.1cm}@{}}
\footnotesize{\textbf{Veränderungen am Messsystem:}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->equipment_changes ?? '') !!}} \\
\footnotesize{\textbf{Ist eine Dokumentation über Messstelle, Mess- und Auswertegeräte vorhanden?}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->documentation_existent_string) !!}} \\
\footnotesize{\textbf{Ist ein Prüfbuch vorhanden?}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->inspection_book_existent_string) !!}} \\
\footnotesize{\textbf{Ist eine Wartungsvorschrift vorhanden?}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->inspection_requirements_existent_string) !!}} \\
\footnotesize{\textbf{Stimmen die Einbaubedingungen mit der Dokumentation überein?}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->documentation_current_string) !!}} \\
\footnotesize{\textbf{Beschreibung der vorgenommenen Änderungen gegenüber der Dokumentation:}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->equipment_changes_to_documentation ?? '') !!}} \\
\footnotesize{\textbf{Fabrikat des Messrohrs:}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->measuring_pipe_type ?? '') !!}} \\
\footnotesize{\textbf{Mindestgeschwindigkeit des Messrohrs:}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->measuring_pipe_minimum_speed ? $flowMeterInspectionReport->measuring_pipe_minimum_speed . '' . $flowMeterInspectionReport->measuring_pipe_minimum_speed_unit_string : '' ) !!}} \\
\footnotesize{\textbf{Messbereich des Messrohrs laut Herstellerangabe 100\%:}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->measuring_pipe_maximum_flow_rate ? $flowMeterInspectionReport->measuring_pipe_maximum_flow_rate . '' . $flowMeterInspectionReport->measuring_pipe_maximum_flow_rate_unit_string . ' bei ' . $flowMeterInspectionReport->measuring_pipe_maximum_speed . '' . $flowMeterInspectionReport->measuring_pipe_maximum_speed_unit_string : '') !!}} \\
\footnotesize{\textbf{Einstellung der Schleimmengenunderdrückung:}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->mucus_suppression ?? '') !!}{!! Latex::escape($flowMeterInspectionReport->mucus_suppression ? trans($flowMeterInspectionReport->mucus_suppression_unit) : '') !!}} \\
\footnotesize{\textbf{Tatsächlicher im Betrieb beobachteter Durchflussbereich (laut Betriebsprotokollen seit der letzten Überprüfung):}} & \footnotesize{Q\textsubscript{min}(5\% Wert): {!! Latex::escape($flowMeterInspectionReport->q_min ?? '') !!}{!! Latex::escape('l/s') !!}, Q\textsubscript{max}(95\% Wert): {!! Latex::escape($flowMeterInspectionReport->q_max ?? '') !!}{!! Latex::escape('l/s') !!}} \\
\footnotesize{\textbf{Ermittlung der Werte:}} & \footnotesize{{!! Latex::escape(trans($flowMeterInspectionReport->flow_range_type)) !!}} \\
\end{tabular}

\section{Messsystem}

\subsection{Messwertaufnehmer Wasserstand}
\begin{tabular}{@{}p{8.1cm}p{8.1cm}@{}}
\footnotesize{\textbf{System:}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->water_level_meter ?? '') !!}} \\
\footnotesize{\textbf{Fabrikat:}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->water_level_meter_make ?? '') !!}} \\
\footnotesize{\textbf{Type:}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->water_level_meter_type ?? '') !!}} \\
\footnotesize{\textbf{Seriennummer:}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->water_level_meter_identifier ?? '') !!}} \\
\end{tabular}

\subsection{Messwertaufnehmer Fließgeschwindigkeit}
\begin{tabular}{@{}p{8.1cm}p{8.1cm}@{}}
\footnotesize{\textbf{System:}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->flow_rate_meter) !!}} \\
\footnotesize{\textbf{Fabrikat:}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->flow_rate_meter_make) !!}} \\
\footnotesize{\textbf{Type:}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->flow_rate_meter_type) !!}} \\
\footnotesize{\textbf{Seriennummer:}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->flow_rate_meter_identifier) !!}} \\
\end{tabular}

\subsection{Messwertumformer}
\begin{tabular}{@{}p{8.1cm}p{8.1cm}@{}}
\footnotesize{\textbf{Vor Ort oder in der Warte:}} & \footnotesize{{!! Latex::escape(trans($flowMeterInspectionReport->measurement_transformer_point)) !!}} \\
\footnotesize{\textbf{Fabrikat:}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->measurement_transformer_make) !!}} \\
\footnotesize{\textbf{Type:}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->measurement_transformer_type) !!}} \\
\footnotesize{\textbf{Seriennummer:}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->measurement_transformer_identifier) !!}} \\
\footnotesize{\textbf{Signalausgang:}} & \footnotesize{von {!! Latex::escape($flowMeterInspectionReport->measurement_transformer_level_unit === 'interface' ? trans($flowMeterInspectionReport->measurement_transformer_level_unit) : 'von ' . $flowMeterInspectionReport->measurement_transformer_minimum_level . ' bis ' . $flowMeterInspectionReport->measurement_transformer_maximum_level . '' . $flowMeterInspectionReport->measurement_transformer_level_unit_string) !!}} \\
\footnotesize{\textbf{Programmierbarer Messbereich 100\%:}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->measurement_transformer_range_100_percent) !!} {!! Latex::escape('l/s') !!}} \\
\footnotesize{\textbf{Impulsausgang:}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->measurement_transformer_impulses) !!} {!! Latex::escape('Impulse/m³') !!}} \\
\footnotesize{\textbf{Die Aufzeichnung der Durchflusssummen und Momentanwerte, die für die Betriebsprotokolle verwendet wird, erfolg durch:}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->measurement_transformer_data_logging) !!}} \\
\end{tabular}

\subsection{Bestandsaufnahme Oberwasserseite}

\begin{tabular}{@{}p{8.1cm}p{8.1cm}@{}}
\footnotesize{\textbf{Rohrdurchmesser innen:}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->headwater_pipe_diameter) !!} {!! Latex::escape('mm') !!}} \\
\footnotesize{\textbf{Länge der einlaufseitigen Beruhigungsstrecke:}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->headwater_calming_section) !!}} \\
\footnotesize{\textbf{Beurteilung der Beruhigungsstrecke:}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->headwater_calming_section_assessment) !!}} \\
\end{tabular}

\subsection{Bestandsaufnahme Messstrecke}

\begin{tabular}{@{}p{8.1cm}p{8.1cm}@{}}
\footnotesize{\textbf{Gefälle der Messstrecke:}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->measurement_section_slope ? $flowMeterInspectionReport->measurement_section_slope.'\textperthousand' : '') !!}} \\
\footnotesize{\textbf{Vermessung durchgeführt mittels:}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->measurement_section_slope_assessment_type ?? '') !!}} \\
\footnotesize{\textbf{Einbaubedingungen laut Hersteller erfüllt?}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->measurement_section_installation_according_to_manufacturer_string) !!}} \\
\footnotesize{\textbf{Unterschreitung der Mindestgeschwindigkeit unterhalb von:}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->measurement_section_minimum_speed_undercut_point ? $flowMeterInspectionReport->measurement_section_minimum_speed_undercut_point . ' l/s' : '') !!}} \\
\end{tabular}

\subsection{Beurteilung der Messstrecke (im eingebauten Zustand)}

\begin{tabular}{@{}p{8.1cm}p{8.1cm}@{}}
\footnotesize{\textbf{Querschnitt des Messrohrs innen:}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->measurement_section_pipe_diameter) !!} {!! Latex::escape('mm') !!}} \\
\footnotesize{\textbf{Ist die Zugänglichkeit gegeben?}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->measurement_section_access_possible_string) !!}} \\
\footnotesize{\textbf{Ist im Messrohr bei den Durchflüssen der Vergleichsmessung die geforderte Fließtiefe (Füllhöhe) vorhanden?}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->measurement_section_pipe_required_fill_level_existent_string) !!}} \\
\footnotesize{\textbf{Ist das Messrohr innen einer optischen Kontrolle zugänglich?}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->measurement_section_pipe_visible_inspection_inside_possible_string) !!}} \\
\footnotesize{\textbf{Wenn nein, wie?}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->measurement_section_pipe_visible_inspection_inside ?? '') !!}} \\
\footnotesize{\textbf{Sind Ablagerungen, Verschmutzungen im Messrohr/am Messwertaufnehmer ersichtlich?}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->measurement_section_pipe_contaminated_string) !!}} \\
\footnotesize{\textbf{Ist eine Reinigung des Messrohrs möglich?}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->measurement_section_pipe_cleaning_possible_string) !!}} \\
\footnotesize{\textbf{Letzte vorgenommene Reinigung des Messrohrs:}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->measurement_section_pipe_last_cleaned_on ?? '') !!}} \\
\footnotesize{\textbf{Ist der Messwertaufnehmer gereinigt?}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->measurement_section_sensor_cleaned_string) !!}} \\
\footnotesize{\textbf{Ist der Messwertaufnehmer mechanisch beschädigt?}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->measurement_section_sensor_damaged_string) !!}} \\
\footnotesize{\textbf{Ist die innere Oberfläche in Ordnung?}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->measurement_section_pipe_inside_surface_ok_string) !!}} \\
\footnotesize{\textbf{Ist eine Erdung des Messrohrs gegeben?}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->measurement_section_pipe_grounding_existent_string) !!}} \\
\footnotesize{\textbf{Sind Lufteinschlüsse erkennbar?}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->measurement_section_pipe_air_pockets_visible_string) !!}} \\
\end{tabular}

\subsection{Bestandsaufnahme Unterwasserseite}

\begin{tabular}{@{}p{8.1cm}p{8.1cm}@{}}
\footnotesize{\textbf{Rohrdurchmesser innen:}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->tailwater_pipe_diameter) !!} {!! Latex::escape('mm') !!}} \\
\footnotesize{\textbf{Vollfüllung?}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->tailwater_pipe_fully_filled_string) !!}} \\
\footnotesize{\textbf{Gefälle der Auslaufstrecke:}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->tailwater_runout_section_slope ? $flowMeterInspectionReport->tailwater_runout_section_slope_assessment_type.'\textperthousand' : '') !!}} \\
\footnotesize{\textbf{Vermessung durchgeführt mittels:}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->tailwater_runout_section_slope_assessment_type ?? '') !!}} \\
\end{tabular}

\subsection{Beurteilung der Auslaufstrecke}

\begin{tabular}{@{}p{8.1cm}p{8.1cm}@{}}
\footnotesize{\textbf{Beurteilung der Auslaufstrecke:}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->tailwater_runout_section_assessment) !!}} \\
\footnotesize{\textbf{Ist ein Leerlaufen des Messrohrs möglich?}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->tailwater_measurement_pipe_can_run_dry_string) !!}} \\
\footnotesize{\textbf{(Bei Teilfüllung:) Werden die Strömungsverhältnisse im Unterwasser vom Vorfluter oder anderen Einleitungen beeinflusst?}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->tailwater_flow_conditions_influenced ? 'ja, durch '.$flowMeterInspectionReport->tailwater_flow_conditions_influencer : 'nein') !!}} \\
\end{tabular}

\section{Funktionskontrolle Messsystem}

\subsection{Kontrolle der Messwert-Anzeige bei Null-Durchfluss}

\begin{tabular}{@{}p{8.1cm}p{8.1cm}@{}}
\footnotesize{\textbf{Wie wird der Null-Durchfluss geprüft?}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->zero_flow_rate_testing_conditions ?? '') !!}} \\
\footnotesize{\textbf{Wo wird der Null-Durchfluss abgelesen?}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->zero_flow_rate_reading_points ?? '') !!}} \\
\footnotesize{\textbf{Angezeigter Durchfluss bei Null-Durchfluss:}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->zero_flow_rate_displayed_flow ?? '') !!} {!! Latex::escape('l/s') !!}} \\
\end{tabular}


\section{Vergleichsmessung}

\subsection{a) Mittels einer mobilen Messeinrichtung}

\begin{tabular}{@{}p{8.1cm}p{8.1cm}@{}}
\footnotesize{\textbf{Angewendetes Messverfahren:}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->comparison_measurement_mobile_type !== null ? ($flowMeterInspectionReport->comparison_measurement_mobile_type === 'other' ? $flowMeterInspectionReport->comparison_measurement_mobile_type_other : trans($flowMeterInspectionReport->comparison_measurement_mobile_type)) : '') !!}} \\
\footnotesize{\textbf{Einbauort der Vergleichsmessung:}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->comparison_measurement_mobile_installation_point ?? '') !!}} \\
\end{tabular}

\subsubsection{Verwendetes Prüfmittel}

\begin{tabular}{@{}p{8.1cm}p{8.1cm}@{}}
\footnotesize{\textbf{Fabrikat:}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->comparison_measurement_mobile_equipment_make ?? '') !!}} \\
\footnotesize{\textbf{Type:}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->comparison_measurement_mobile_equipment_type ?? '') !!}} \\
\footnotesize{\textbf{Seriennummer:}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->comparison_measurement_mobile_equipment_identifier ?? '') !!}} \\
\footnotesize{\textbf{Q\textsubscript{min}:}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->comparison_measurement_mobile_equipment_q_min !== null ? $flowMeterInspectionReport->comparison_measurement_mobile_equipment_q_min . ' l/s' : '') !!}} \\
\footnotesize{\textbf{Messbereich laut Hersteller (bezogen auf vorhandenen Messquerschnitt) 100\%:}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->comparison_measurement_mobile_equipment_maximum_flow_rate ? $flowMeterInspectionReport->comparison_measurement_mobile_equipment_maximum_flow_rate . $flowMeterInspectionReport->comparison_measurement_mobile_equipment_maximum_flow_rate_unit_string . ' bei ' . $flowMeterInspectionReport->comparison_measurement_mobile_equipment_maximum_speed . $flowMeterInspectionReport->comparison_measurement_mobile_equipment_maximum_speed_unit_string :  '') !!}} \\
\footnotesize{\textbf{Datum des letzten Kalibrierscheins:}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->comparison_measurement_mobile_equipment_last_calibrated_on ?? '') !!}} \\
\footnotesize{\textbf{ausgestellt durch:}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->comparison_measurement_mobile_equipment_last_cal_provider ?? '') !!}} \\
\footnotesize{\textbf{Dokumentation, Geschäftszahl:}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->comparison_measurement_mobile_equipment_last_cal_doc_identifier ?? '') !!}} \\
\end{tabular}

\subsection{b) Volumetrisch}

\begin{tabular}{@{}p{8.1cm}p{8.1cm}@{}}
\footnotesize{\textbf{In welches Becken/aus welchem Becken wurde gefördert?}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->comparison_measurement_volumetric_basin ?? '') !!}} \\
\footnotesize{\textbf{Querschnittsfläche des Vergleichsbehälters:}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->comparison_measurement_volumetric_basin_cross_section_area ? $flowMeterInspectionReport->comparison_measurement_volumetric_basin_cross_section_area . 'm²' : '') !!}} \\
\footnotesize{\textbf{Verwendete Höhenmessung:}} & \footnotesize{{!! Latex::escape($flowMeterInspectionReport->comparison_measurement_volumetric_height_measurement_equipment ?? '') !!}} \\
\end{tabular}

\newpage

\begin{landscape}

\section{Dokumentation der Vergleichsmessung}

\begin{tabular}{|p{2cm}|p{2cm}|p{2cm}|p{1.9cm}|p{2cm}|p{1.9cm}|p{1.9cm}|p{1.9cm}|p{1.9cm}|p{1.9cm}|p{1.9cm}|p{1.9cm}|p{1.9cm}|p{2cm}|p{2cm}|p{2cm}|}
\hline
\multicolumn{1}{|c|}{\scriptsize{(1)}} & \multicolumn{1}{c|}{\scriptsize{(2)}} & \multicolumn{1}{c|}{\scriptsize{(3)}} & \multicolumn{1}{c|}{\scriptsize{(4)}} & \multicolumn{1}{c|}{\scriptsize{(5)}} & \multicolumn{1}{c|}{\scriptsize{(6)}} & \multicolumn{1}{c|}{\scriptsize{(7)}} & \multicolumn{1}{c|}{\scriptsize{(8)}} & \multicolumn{1}{c|}{\scriptsize{(9)}} & \multicolumn{1}{c|}{\scriptsize{(10)}} & \multicolumn{1}{c|}{\scriptsize{(11)}} &  \multicolumn{1}{c|}{\scriptsize{(12)}} & \multicolumn{1}{c|}{\scriptsize{(13)}} & \multicolumn{1}{c|}{\scriptsize{(14)}} & \multicolumn{1}{c|}{\scriptsize{(15)}} & \multicolumn{1}{c|}{\scriptsize{(16)}} \\
\hline
\multicolumn{2}{|c|}{\scriptsize{\shortstack{Messbereich des \\ stationären Systems}}} & \multicolumn{1}{c|}{\scriptsize{Uhrzeit}} & \multicolumn{1}{c|}{\scriptsize{Dauer\textsuperscript{**}}} & \multicolumn{1}{c|}{\scriptsize{Uhrzeit}} & \multicolumn{3}{c|}{\scriptsize{Messwertumformer}} & \multicolumn{3}{c|}{\scriptsize{Prozessleitsystem}} & \multicolumn{3}{c|}{\scriptsize{Vergleichsmessung}} & \multicolumn{1}{c|}{\scriptsize{\shortstack{Abweichung\\Vergleich/\\stationär}}} & \multicolumn{1}{c|}{\scriptsize{\shortstack{errechneter\\Mittelwert\\mobil}}} \\
\hline
\multicolumn{1}{|c|}{\scriptsize{\% Q}} & \multicolumn{1}{c|}{\scriptsize{l/s}} & \multicolumn{1}{c|}{\scriptsize{Start}} & \multicolumn{1}{c|}{\scriptsize{Minuten}} & \multicolumn{1}{c|}{\scriptsize{Ende}} & \multicolumn{2}{c|}{\scriptsize{Zählerstand m\textsuperscript{3}}} & \multicolumn{1}{c|}{\scriptsize{Summe}} & \multicolumn{2}{c|}{\scriptsize{Zählerstand m\textsuperscript{3}}} & \multicolumn{1}{c|}{\scriptsize{Summe}} &  \multicolumn{2}{c|}{\scriptsize{m\textsuperscript{3}}} & \multicolumn{1}{c|}{\scriptsize{Summe}} & \multicolumn{1}{c|}{\scriptsize{\%}} & \multicolumn{1}{c|}{\scriptsize{l/s}} \\
\hline
\multicolumn{1}{|c|}{\scriptsize{}} & \multicolumn{1}{c|}{\scriptsize{}} & \multicolumn{1}{c|}{\scriptsize{}} & \multicolumn{1}{c|}{\scriptsize{= (5)-(3)}} & \multicolumn{1}{c|}{\scriptsize{}} & \multicolumn{1}{c|}{\scriptsize{Start}} & \multicolumn{1}{c|}{\scriptsize{Ende}} & \multicolumn{1}{c|}{\scriptsize{= (7)-(6)}} & \multicolumn{1}{c|}{\scriptsize{Start}} & \multicolumn{1}{c|}{\scriptsize{Ende}} & \multicolumn{1}{c|}{\scriptsize{= (10)-(9)}} &  \multicolumn{1}{c|}{\scriptsize{Start}} & \multicolumn{1}{c|}{\scriptsize{Ende}} & \multicolumn{1}{c|}{\scriptsize{= (13)-(12)}} & \multicolumn{1}{c|}{\scriptsize{= [(11)-(14)]/(14)}} & \multicolumn{1}{c|}{\scriptsize{vgl. (2)}} \\
\hline
\multicolumn{1}{|c|}{\scriptsize{}} & \multicolumn{1}{c|}{\scriptsize{}} & \multicolumn{1}{c|}{\scriptsize{{!! Latex::escape(optional(optional($flowMeterInspectionReport->measurementsQ100)->started_at)->format('d.m.y') ?? '') !!}}} & \multicolumn{1}{c|}{\scriptsize{}} & \multicolumn{1}{c|}{\scriptsize{{!! Latex::escape(optional(optional($flowMeterInspectionReport->measurementsQ100)->ended_at)->format("d.m.y") ?? '') !!}}} & \multicolumn{1}{c|}{\scriptsize{}} & \multicolumn{1}{c|}{\scriptsize{}} & \multicolumn{1}{c|}{\scriptsize{}} & \multicolumn{1}{c|}{\scriptsize{}} & \multicolumn{1}{c|}{\scriptsize{}} & \multicolumn{1}{c|}{\scriptsize{}} &  \multicolumn{1}{c|}{\scriptsize{}} & \multicolumn{1}{c|}{\scriptsize{}} & \multicolumn{1}{c|}{\scriptsize{}} & \multicolumn{1}{c|}{\scriptsize{}} & \multicolumn{1}{c|}{\scriptsize{()}} \\
\hline
\multicolumn{1}{|c|}{\scriptsize{Gesamt}} & \multicolumn{1}{c|}{\scriptsize{{!! Latex::escape(optional($flowMeterInspectionReport->measurementsQ100)->q_value ?? '') !!}}} & \multicolumn{1}{c|}{\scriptsize{{!! Latex::escape(optional(optional($flowMeterInspectionReport->measurementsQ100)->started_at)->format("H:i") ?? '') !!}}} & \multicolumn{1}{c|}{\scriptsize{{!! Latex::escape($flowMeterInspectionReport->measurementsQ100 && $flowMeterInspectionReport->measurementsQ100->started_at && $flowMeterInspectionReport->measurementsQ100->ended_at ? $flowMeterInspectionReport->measurementsQ100->started_at->diffInHours($flowMeterInspectionReport->measurementsQ100->ended_at) . ':' . $flowMeterInspectionReport->measurementsQ100->started_at->diff($flowMeterInspectionReport->measurementsQ100->ended_at)->format("%I") : '') !!}}} & \multicolumn{1}{c|}{\scriptsize{{!! Latex::escape(optional(optional($flowMeterInspectionReport->measurementsQ100)->ended_at)->format("H:i") ?? '') !!}}} & \multicolumn{1}{c|}{\scriptsize{{!! Latex::escape(optional($flowMeterInspectionReport->measurementsQ100)->measurement_transformer_reading_start ?? '') !!}}} & \multicolumn{1}{c|}{\scriptsize{{!! Latex::escape(optional($flowMeterInspectionReport->measurementsQ100)->measurement_transformer_reading_end ?? '') !!}}} & \multicolumn{1}{c|}{\scriptsize{{!! Latex::escape(optional($flowMeterInspectionReport->measurementsQ100)->measurement_transformer_reading_sum ?? '') !!}}} & \multicolumn{1}{c|}{\scriptsize{{!! Latex::escape(optional($flowMeterInspectionReport->measurementsQ100)->pcs_reading_start ?? '') !!}}} & \multicolumn{1}{c|}{\scriptsize{{!! Latex::escape(optional($flowMeterInspectionReport->measurementsQ100)->pcs_reading_end ?? '') !!}}} & \multicolumn{1}{c|}{\scriptsize{{!! Latex::escape(optional($flowMeterInspectionReport->measurementsQ100)->pcs_reading_sum ?? '') !!}}} & \multicolumn{1}{c|}{\scriptsize{{!! Latex::escape(optional($flowMeterInspectionReport->measurementsQ100)->comparison_measurement_start ?? '') !!}}} & \multicolumn{1}{c|}{\scriptsize{{!! Latex::escape(optional($flowMeterInspectionReport->measurementsQ100)->comparison_measurement_end ?? '') !!}}} & \multicolumn{1}{c|}{\scriptsize{{!! Latex::escape(optional($flowMeterInspectionReport->measurementsQ100)->comparison_measurement_sum ?? '') !!}}} & \multicolumn{1}{c|}{\scriptsize{{!! Latex::escape(optional($flowMeterInspectionReport->measurementsQ100)->measurement_difference ?? '') !!}}} & \multicolumn{1}{c|}{\scriptsize{{!! Latex::escape(optional($flowMeterInspectionReport->measurementsQ100)->q_value_average_mobile ?? '') !!}}} \\
\hline
\multicolumn{1}{|c|}{\scriptsize{(51-) 70\%\textsuperscript{*} =}} & \multicolumn{1}{c|}{\scriptsize{{!! Latex::escape(optional($flowMeterInspectionReport->measurementsQ70)->q_value ?? '') !!}}} & \multicolumn{1}{c|}{\scriptsize{{!! Latex::escape(optional(optional($flowMeterInspectionReport->measurementsQ70)->started_at)->format("H:i") ?? '') !!}}} & \multicolumn{1}{c|}{\scriptsize{{!! Latex::escape($flowMeterInspectionReport->measurementsQ70 && $flowMeterInspectionReport->measurementsQ70->started_at && $flowMeterInspectionReport->measurementsQ70->ended_at ? $flowMeterInspectionReport->measurementsQ70->ended_at->diff($flowMeterInspectionReport->measurementsQ70->started_at)->format("%H:%I") : '') !!}}} & \multicolumn{1}{c|}{\scriptsize{{!! Latex::escape(optional(optional($flowMeterInspectionReport->measurementsQ70)->ended_at)->format("H:i") ?? '') !!}}} & \multicolumn{1}{c|}{\scriptsize{{!! Latex::escape(optional($flowMeterInspectionReport->measurementsQ70)->measurement_transformer_reading_start ?? '') !!}}} & \multicolumn{1}{c|}{\scriptsize{{!! Latex::escape(optional($flowMeterInspectionReport->measurementsQ70)->measurement_transformer_reading_end ?? '') !!}}} & \multicolumn{1}{c|}{\scriptsize{{!! Latex::escape(optional($flowMeterInspectionReport->measurementsQ70)->measurement_transformer_reading_sum ?? '') !!}}} & \multicolumn{1}{c|}{\scriptsize{{!! Latex::escape(optional($flowMeterInspectionReport->measurementsQ70)->pcs_reading_start ?? '') !!}}} & \multicolumn{1}{c|}{\scriptsize{{!! Latex::escape(optional($flowMeterInspectionReport->measurementsQ70)->pcs_reading_end ?? '') !!}}} & \multicolumn{1}{c|}{\scriptsize{{!! Latex::escape(optional($flowMeterInspectionReport->measurementsQ70)->pcs_reading_sum ?? '') !!}}} & \multicolumn{1}{c|}{\scriptsize{{!! Latex::escape(optional($flowMeterInspectionReport->measurementsQ70)->comparison_measurement_start ?? '') !!}}} & \multicolumn{1}{c|}{\scriptsize{{!! Latex::escape(optional($flowMeterInspectionReport->measurementsQ70)->comparison_measurement_end ?? '') !!}}} & \multicolumn{1}{c|}{\scriptsize{{!! Latex::escape(optional($flowMeterInspectionReport->measurementsQ70)->comparison_measurement_sum ?? '') !!}}} & \multicolumn{1}{c|}{\scriptsize{{!! Latex::escape(optional($flowMeterInspectionReport->measurementsQ70)->measurement_difference ?? '') !!}}} & \multicolumn{1}{c|}{\scriptsize{{!! Latex::escape(optional($flowMeterInspectionReport->measurementsQ70)->q_value_average_mobile ?? '') !!}}} \\
\hline
\multicolumn{1}{|c|}{\scriptsize{(31-) 50\% =}} & \multicolumn{1}{c|}{\scriptsize{{!! Latex::escape(optional($flowMeterInspectionReport->measurementsQ50)->q_value ?? '') !!}}} & \multicolumn{1}{c|}{\scriptsize{{!! Latex::escape(optional(optional($flowMeterInspectionReport->measurementsQ50)->started_at)->format("H:i") ?? '') !!}}} & \multicolumn{1}{c|}{\scriptsize{{!! Latex::escape($flowMeterInspectionReport->measurementsQ50 && $flowMeterInspectionReport->measurementsQ50->started_at && $flowMeterInspectionReport->measurementsQ50->ended_at ? $flowMeterInspectionReport->measurementsQ50->ended_at->diff($flowMeterInspectionReport->measurementsQ50->started_at)->format("%H:%I") : '') !!}}} & \multicolumn{1}{c|}{\scriptsize{{!! Latex::escape(optional(optional($flowMeterInspectionReport->measurementsQ50)->ended_at)->format("H:i") ?? '') !!}}} & \multicolumn{1}{c|}{\scriptsize{{!! Latex::escape(optional($flowMeterInspectionReport->measurementsQ50)->measurement_transformer_reading_start ?? '') !!}}} & \multicolumn{1}{c|}{\scriptsize{{!! Latex::escape(optional($flowMeterInspectionReport->measurementsQ50)->measurement_transformer_reading_end ?? '') !!}}} & \multicolumn{1}{c|}{\scriptsize{{!! Latex::escape(optional($flowMeterInspectionReport->measurementsQ50)->measurement_transformer_reading_sum ?? '') !!}}} & \multicolumn{1}{c|}{\scriptsize{{!! Latex::escape(optional($flowMeterInspectionReport->measurementsQ50)->pcs_reading_start ?? '') !!}}} & \multicolumn{1}{c|}{\scriptsize{{!! Latex::escape(optional($flowMeterInspectionReport->measurementsQ50)->pcs_reading_end ?? '') !!}}} & \multicolumn{1}{c|}{\scriptsize{{!! Latex::escape(optional($flowMeterInspectionReport->measurementsQ50)->pcs_reading_sum ?? '') !!}}} & \multicolumn{1}{c|}{\scriptsize{{!! Latex::escape(optional($flowMeterInspectionReport->measurementsQ50)->comparison_measurement_start ?? '') !!}}} & \multicolumn{1}{c|}{\scriptsize{{!! Latex::escape(optional($flowMeterInspectionReport->measurementsQ50)->comparison_measurement_end ?? '') !!}}} & \multicolumn{1}{c|}{\scriptsize{{!! Latex::escape(optional($flowMeterInspectionReport->measurementsQ50)->comparison_measurement_sum ?? '') !!}}} & \multicolumn{1}{c|}{\scriptsize{{!! Latex::escape(optional($flowMeterInspectionReport->measurementsQ50)->measurement_difference ?? '') !!}}} & \multicolumn{1}{c|}{\scriptsize{{!! Latex::escape(optional($flowMeterInspectionReport->measurementsQ50)->q_value_average_mobile ?? '') !!}}} \\
\hline
\multicolumn{1}{|c|}{\scriptsize{(21-) 30\% =}} & \multicolumn{1}{c|}{\scriptsize{{!! Latex::escape(optional($flowMeterInspectionReport->measurementsQ30)->q_value ?? '') !!}}} & \multicolumn{1}{c|}{\scriptsize{{!! Latex::escape(optional(optional($flowMeterInspectionReport->measurementsQ30)->started_at)->format("H:i") ?? '') !!}}} & \multicolumn{1}{c|}{\scriptsize{{!! Latex::escape($flowMeterInspectionReport->measurementsQ30 && $flowMeterInspectionReport->measurementsQ30->started_at && $flowMeterInspectionReport->measurementsQ30->ended_at ? $flowMeterInspectionReport->measurementsQ30->ended_at->diff($flowMeterInspectionReport->measurementsQ30->started_at)->format("%H:%I") : '') !!}}} & \multicolumn{1}{c|}{\scriptsize{{!! Latex::escape(optional(optional($flowMeterInspectionReport->measurementsQ30)->ended_at)->format("H:i") ?? '') !!}}} & \multicolumn{1}{c|}{\scriptsize{{!! Latex::escape(optional($flowMeterInspectionReport->measurementsQ30)->measurement_transformer_reading_start ?? '') !!}}} & \multicolumn{1}{c|}{\scriptsize{{!! Latex::escape(optional($flowMeterInspectionReport->measurementsQ30)->measurement_transformer_reading_end ?? '') !!}}} & \multicolumn{1}{c|}{\scriptsize{{!! Latex::escape(optional($flowMeterInspectionReport->measurementsQ30)->measurement_transformer_reading_sum ?? '') !!}}} & \multicolumn{1}{c|}{\scriptsize{{!! Latex::escape(optional($flowMeterInspectionReport->measurementsQ30)->pcs_reading_start ?? '') !!}}} & \multicolumn{1}{c|}{\scriptsize{{!! Latex::escape(optional($flowMeterInspectionReport->measurementsQ30)->pcs_reading_end ?? '') !!}}} & \multicolumn{1}{c|}{\scriptsize{{!! Latex::escape(optional($flowMeterInspectionReport->measurementsQ30)->pcs_reading_sum ?? '') !!}}} & \multicolumn{1}{c|}{\scriptsize{{!! Latex::escape(optional($flowMeterInspectionReport->measurementsQ30)->comparison_measurement_start ?? '') !!}}} & \multicolumn{1}{c|}{\scriptsize{{!! Latex::escape(optional($flowMeterInspectionReport->measurementsQ30)->comparison_measurement_end ?? '') !!}}} & \multicolumn{1}{c|}{\scriptsize{{!! Latex::escape(optional($flowMeterInspectionReport->measurementsQ30)->comparison_measurement_sum ?? '') !!}}} & \multicolumn{1}{c|}{\scriptsize{{!! Latex::escape(optional($flowMeterInspectionReport->measurementsQ30)->measurement_difference ?? '') !!}}} & \multicolumn{1}{c|}{\scriptsize{{!! Latex::escape(optional($flowMeterInspectionReport->measurementsQ30)->q_value_average_mobile ?? '') !!}}} \\
\hline
\multicolumn{1}{|c|}{\scriptsize{(10-) 20\% =}} & \multicolumn{1}{c|}{\scriptsize{{!! Latex::escape(optional($flowMeterInspectionReport->measurementsQ20)->q_value ?? '') !!}}} & \multicolumn{1}{c|}{\scriptsize{{!! Latex::escape(optional(optional($flowMeterInspectionReport->measurementsQ20)->started_at)->format("H:i") ?? '') !!}}} & \multicolumn{1}{c|}{\scriptsize{{!! Latex::escape($flowMeterInspectionReport->measurementsQ20 && $flowMeterInspectionReport->measurementsQ20->started_at && $flowMeterInspectionReport->measurementsQ20->ended_at ? $flowMeterInspectionReport->measurementsQ20->ended_at->diff($flowMeterInspectionReport->measurementsQ20->started_at)->format("%H:%I") : '') !!}}} & \multicolumn{1}{c|}{\scriptsize{{!! Latex::escape(optional(optional($flowMeterInspectionReport->measurementsQ20)->ended_at)->format("H:i") ?? '') !!}}} & \multicolumn{1}{c|}{\scriptsize{{!! Latex::escape(optional($flowMeterInspectionReport->measurementsQ20)->measurement_transformer_reading_start ?? '') !!}}} & \multicolumn{1}{c|}{\scriptsize{{!! Latex::escape(optional($flowMeterInspectionReport->measurementsQ20)->measurement_transformer_reading_end ?? '') !!}}} & \multicolumn{1}{c|}{\scriptsize{{!! Latex::escape(optional($flowMeterInspectionReport->measurementsQ20)->measurement_transformer_reading_sum ?? '') !!}}} & \multicolumn{1}{c|}{\scriptsize{{!! Latex::escape(optional($flowMeterInspectionReport->measurementsQ20)->pcs_reading_start ?? '') !!}}} & \multicolumn{1}{c|}{\scriptsize{{!! Latex::escape(optional($flowMeterInspectionReport->measurementsQ20)->pcs_reading_end ?? '') !!}}} & \multicolumn{1}{c|}{\scriptsize{{!! Latex::escape(optional($flowMeterInspectionReport->measurementsQ20)->pcs_reading_sum ?? '') !!}}} & \multicolumn{1}{c|}{\scriptsize{{!! Latex::escape(optional($flowMeterInspectionReport->measurementsQ20)->comparison_measurement_start ?? '') !!}}} & \multicolumn{1}{c|}{\scriptsize{{!! Latex::escape(optional($flowMeterInspectionReport->measurementsQ20)->comparison_measurement_end ?? '') !!}}} & \multicolumn{1}{c|}{\scriptsize{{!! Latex::escape(optional($flowMeterInspectionReport->measurementsQ20)->comparison_measurement_sum ?? '') !!}}} & \multicolumn{1}{c|}{\scriptsize{{!! Latex::escape(optional($flowMeterInspectionReport->measurementsQ20)->measurement_difference ?? '') !!}}} & \multicolumn{1}{c|}{\scriptsize{{!! Latex::escape(optional($flowMeterInspectionReport->measurementsQ20)->q_value_average_mobile ?? '') !!}}} \\
\hline
\end{tabular}

\begin{scriptsize}
\textsuperscript{*} sofern bei der Überprüfung möglich\\
\textsuperscript{**} insgesamnt mindestens 3 Stunden\\
\end{scriptsize}

\begin{footnotesize}
\textbf{Anleitung zum Ausfüllen der Tabelle}

\begin{enumerate}[label=(\alph*)]
\item Aus dem Datenblatt des stationären Messsystems sind die jeweiligen Prozentwerte (1) in die entsprechenden Felder von Spalte (2) einzutragen.
\item Die Vergleichsmessung soll dann zumindest in den untereen 3 Bereichen (10-50\% vom Messbereichsendwert) durchgeführt werden. Dies wird in den Spalten (3) bis (5) dokumentiert.
\item Beim Beginn und Ende der jeweiligen Vergleichsmessung(en) in den verschiedenen Bereichen sind die jeweiligen Zählerstände des Messwertumformers (6) und (7) und des Prozessleitsystems (9) und (10) einzutragen bzw. zu dokumentieren. Die Zählerstände des mobilen Messsystems können auch nachträglich eingetragen werden, da ja elektronisch dokumentiert wird (das ist auch erforderlich, um die Ganglinie zu dokumentieren).
\item Abschließend wird in Spalte 815) die Abweichung zwischen der Vergleichsmessung und der stationären Messung (Spalte (11) dividiert durch (14)) berechnet und der Mittelwert des Durchflusses über die Dauer der Vergleichsmessung in den jeweiligen Bereichen berechnet.
\item Der jeweilige Wert in Spalte (16) dient als Kontrollwert, ob der jeweils geforderte Bereich in Spalte (2) eingehalten wird.
\end{enumerate}
\end{footnotesize}

\end{landscape}

\newpage

\section{Zusammenfassende Beurteilung}

\begin{footnotesize}
Nach Überprüfung der Messstelle wird festgestellt, dass zum Zeitpunkt der Überprüfung die Messwerte der stationären Messung im Durchflussbereich

von $0,1$ Q\textsubscript{max} bis $0,3$ Q\textsubscript{max} von der Vergleichsmessung um \textbf{{!! Latex::escape($flowMeterInspectionReport->measurement_difference_up_to_30_q_max) !!}{!! Latex::escape('%') !!}} abweichen\\
über $0,3$ Q\textsubscript{max} von der Vergleichsmessung um \textbf{{!! Latex::escape($flowMeterInspectionReport->measurement_difference_above_30_q_max) !!}{!! Latex::escape('%') !!}} abweichen,

die Differenz der Zählerstände der stationären Messung im Durchflussbereich

von $0,1$ Q\textsubscript{max} bis $0,3$ Q\textsubscript{max} vom Ergebnis der Vergleichsmessung um \textbf{{!! Latex::escape($flowMeterInspectionReport->reading_difference_up_to_30_q_max) !!}{!! Latex::escape('%') !!}} abweicht\\
über $0,3$ Q\textsubscript{max} vom Ergebnis der Vergleichsmessung um \textbf{{!! Latex::escape($flowMeterInspectionReport->reading_difference_above_30_q_max) !!}{!! Latex::escape('%') !!}} abweicht.

\textbf{Das Messsystem arbeitet somit {!! Latex::escape($flowMeterInspectionReport->equipment_in_tolerance_range ? 'innerhalb' : 'außerhalb') !!} des maximalen Toleranzbereichs von 10{!! Latex::escape('%') !!} des ÖWAV Regelblatts 38.}

Beim Messsystem wurden folgende Mängel festgstellt: {!! Latex::escape($flowMeterInspectionReport->equipment_deficiencies ?? '') !!}
@if($flowMeterInspectionReport->equipment_deficiencies)
\\
Zweitprüfung/Vollprüfung nach Korrektur erforderlich? {!! Latex::escape($flowMeterInspectionReport->further_inspection_required_string) !!}
@endif

Die oben stehenden Aussagen beruhen auf eigenen Erhebungen und Vergleichsmessungen.

\end{footnotesize}

@if($flowMeterInspectionReport->comment)
\section{Sonstige Kommentare}
\footnotesize{{!! Latex::fromMarkdown($flowMeterInspectionReport->comment) !!}}
@endif

@if($flowMeterInspectionReport->appendix_description)
\section{Anhänge}
{!! Latex::escape($flowMeterInspectionReport->appendix_description) !!}
@endif

\vfill
\begin{minipage}[t][2.5cm]{0.5\textwidth}
\vfill
\centering
@if($flowMeterInspectionReport->employee->user->signature())
\includegraphics[height=2cm]{{!! Latex::escape($flowMeterInspectionReport->employee->user->signature()->getPath()) !!}}
\\
@endif
\footnotesize{Unterschrift Techniker @if($flowMeterInspectionReport->employee->user->signature())vom {!! Latex::escape($flowMeterInspectionReport->created_at) !!} @endif}
\end{minipage}
\begin{minipage}[t][2.5cm]{0.5\textwidth}
\vfill
\centering
@if($flowMeterInspectionReport->signature())
\includegraphics[height=2cm]{{!! Latex::escape($flowMeterInspectionReport->signature()->getPath()) !!}}
\\
@endif
\footnotesize{Unterschrift Kunde @if($flowMeterInspectionReport->signature())vom {!! Latex::escape($flowMeterInspectionReport->signature()->created_at) !!} @endif}
\end{minipage}
\\
@if($flowMeterInspectionReport->appendix())
\newpage
\includepdf[pages=-,pagecommand={},height=\textheight]{{!! $flowMeterInspectionReport->appendix()->getPath() !!}}
@endif
\end{document}
