<?php

namespace App\Helpers;

use App\Models\AdditionsReport;
use App\Models\ConstructionReport;
use App\Models\InspectionReport;
use App\Models\Memo;
use App\Models\ServiceReport;
use App\Models\Task;
use App\Models\TaskComment;
use App\Notifications\AdditionsReportInvolvedNotification;
use App\Notifications\AdditionsReportMentionNotification;
use App\Notifications\AdditionsReportSignedNotification;
use App\Notifications\ApplicationVersionUpdateNotification;
use App\Notifications\CommentInvolvedNotification;
use App\Notifications\CommentMentionNotification;
use App\Notifications\ConstructionReportInvolvedNotification;
use App\Notifications\ConstructionReportMentionNotification;
use App\Notifications\ConstructionReportSignedNotification;
use App\Notifications\FlowMeterInspectionReportMentionNotification;
use App\Notifications\FlowMeterInspectionReportSignedNotification;
use App\Notifications\HolidayAllowanceAdjustmentNotification;
use App\Notifications\InspectionReportMentionNotification;
use App\Notifications\InspectionReportSignedNotification;
use App\Notifications\MemoInvolvedNotification;
use App\Notifications\MemoMentionNotification;
use App\Notifications\ServiceReportMentionNotification;
use App\Notifications\ServiceReportSignedNotification;
use App\Notifications\TaskInvolvedNotification;
use App\Notifications\TaskMentionNotification;
use Illuminate\Notifications\DatabaseNotification;

class NotificationHelper
{
    public static function header(DatabaseNotification $notification): string
    {
        switch ($notification->type) {
            case AdditionsReportInvolvedNotification::class:
                return $notification->data['created'] ?
                    'Ein Regiebericht wurde erstellt' :
                    'Ein Regiebericht wurde bearbeitet';

            case AdditionsReportMentionNotification::class:
                return 'Du wurdst in einem Regiebericht erwähnt';

            case AdditionsReportSignedNotification::class:
                return 'Ein Regiebericht wurde unterschrieben';

            case ApplicationVersionUpdateNotification::class:
                return 'Eine neue Quokka Version ist verfügbar';

            case CommentInvolvedNotification::class:
                return $notification->data['created'] ?
                    'Ein Kommentar in einer Aufgabe wurde erstellt' :
                    'Ein Kommentar in einer Aufgabe wurde bearbeitet';

            case CommentMentionNotification::class:
                return 'Du wurdst in einem Kommentar erwähnt';

            case ConstructionReportInvolvedNotification::class:
                return $notification->data['created'] ?
                    'Ein Bautagesbericht wurde erstellt' :
                    'Ein Bautagesbericht wurde bearbeitet';

            case ConstructionReportMentionNotification::class:
                return 'Du wurdst in einem Bautagesbericht erwähnt';

            case ConstructionReportSignedNotification::class:
                return 'Ein Bautagesbericht wurde unterschrieben';

            case FlowMeterInspectionReportMentionNotification::class:
                return 'Du wurdst in einem Prüfbericht für Durchflussmesseinrichtungen erwähnt';

            case FlowMeterInspectionReportSignedNotification::class:
                return 'Ein Prüfbericht für Durchflussmesseinrichtungen wurde unterschrieben';

            case HolidayAllowanceAdjustmentNotification::class:
               return $notification->data['manualAdjustment'] ? "Urlaubsanpassung" : "Automatische Urlaubsanpassung";

            case InspectionReportMentionNotification::class:
                return 'Du wurdst in einem Prüfbericht erwähnt';

            case InspectionReportSignedNotification::class:
                return 'Ein Prüfbericht wurde unterschrieben';

            case MemoInvolvedNotification::class:
                return $notification->data['created'] ?
                    'Ein Aktenvermerk wurde erstellt' :
                    'Ein Aktenvermerk wurde bearbeitet';

            case MemoMentionNotification::class:
                return 'Du wurdst in einem Aktenvermerk erwähnt';

            case ServiceReportMentionNotification::class:
                return 'Du wurdst in einem Servicebericht erwähnt';

            case ServiceReportSignedNotification::class:
                return 'Ein Servicebericht wurde unterschrieben';

            case TaskInvolvedNotification::class:
                return $notification->data['created'] ?
                    'Eine Aufgabe wurde erstellt' :
                    'Eine Aufgabe wurde bearbeitet';

            case TaskMentionNotification::class:
                return 'Du wurdst in einer Aufgabe erwähnt';

            default:
                return '';
        }
    }

