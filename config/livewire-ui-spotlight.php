<?php

use App\SpotlightCommands\AccountingDownloadMonthlyReportCommand;
use App\SpotlightCommands\AccountingIndexCommand;
use App\SpotlightCommands\AdditionsReportCreateCommand;
use App\SpotlightCommands\AdditionsReportIndexCommand;
use App\SpotlightCommands\AdditionsReportShowCommand;
use App\SpotlightCommands\AddressCreateCommand;
use App\SpotlightCommands\AddressIndexCommand;
use App\SpotlightCommands\ApplicationSettingsEditCommand;
use App\SpotlightCommands\ChangelogShowCommand;
use App\SpotlightCommands\CompanyCreateCommand;
use App\SpotlightCommands\CompanyIndexCommand;
use App\SpotlightCommands\CompanyShowCommand;
use App\SpotlightCommands\ConstructionReportCreateCommand;
use App\SpotlightCommands\ConstructionReportIndexCommand;
use App\SpotlightCommands\ConstructionReportShowCommand;
use App\SpotlightCommands\EmployeeCreateCommand;
use App\SpotlightCommands\EmployeeIndexCommand;
use App\SpotlightCommands\EmployeeShowCommand;
use App\SpotlightCommands\EmployeeStartImpersonationCommand;
use App\SpotlightCommands\EmployeeStopImpersonationCommand;
use App\SpotlightCommands\ExceptionIndexCommand;
use App\SpotlightCommands\ExceptionShowCommand;
use App\SpotlightCommands\FinanceGroupCreateCommand;
use App\SpotlightCommands\FinanceGroupIndexCommand;
use App\SpotlightCommands\FinanceGroupShowCommand;
use App\SpotlightCommands\FinanceIndexCommand;
use App\SpotlightCommands\FlowMeterInspectionReportCreateCommand;
use App\SpotlightCommands\FlowMeterInspectionReportIndexCommand;
use App\SpotlightCommands\FlowMeterInspectionReportShowCommand;
use App\SpotlightCommands\GlobalSearchCommand;
use App\SpotlightCommands\HelpIndexCommand;
use App\SpotlightCommands\HelpShowCommand;
use App\SpotlightCommands\HomeShowCommand;
use App\SpotlightCommands\InspectionReportCreateCommand;
use App\SpotlightCommands\InspectionReportIndexCommand;
use App\SpotlightCommands\InspectionReportShowCommand;
use App\SpotlightCommands\LatestChangesIndexCommand;
use App\SpotlightCommands\LogbookIndexCommand;
use App\SpotlightCommands\LogoutCommand;
use App\SpotlightCommands\MaterialServiceCreateCommand;
use App\SpotlightCommands\MaterialServiceIndexCommand;
use App\SpotlightCommands\MaterialServiceShowCommand;
use App\SpotlightCommands\MemoCreateCommand;
use App\SpotlightCommands\MemoIndexCommand;
use App\SpotlightCommands\MemoShowCommand;
use App\SpotlightCommands\NoteCreateCommand;
use App\SpotlightCommands\NoteIndexCommand;
use App\SpotlightCommands\NoteShowCommand;
use App\SpotlightCommands\NotificationIndexCommand;
use App\SpotlightCommands\PersonCreateCommand;
use App\SpotlightCommands\PersonIndexCommand;
use App\SpotlightCommands\PersonShowCommand;
use App\SpotlightCommands\ProjectCreateCommand;
use App\SpotlightCommands\ProjectIndexCommand;
use App\SpotlightCommands\ProjectShowCommand;
use App\SpotlightCommands\QrScanIndexCommand;
use App\SpotlightCommands\RoleCreateCommand;
use App\SpotlightCommands\RoleIndexCommand;
use App\SpotlightCommands\RoleShowCommand;
use App\SpotlightCommands\SentEmailIndexCommand;
use App\SpotlightCommands\ServiceReportCreateCommand;
use App\SpotlightCommands\ServiceReportIndexCommand;
use App\SpotlightCommands\ServiceReportShowCommand;
use App\SpotlightCommands\TaskCreateCommand;
use App\SpotlightCommands\TaskIndexCommand;
use App\SpotlightCommands\TaskShowCommand;
use App\SpotlightCommands\UserSettingsEditCommand;
use App\SpotlightCommands\UserSettingsEditSecurityCommand;
use App\SpotlightCommands\VehicleCreateCommand;
use App\SpotlightCommands\VehicleIndexCommand;
use App\SpotlightCommands\VehicleShowCommand;
use App\SpotlightCommands\WageServiceCreateCommand;
use App\SpotlightCommands\WageServiceIndexCommand;
use App\SpotlightCommands\WageServiceShowCommand;

return [

    /*
    |--------------------------------------------------------------------------
    | Shortcuts
    |--------------------------------------------------------------------------
    |
    | Define which shortcuts will activate Spotlight CTRL / CMD + key
    | The default is CTRL/CMD + K or CTRL/CMD + /
    |
    */

    'shortcuts' => [
        'k',
        'slash',
        'space',
    ],

    /*
    |--------------------------------------------------------------------------
    | Commands
    |--------------------------------------------------------------------------
    |
    | Define which commands you want to make available in Spotlight.
    | Alternatively, you can also register commands in your AppServiceProvider
    | with the Spotlight::registerCommand(Logout::class); method.
    |
    */

    'commands' => [
        AccountingDownloadMonthlyReportCommand::class,
        AccountingIndexCommand::class,
        AdditionsReportCreateCommand::class,
        AdditionsReportIndexCommand::class,
        AdditionsReportShowCommand::class,
        AddressCreateCommand::class,
        AddressIndexCommand::class,
        ApplicationSettingsEditCommand::class,
        ChangelogShowCommand::class,
        CompanyCreateCommand::class,
        CompanyIndexCommand::class,
        CompanyShowCommand::class,
        ConstructionReportCreateCommand::class,
        ConstructionReportIndexCommand::class,
        ConstructionReportShowCommand::class,
        EmployeeCreateCommand::class,
        EmployeeIndexCommand::class,
        EmployeeShowCommand::class,
        EmployeeStartImpersonationCommand::class,
        EmployeeStopImpersonationCommand::class,
        ExceptionIndexCommand::class,
        ExceptionShowCommand::class,
        FinanceIndexCommand::class,
        FinanceGroupCreateCommand::class,
        FinanceGroupIndexCommand::class,
        FinanceGroupShowCommand::class,
        FlowMeterInspectionReportCreateCommand::class,
        FlowMeterInspectionReportIndexCommand::class,
        FlowMeterInspectionReportShowCommand::class,
        GlobalSearchCommand::class,
        HelpIndexCommand::class,
        HelpShowCommand::class,
        HomeShowCommand::class,
        InspectionReportCreateCommand::class,
        InspectionReportIndexCommand::class,
        InspectionReportShowCommand::class,
        LatestChangesIndexCommand::class,
        LogbookIndexCommand::class,
        LogoutCommand::class,
        MaterialServiceCreateCommand::class,
        MaterialServiceIndexCommand::class,
        MaterialServiceShowCommand::class,
        MemoCreateCommand::class,
        MemoIndexCommand::class,
        MemoShowCommand::class,
        NoteCreateCommand::class,
        NoteIndexCommand::class,
        NoteShowCommand::class,
        NotificationIndexCommand::class,
        PersonCreateCommand::class,
        PersonIndexCommand::class,
        PersonShowCommand::class,
        ProjectCreateCommand::class,
        ProjectIndexCommand::class,
        ProjectShowCommand::class,
        QrScanIndexCommand::class,
        RoleCreateCommand::class,
        RoleIndexCommand::class,
        RoleShowCommand::class,
        SentEmailIndexCommand::class,
        ServiceReportCreateCommand::class,
        ServiceReportIndexCommand::class,
        ServiceReportShowCommand::class,
        TaskCreateCommand::class,
        TaskIndexCommand::class,
        TaskShowCommand::class,
        UserSettingsEditCommand::class,
        UserSettingsEditSecurityCommand::class,
        VehicleCreateCommand::class,
        VehicleIndexCommand::class,
        VehicleShowCommand::class,
        WageServiceCreateCommand::class,
        WageServiceIndexCommand::class,
        WageServiceShowCommand::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Include CSS
    |--------------------------------------------------------------------------
    |
    | Spotlight uses TailwindCSS, if you don't use TailwindCSS you will need
    | to set this parameter to true. This includes the modern-normalize css.
    |
    */
    'include_css' => false,


    /*
    |--------------------------------------------------------------------------
    | Include JS
    |--------------------------------------------------------------------------
    |
    | Spotlight will inject the required Javascript in your blade template.
    | If you want to bundle the required Javascript you can set this to false,
    | call 'npm install fuse.js' or 'yarn add fuse.js',
    | then add `require('vendor/livewire-ui/spotlight/resources/js/spotlight');`
    | to your script bundler like webpack.
    |
    */
    'include_js' => true,

];