    public static function text(DatabaseNotification $notification): string
    {
        switch ($notification->type) {
            case AdditionsReportInvolvedNotification::class:
                $additionsReport = AdditionsReport::with('project')
                    ->find($notification->data['id']);

                if(!$additionsReport) {
                    return 'Der Regiebericht existiert nicht mehr.';
                }

                return $notification->data['created'] ?
                    'Der Regiebericht Projekt '.$additionsReport->project->name.' #'.$additionsReport->number.', an dem du beteiligt bist, wurde erstellt.' :
                    'Der Regiebericht Projekt '.$additionsReport->project->name.' #'.$additionsReport->number.', an dem du beteiligt bist, wurde bearbeitet.';

            case AdditionsReportMentionNotification::class:
                $additionsReport = AdditionsReport::with('project')
                    ->find($notification->data['id']);

                if(!$additionsReport) {
                    return 'Der Regiebericht existiert nicht mehr.';
                }

                return 'Du wurdst im Regiebericht Projekt '.$additionsReport->project->name.' #'.$additionsReport->number.' erwähnt';

            case AdditionsReportSignedNotification::class:
                $additionsReport = AdditionsReport::with('project')
                    ->find($notification->data['id']);

                if(!$additionsReport) {
                    return 'Der Regiebericht existiert nicht mehr.';
                }

                return 'Der Regiebericht Projekt '.$additionsReport->project->name.' #'.$additionsReport->number.' wurde unterschrieben.';

            case ApplicationVersionUpdateNotification::class:
                return 'Quokka Version '.$notification->data['version'].' ist nun zur Verwendung verfügbar.';

            case CommentInvolvedNotification::class:
                $comment = TaskComment::with('task.project')
                    ->find($notification->data['id']);

                if(!$comment || !$comment->task) {
                    return 'Der Kommentar existiert nicht mehr.';
                }

                return $notification->data['created'] ?
                    'Ein Kommentar in der Aufgabe '.$comment->task->name.' (Projekt '.$comment->task->project->name.'), an der du beteiligt bist, wurde erstellt.' :
                    'Ein Kommentar in der Aufgabe '.$comment->task->name.' (Projekt '.$comment->task->project->name.'), an der du beteiligt bist, wurde bearbeit.';

            case CommentMentionNotification::class:
                $comment = TaskComment::with('task.project')
                    ->find($notification->data['id']);

                if(!$comment || !$comment->task) {
                    return 'Der Kommentar existiert nicht mehr.';
                }

                return 'Du wurdest in einem Kommentar der Aufgabe '.$comment->task->name.' (Projekt '.$comment->task->project->name.') erwähnt.';

            case ConstructionReportInvolvedNotification::class:
                $constructionReport = ConstructionReport::with('project')
                    ->find($notification->data['id']);

                if(!$constructionReport) {
                    return 'Der Bautagesbericht existiert nicht mehr.';
                }

                return $notification->data['created'] ?
                    'Der Bautagesbericht Projekt '.$constructionReport->project->name.' #'.$constructionReport->number.', an dem du beteiligt bist, wurde erstellt.' :
                    'Der Bautagesbericht Projekt '.$constructionReport->project->name.' #'.$constructionReport->number.', an dem du beteiligt bist, wurde bearbeitet.';

            case ConstructionReportMentionNotification::class:
                $constructionReport = ConstructionReport::with('project')
                    ->find($notification->data['id']);

                if(!$constructionReport) {
                    return 'Der Bautagesbericht existiert nicht mehr.';
                }

                return 'Du wurdst im Bautagesbericht Projekt '.$constructionReport->project->name.' #'.$constructionReport->number.' erwähnt';

            case ConstructionReportSignedNotification::class:
                $constructionReport = ConstructionReport::with('project')
                    ->find($notification->data['id']);

                if(!$constructionReport) {
                    return 'Der Bautagesbericht existiert nicht mehr.';
                }

                return 'Der Bautagesbericht Projekt '.$constructionReport->project->name.' #'.$constructionReport->number.' wurde unterschrieben.';

            case FlowMeterInspectionReportMentionNotification::class:
                $flowMeterInspectionReport = FlowMeterInspectionReport::with('project')
                    ->find($notification->data['id']);

                if(!$flowMeterInspectionReport) {
                    return 'Der Prüfbericht für Durchflussmesseinrichtungen existiert nicht mehr.';
                }

                return 'Du wurdst im Prüfbericht der Anlage '.$flowMeterInspectionReport->equipment_identifier.' (Kunde: '.$flowMeterInspectionReport->project->company->name.') erwähnt';

            case FlowMeterInspectionReportSignedNotification::class:
                $flowMeterInspectionReport = FlowMeterInspectionReport::with('project')
                    ->find($notification->data['id']);

                if(!$flowMeterInspectionReport) {
                    return 'Der Prüfbericht für Durchflussmesseinrichtungen existiert nicht mehr.';
                }

                return 'Der Prüfbericht der Anlage '.$flowMeterInspectionReport->equipment_identifier.' (Kunde: '.$flowMeterInspectionReport->project->company->name.') wurde unterschrieben.';

            case HolidayAllowanceAdjustmentNotification::class:
                return 'Dein verfügbarer Urlaub wurde um ' .
                $notification->data['holidayAllowanceDifference'] . ' ' . $notification->data['holidayServiceUnit'] . ' ' . $notification->data['directionString'] . '.' .
                ' Dein aktueller Stand beträgt ' . $notification->data['currentHolidayAllowance'] . ' ' . $notification->data['holidayServiceUnit'] . '.';

            case InspectionReportMentionNotification::class:
                $inspectionReport = InspectionReport::with('project')
                    ->find($notification->data['id']);

                if(!$inspectionReport) {
                    return 'Der Prüfbericht existiert nicht mehr.';
                }

                return 'Du wurdst im Prüfbericht der Anlage '.$inspectionReport->equipment_identifier.' (Kunde: '.$inspectionReport->project->company->name.') erwähnt';

            case InspectionReportSignedNotification::class:
                $inspectionReport = InspectionReport::with('project')
                    ->find($notification->data['id']);

                if(!$inspectionReport) {
                    return 'Der Prüfbericht existiert nicht mehr.';
                }

                return 'Der Prüfbericht der Anlage '.$inspectionReport->equipment_identifier.' (Kunde: '.$inspectionReport->project->company->name.') wurde unterschrieben.';

            case MemoInvolvedNotification::class:
                $memo = Memo::with('project')
                    ->find($notification->data['id']);

                if(!$memo) {
                    return 'Der Aktenvermerk existiert nicht mehr.';
                }

                return $notification->data['created'] ?
                    'Der Aktenvermerk '.$memo->title.',  an dem du beteiligt bist, wurde erstellt (Projekt '.$memo->project->name.' #'.$memo->number.').' :
                    'Der Aktenvermerk '.$memo->title.',  an dem du beteiligt bist, wurde bearbeitet (Projekt '.$memo->project->name.' #'.$memo->number.').';

            case MemoMentionNotification::class:
                $memo = Memo::with('project')
                    ->find($notification->data['id']);

                if(!$memo) {
                    return 'Der Aktenvermerk existiert nicht mehr.';
                }

                return 'Du wurdst im Aktenvermerk '.$memo->title.' (Projekt '.$memo->project->name.' #'.$memo->number.') erwähnt.';

            case ServiceReportMentionNotification::class:
                $serviceReport = ServiceReport::with('project')
                    ->find($notification->data['id']);

                if(!$serviceReport) {
                    return 'Der Servicebericht existiert nicht mehr.';
                }

                return 'Du wurdst im Servicebericht Projekt '.$serviceReport->project->name.' #'.$serviceReport->number.' erwähnt';

            case ServiceReportSignedNotification::class:
                $serviceReport = ServiceReport::with('project')
                    ->find($notification->data['id']);

                if(!$serviceReport) {
                    return 'Der Servicebericht existiert nicht mehr.';
                }

                return 'Der Servicebericht Projekt '.$serviceReport->project->name.' #'.$serviceReport->number.' wurde unterschrieben.';

            case TaskInvolvedNotification::class:
                $task = Task::with('project')
                    ->find($notification->data['id']);

                if(!$task) {
                    return 'Die Aufgabe existiert nicht mehr.';
                }

                return $notification->data['created'] ?
                    'Die Aufgabe '.$task->name.', an der du beteiligt bist, wurde erstellt (Projekt '.$task->project->name.').' :
                    'Die Aufgabe '.$task->name.', an der du beteiligt bist, wurde bearbeitet (Projekt '.$task->project->name.').';

            case TaskMentionNotification::class:
                $task = Task::with('project')
                    ->find($notification->data['id']);

                if(!$task) {
                    return 'Die Aufgabe existiert nicht mehr.';
                }

                return 'Du wurdst in der Aufgabe '.$task->name.' (Projekt '.$task->project->name.') erwähnt';

            default:
                return '';
        }
    }
}
