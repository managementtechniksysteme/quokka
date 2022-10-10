<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ***
        // Permissions
        // ***

        // accounting
        Permission::firstOrCreate(['name' => 'accounting.view.own']);
        Permission::firstOrCreate(['name' => 'accounting.view.other']);
        Permission::firstOrCreate(['name' => 'accounting.create']);
        Permission::firstOrCreate(['name' => 'accounting.update.own']);
        Permission::firstOrCreate(['name' => 'accounting.update.other']);
        Permission::firstOrCreate(['name' => 'accounting.delete.own']);
        Permission::firstOrCreate(['name' => 'accounting.delete.other']);
        Permission::firstOrCreate(['name' => 'accounting.email']);
        Permission::firstOrCreate(['name' => 'accounting.createpdf']);

        // additions reports
        Permission::firstOrCreate(['name' => 'additions-reports.view.own']);
        Permission::firstOrCreate(['name' => 'additions-reports.view.involved']);
        Permission::firstOrCreate(['name' => 'additions-reports.view.other']);
        Permission::firstOrCreate(['name' => 'additions-reports.create']);
        Permission::firstOrCreate(['name' => 'additions-reports.update.own']);
        Permission::firstOrCreate(['name' => 'additions-reports.update.involved']);
        Permission::firstOrCreate(['name' => 'additions-reports.update.other']);
        Permission::firstOrCreate(['name' => 'additions-reports.delete.own']);
        Permission::firstOrCreate(['name' => 'additions-reports.delete.involved']);
        Permission::firstOrCreate(['name' => 'additions-reports.delete.other']);
        Permission::firstOrCreate(['name' => 'additions-reports.email.own']);
        Permission::firstOrCreate(['name' => 'additions-reports.email.involved']);
        Permission::firstOrCreate(['name' => 'additions-reports.email.other']);
        Permission::firstOrCreate(['name' => 'additions-reports.createpdf.own']);
        Permission::firstOrCreate(['name' => 'additions-reports.createpdf.involved']);
        Permission::firstOrCreate(['name' => 'additions-reports.createpdf.other']);
        Permission::firstOrCreate(['name' => 'additions-reports.send-signature-request.own']);
        Permission::firstOrCreate(['name' => 'additions-reports.send-signature-request.involved']);
        Permission::firstOrCreate(['name' => 'additions-reports.send-signature-request.other']);
        Permission::firstOrCreate(['name' => 'additions-reports.send-download-request.own']);
        Permission::firstOrCreate(['name' => 'additions-reports.send-download-request.involved']);
        Permission::firstOrCreate(['name' => 'additions-reports.send-download-request.other']);
        Permission::firstOrCreate(['name' => 'additions-reports.get-signature.own']);
        Permission::firstOrCreate(['name' => 'additions-reports.get-signature.involved']);
        Permission::firstOrCreate(['name' => 'additions-reports.get-signature.other']);
        Permission::firstOrCreate(['name' => 'additions-reports.approve']);

        // addresses
        Permission::firstOrCreate(['name' => 'addresses.view']);
        Permission::firstOrCreate(['name' => 'addresses.create']);
        Permission::firstOrCreate(['name' => 'addresses.update']);
        Permission::firstOrCreate(['name' => 'addresses.delete']);
        Permission::firstOrCreate(['name' => 'addresses.email']);
        Permission::firstOrCreate(['name' => 'addresses.createpdf']);

        // application settings
        Permission::firstOrCreate(['name' => 'application-settings.update.general']);

        // companies
        Permission::firstOrCreate(['name' => 'companies.view']);
        Permission::firstOrCreate(['name' => 'companies.create']);
        Permission::firstOrCreate(['name' => 'companies.update']);
        Permission::firstOrCreate(['name' => 'companies.delete']);
        Permission::firstOrCreate(['name' => 'companies.email']);
        Permission::firstOrCreate(['name' => 'companies.createpdf']);

        // construction reports
        Permission::firstOrCreate(['name' => 'construction-reports.view.own']);
        Permission::firstOrCreate(['name' => 'construction-reports.view.involved']);
        Permission::firstOrCreate(['name' => 'construction-reports.view.other']);
        Permission::firstOrCreate(['name' => 'construction-reports.create']);
        Permission::firstOrCreate(['name' => 'construction-reports.update.own']);
        Permission::firstOrCreate(['name' => 'construction-reports.update.involved']);
        Permission::firstOrCreate(['name' => 'construction-reports.update.other']);
        Permission::firstOrCreate(['name' => 'construction-reports.delete.own']);
        Permission::firstOrCreate(['name' => 'construction-reports.delete.involved']);
        Permission::firstOrCreate(['name' => 'construction-reports.delete.other']);
        Permission::firstOrCreate(['name' => 'construction-reports.email.own']);
        Permission::firstOrCreate(['name' => 'construction-reports.email.involved']);
        Permission::firstOrCreate(['name' => 'construction-reports.email.other']);
        Permission::firstOrCreate(['name' => 'construction-reports.createpdf.own']);
        Permission::firstOrCreate(['name' => 'construction-reports.createpdf.involved']);
        Permission::firstOrCreate(['name' => 'construction-reports.createpdf.other']);
        Permission::firstOrCreate(['name' => 'construction-reports.send-signature-request.own']);
        Permission::firstOrCreate(['name' => 'construction-reports.send-signature-request.involved']);
        Permission::firstOrCreate(['name' => 'construction-reports.send-signature-request.other']);
        Permission::firstOrCreate(['name' => 'construction-reports.send-download-request.own']);
        Permission::firstOrCreate(['name' => 'construction-reports.send-download-request.involved']);
        Permission::firstOrCreate(['name' => 'construction-reports.send-download-request.other']);
        Permission::firstOrCreate(['name' => 'construction-reports.get-signature.own']);
        Permission::firstOrCreate(['name' => 'construction-reports.get-signature.involved']);
        Permission::firstOrCreate(['name' => 'construction-reports.get-signature.other']);
        Permission::firstOrCreate(['name' => 'construction-reports.approve']);

        // employees
        Permission::firstOrCreate(['name' => 'employees.view']);
        Permission::firstOrCreate(['name' => 'employees.create']);
        Permission::firstOrCreate(['name' => 'employees.update']);
        Permission::firstOrCreate(['name' => 'employees.delete']);
        Permission::firstOrCreate(['name' => 'employees.email']);
        Permission::firstOrCreate(['name' => 'employees.createpdf']);
        Permission::firstOrCreate(['name' => 'employees.impersonate']);

        // exceptions
        Permission::firstOrCreate(['name' => 'exceptions.view']);
        Permission::firstOrCreate(['name' => 'exceptions.delete']);

        // flow meter inspection reports
        Permission::firstOrCreate(['name' => 'flow-meter-inspection-reports.view.own']);
        Permission::firstOrCreate(['name' => 'flow-meter-inspection-reports.view.other']);
        Permission::firstOrCreate(['name' => 'flow-meter-inspection-reports.create']);
        Permission::firstOrCreate(['name' => 'flow-meter-inspection-reports.update.own']);
        Permission::firstOrCreate(['name' => 'flow-meter-inspection-reports.update.other']);
        Permission::firstOrCreate(['name' => 'flow-meter-inspection-reports.delete.own']);
        Permission::firstOrCreate(['name' => 'flow-meter-inspection-reports.delete.other']);
        Permission::firstOrCreate(['name' => 'flow-meter-inspection-reports.email.own']);
        Permission::firstOrCreate(['name' => 'flow-meter-inspection-reports.email.other']);
        Permission::firstOrCreate(['name' => 'flow-meter-inspection-reports.createpdf.own']);
        Permission::firstOrCreate(['name' => 'flow-meter-inspection-reports.createpdf.other']);
        Permission::firstOrCreate(['name' => 'flow-meter-inspection-reports.send-signature-request.own']);
        Permission::firstOrCreate(['name' => 'flow-meter-inspection-reports.send-signature-request.other']);
        Permission::firstOrCreate(['name' => 'flow-meter-inspection-reports.send-download-request.own']);
        Permission::firstOrCreate(['name' => 'flow-meter-inspection-reports.send-download-request.other']);
        Permission::firstOrCreate(['name' => 'flow-meter-inspection-reports.get-signature.own']);
        Permission::firstOrCreate(['name' => 'flow-meter-inspection-reports.get-signature.other']);
        Permission::firstOrCreate(['name' => 'flow-meter-inspection-reports.approve']);

        // help
        Permission::firstOrCreate(['name' => 'help.view']);

        // inspection reports
        Permission::firstOrCreate(['name' => 'inspection-reports.view.own']);
        Permission::firstOrCreate(['name' => 'inspection-reports.view.other']);
        Permission::firstOrCreate(['name' => 'inspection-reports.create']);
        Permission::firstOrCreate(['name' => 'inspection-reports.update.own']);
        Permission::firstOrCreate(['name' => 'inspection-reports.update.other']);
        Permission::firstOrCreate(['name' => 'inspection-reports.delete.own']);
        Permission::firstOrCreate(['name' => 'inspection-reports.delete.other']);
        Permission::firstOrCreate(['name' => 'inspection-reports.email.own']);
        Permission::firstOrCreate(['name' => 'inspection-reports.email.other']);
        Permission::firstOrCreate(['name' => 'inspection-reports.createpdf.own']);
        Permission::firstOrCreate(['name' => 'inspection-reports.createpdf.other']);
        Permission::firstOrCreate(['name' => 'inspection-reports.send-signature-request.own']);
        Permission::firstOrCreate(['name' => 'inspection-reports.send-signature-request.other']);
        Permission::firstOrCreate(['name' => 'inspection-reports.send-download-request.own']);
        Permission::firstOrCreate(['name' => 'inspection-reports.send-download-request.other']);
        Permission::firstOrCreate(['name' => 'inspection-reports.get-signature.own']);
        Permission::firstOrCreate(['name' => 'inspection-reports.get-signature.other']);
        Permission::firstOrCreate(['name' => 'inspection-reports.approve']);

        // logbook
        Permission::firstOrCreate(['name' => 'logbook.view.own']);
        Permission::firstOrCreate(['name' => 'logbook.view.other']);
        Permission::firstOrCreate(['name' => 'logbook.create']);
        Permission::firstOrCreate(['name' => 'logbook.update.own']);
        Permission::firstOrCreate(['name' => 'logbook.update.other']);
        Permission::firstOrCreate(['name' => 'logbook.delete.own']);
        Permission::firstOrCreate(['name' => 'logbook.delete.other']);
        Permission::firstOrCreate(['name' => 'logbook.email']);
        Permission::firstOrCreate(['name' => 'logbook.createpdf']);

        // material services
        Permission::firstOrCreate(['name' => 'material-services.view']);
        Permission::firstOrCreate(['name' => 'material-services.create']);
        Permission::firstOrCreate(['name' => 'material-services.update']);
        Permission::firstOrCreate(['name' => 'material-services.delete']);
        Permission::firstOrCreate(['name' => 'material-services.email']);
        Permission::firstOrCreate(['name' => 'material-services.createpdf']);

        // memos
        Permission::firstOrCreate(['name' => 'memos.view.sender']);
        Permission::firstOrCreate(['name' => 'memos.view.recipient']);
        Permission::firstOrCreate(['name' => 'memos.view.present']);
        Permission::firstOrCreate(['name' => 'memos.view.notified']);
        Permission::firstOrCreate(['name' => 'memos.view.other']);
        Permission::firstOrCreate(['name' => 'memos.create']);
        Permission::firstOrCreate(['name' => 'memos.update.sender']);
        Permission::firstOrCreate(['name' => 'memos.update.recipient']);
        Permission::firstOrCreate(['name' => 'memos.update.present']);
        Permission::firstOrCreate(['name' => 'memos.update.notified']);
        Permission::firstOrCreate(['name' => 'memos.update.other']);
        Permission::firstOrCreate(['name' => 'memos.delete.sender']);
        Permission::firstOrCreate(['name' => 'memos.delete.recipient']);
        Permission::firstOrCreate(['name' => 'memos.delete.present']);
        Permission::firstOrCreate(['name' => 'memos.delete.notified']);
        Permission::firstOrCreate(['name' => 'memos.delete.other']);
        Permission::firstOrCreate(['name' => 'memos.email.sender']);
        Permission::firstOrCreate(['name' => 'memos.email.recipient']);
        Permission::firstOrCreate(['name' => 'memos.email.present']);
        Permission::firstOrCreate(['name' => 'memos.email.notified']);
        Permission::firstOrCreate(['name' => 'memos.email.other']);
        Permission::firstOrCreate(['name' => 'memos.createpdf.sender']);
        Permission::firstOrCreate(['name' => 'memos.createpdf.recipient']);
        Permission::firstOrCreate(['name' => 'memos.createpdf.present']);
        Permission::firstOrCreate(['name' => 'memos.createpdf.notified']);
        Permission::firstOrCreate(['name' => 'memos.createpdf.other']);

        // people
        Permission::firstOrCreate(['name' => 'people.view']);
        Permission::firstOrCreate(['name' => 'people.create']);
        Permission::firstOrCreate(['name' => 'people.update']);
        Permission::firstOrCreate(['name' => 'people.delete']);
        Permission::firstOrCreate(['name' => 'people.email']);
        Permission::firstOrCreate(['name' => 'people.createpdf']);

        // projects
        Permission::firstOrCreate(['name' => 'projects.view']);
        Permission::firstOrCreate(['name' => 'projects.view.estimates']);
        Permission::firstOrCreate(['name' => 'projects.create']);
        Permission::firstOrCreate(['name' => 'projects.update']);
        Permission::firstOrCreate(['name' => 'projects.delete']);
        Permission::firstOrCreate(['name' => 'projects.email']);
        Permission::firstOrCreate(['name' => 'projects.createpdf']);

        // project interim invoices
        Permission::firstOrCreate(['name' => 'interim-invoices.view']);
        Permission::firstOrCreate(['name' => 'interim-invoices.create']);
        Permission::firstOrCreate(['name' => 'interim-invoices.update']);
        Permission::firstOrCreate(['name' => 'interim-invoices.delete']);

        // roles
        Permission::firstOrCreate(['name' => 'roles.view']);
        Permission::firstOrCreate(['name' => 'roles.create']);
        Permission::firstOrCreate(['name' => 'roles.update']);
        Permission::firstOrCreate(['name' => 'roles.delete']);
        Permission::firstOrCreate(['name' => 'roles.email']);
        Permission::firstOrCreate(['name' => 'roles.createpdf']);

        // search
        Permission::firstOrCreate(['name' => 'search']);

        // service reports
        Permission::firstOrCreate(['name' => 'service-reports.view.own']);
        Permission::firstOrCreate(['name' => 'service-reports.view.other']);
        Permission::firstOrCreate(['name' => 'service-reports.create']);
        Permission::firstOrCreate(['name' => 'service-reports.update.own']);
        Permission::firstOrCreate(['name' => 'service-reports.update.other']);
        Permission::firstOrCreate(['name' => 'service-reports.delete.own']);
        Permission::firstOrCreate(['name' => 'service-reports.delete.other']);
        Permission::firstOrCreate(['name' => 'service-reports.email.own']);
        Permission::firstOrCreate(['name' => 'service-reports.email.other']);
        Permission::firstOrCreate(['name' => 'service-reports.createpdf.own']);
        Permission::firstOrCreate(['name' => 'service-reports.createpdf.other']);
        Permission::firstOrCreate(['name' => 'service-reports.send-signature-request.own']);
        Permission::firstOrCreate(['name' => 'service-reports.send-signature-request.other']);
        Permission::firstOrCreate(['name' => 'service-reports.send-download-request.own']);
        Permission::firstOrCreate(['name' => 'service-reports.send-download-request.other']);
        Permission::firstOrCreate(['name' => 'service-reports.get-signature.own']);
        Permission::firstOrCreate(['name' => 'service-reports.get-signature.other']);
        Permission::firstOrCreate(['name' => 'service-reports.approve']);

        // tasks
        Permission::firstOrCreate(['name' => 'tasks.view.responsible']);
        Permission::firstOrCreate(['name' => 'tasks.view.involved']);
        Permission::firstOrCreate(['name' => 'tasks.view.other']);
        Permission::firstOrCreate(['name' => 'tasks.view.private.responsible']);
        Permission::firstOrCreate(['name' => 'tasks.view.private.involved']);
        Permission::firstOrCreate(['name' => 'tasks.view.private.other']);
        Permission::firstOrCreate(['name' => 'tasks.create']);
        Permission::firstOrCreate(['name' => 'tasks.create.private']);
        Permission::firstOrCreate(['name' => 'tasks.update.responsible']);
        Permission::firstOrCreate(['name' => 'tasks.update.involved']);
        Permission::firstOrCreate(['name' => 'tasks.update.other']);
        Permission::firstOrCreate(['name' => 'tasks.update.private.responsible']);
        Permission::firstOrCreate(['name' => 'tasks.update.private.involved']);
        Permission::firstOrCreate(['name' => 'tasks.update.private.other']);
        Permission::firstOrCreate(['name' => 'tasks.delete.responsible']);
        Permission::firstOrCreate(['name' => 'tasks.delete.involved']);
        Permission::firstOrCreate(['name' => 'tasks.delete.other']);
        Permission::firstOrCreate(['name' => 'tasks.delete.private.responsible']);
        Permission::firstOrCreate(['name' => 'tasks.delete.private.involved']);
        Permission::firstOrCreate(['name' => 'tasks.delete.private.other']);
        Permission::firstOrCreate(['name' => 'tasks.email.responsible']);
        Permission::firstOrCreate(['name' => 'tasks.email.involved']);
        Permission::firstOrCreate(['name' => 'tasks.email.other']);
        Permission::firstOrCreate(['name' => 'tasks.email.private.responsible']);
        Permission::firstOrCreate(['name' => 'tasks.email.private.involved']);
        Permission::firstOrCreate(['name' => 'tasks.email.private.other']);
        Permission::firstOrCreate(['name' => 'tasks.createpdf.responsible']);
        Permission::firstOrCreate(['name' => 'tasks.createpdf.involved']);
        Permission::firstOrCreate(['name' => 'tasks.createpdf.other']);
        Permission::firstOrCreate(['name' => 'tasks.createpdf.private.responsible']);
        Permission::firstOrCreate(['name' => 'tasks.createpdf.private.involved']);
        Permission::firstOrCreate(['name' => 'tasks.createpdf.private.other']);

        // task comments
        Permission::firstOrCreate(['name' => 'tasks.comments.create']);
        Permission::firstOrCreate(['name' => 'tasks.comments.update.own']);
        Permission::firstOrCreate(['name' => 'tasks.comments.update.other']);
        Permission::firstOrCreate(['name' => 'tasks.comments.delete.own']);
        Permission::firstOrCreate(['name' => 'tasks.comments.delete.other']);

        // tools
        Permission::firstOrCreate(['name' => 'tools.viewlatestchanges']);
        Permission::firstOrCreate(['name' => 'tools.viewsentemails']);
        Permission::firstOrCreate(['name' => 'tools.scanqr']);

        // user settings

        // vehicles
        Permission::firstOrCreate(['name' => 'vehicles.view']);
        Permission::firstOrCreate(['name' => 'vehicles.create']);
        Permission::firstOrCreate(['name' => 'vehicles.update']);
        Permission::firstOrCreate(['name' => 'vehicles.delete']);
        Permission::firstOrCreate(['name' => 'vehicles.email']);
        Permission::firstOrCreate(['name' => 'vehicles.createpdf']);

        // wage services
        Permission::firstOrCreate(['name' => 'wage-services.view']);
        Permission::firstOrCreate(['name' => 'wage-services.create']);
        Permission::firstOrCreate(['name' => 'wage-services.update']);
        Permission::firstOrCreate(['name' => 'wage-services.delete']);
        Permission::firstOrCreate(['name' => 'wage-services.email']);
        Permission::firstOrCreate(['name' => 'wage-services.createpdf']);

        // ***
        // Roles
        // ***

        // Administrator role template
        $admin = Role::firstOrCreate(['name' => 'Administrator']);
        $admin->givePermissionTo(Permission::all());

        // Employee role template
        $employee = Role::firstOrCreate(['name' => 'Mitarbeiter']);
        $employee->revokePermissionTo(Permission::all());

        // accounting
        $employee->givePermissionTo('accounting.view.own');
        $employee->givePermissionTo('accounting.create');
        $employee->givePermissionTo('accounting.update.own');
        $employee->givePermissionTo('accounting.delete.own');
        $employee->givePermissionTo('accounting.email');
        $employee->givePermissionTo('accounting.createpdf');

        // additions reports
        $employee->givePermissionTo('additions-reports.view.own');
        $employee->givePermissionTo('additions-reports.view.involved');
        $employee->givePermissionTo('additions-reports.view.other');
        $employee->givePermissionTo('additions-reports.create');
        $employee->givePermissionTo('additions-reports.update.own');
        $employee->givePermissionTo('additions-reports.delete.own');
        $employee->givePermissionTo('additions-reports.email.own');
        $employee->givePermissionTo('additions-reports.email.involved');
        $employee->givePermissionTo('additions-reports.email.other');
        $employee->givePermissionTo('additions-reports.createpdf.own');
        $employee->givePermissionTo('additions-reports.createpdf.involved');
        $employee->givePermissionTo('additions-reports.createpdf.other');
        $employee->givePermissionTo('additions-reports.send-signature-request.own');
        $employee->givePermissionTo('additions-reports.send-signature-request.involved');
        $employee->givePermissionTo('additions-reports.send-signature-request.other');
        $employee->givePermissionTo('additions-reports.send-download-request.own');
        $employee->givePermissionTo('additions-reports.send-download-request.involved');
        $employee->givePermissionTo('additions-reports.send-download-request.other');
        $employee->givePermissionTo('additions-reports.get-signature.own');
        $employee->givePermissionTo('additions-reports.get-signature.involved');
        $employee->givePermissionTo('additions-reports.get-signature.other');

        // addresses
        $employee->givePermissionTo('addresses.view');
        $employee->givePermissionTo('addresses.create');
        $employee->givePermissionTo('addresses.update');
        $employee->givePermissionTo('addresses.email');
        $employee->givePermissionTo('addresses.createpdf');

        // comments
        // permissions on task comments are based on task permissions for now

        // companies
        $employee->givePermissionTo('companies.view');
        $employee->givePermissionTo('companies.create');
        $employee->givePermissionTo('companies.update');
        $employee->givePermissionTo('companies.email');
        $employee->givePermissionTo('companies.createpdf');

        // construction reports
        $employee->givePermissionTo('construction-reports.view.own');
        $employee->givePermissionTo('construction-reports.view.involved');
        $employee->givePermissionTo('construction-reports.view.other');
        $employee->givePermissionTo('construction-reports.create');
        $employee->givePermissionTo('construction-reports.update.own');
        $employee->givePermissionTo('construction-reports.delete.own');
        $employee->givePermissionTo('construction-reports.email.own');
        $employee->givePermissionTo('construction-reports.email.involved');
        $employee->givePermissionTo('construction-reports.email.other');
        $employee->givePermissionTo('construction-reports.createpdf.own');
        $employee->givePermissionTo('construction-reports.createpdf.involved');
        $employee->givePermissionTo('construction-reports.createpdf.other');
        $employee->givePermissionTo('construction-reports.send-signature-request.own');
        $employee->givePermissionTo('construction-reports.send-signature-request.involved');
        $employee->givePermissionTo('construction-reports.send-signature-request.other');
        $employee->givePermissionTo('construction-reports.send-download-request.own');
        $employee->givePermissionTo('construction-reports.send-download-request.involved');
        $employee->givePermissionTo('construction-reports.send-download-request.other');
        $employee->givePermissionTo('construction-reports.get-signature.own');
        $employee->givePermissionTo('construction-reports.get-signature.involved');
        $employee->givePermissionTo('construction-reports.get-signature.other');

        // flow meter inspection reports
        $employee->givePermissionTo('flow-meter-inspection-reports.view.own');
        $employee->givePermissionTo('flow-meter-inspection-reports.view.other');
        $employee->givePermissionTo('flow-meter-inspection-reports.create');
        $employee->givePermissionTo('flow-meter-inspection-reports.update.own');
        $employee->givePermissionTo('flow-meter-inspection-reports.delete.own');
        $employee->givePermissionTo('flow-meter-inspection-reports.email.own');
        $employee->givePermissionTo('flow-meter-inspection-reports.email.other');
        $employee->givePermissionTo('flow-meter-inspection-reports.createpdf.own');
        $employee->givePermissionTo('flow-meter-inspection-reports.createpdf.other');
        $employee->givePermissionTo('flow-meter-inspection-reports.send-signature-request.own');
        $employee->givePermissionTo('flow-meter-inspection-reports.send-signature-request.other');
        $employee->givePermissionTo('flow-meter-inspection-reports.send-download-request.own');
        $employee->givePermissionTo('flow-meter-inspection-reports.send-download-request.other');
        $employee->givePermissionTo('flow-meter-inspection-reports.get-signature.own');
        $employee->givePermissionTo('flow-meter-inspection-reports.get-signature.other');

        // help
        $employee->givePermissionTo('help.view');

        // inspection reports
        $employee->givePermissionTo('inspection-reports.view.own');
        $employee->givePermissionTo('inspection-reports.view.other');
        $employee->givePermissionTo('inspection-reports.create');
        $employee->givePermissionTo('inspection-reports.update.own');
        $employee->givePermissionTo('inspection-reports.delete.own');
        $employee->givePermissionTo('inspection-reports.email.own');
        $employee->givePermissionTo('inspection-reports.email.other');
        $employee->givePermissionTo('inspection-reports.createpdf.own');
        $employee->givePermissionTo('inspection-reports.createpdf.other');
        $employee->givePermissionTo('inspection-reports.send-signature-request.own');
        $employee->givePermissionTo('inspection-reports.send-signature-request.other');
        $employee->givePermissionTo('inspection-reports.send-download-request.own');
        $employee->givePermissionTo('inspection-reports.send-download-request.other');
        $employee->givePermissionTo('inspection-reports.get-signature.own');
        $employee->givePermissionTo('inspection-reports.get-signature.other');

        // logbook
        $employee->givePermissionTo('logbook.view.own');
        $employee->givePermissionTo('logbook.view.other');
        $employee->givePermissionTo('logbook.create');
        $employee->givePermissionTo('logbook.update.own');
        $employee->givePermissionTo('logbook.delete.own');
        $employee->givePermissionTo('logbook.email');
        $employee->givePermissionTo('logbook.createpdf');

        // memos
        $employee->givePermissionTo('memos.view.sender');
        $employee->givePermissionTo('memos.view.recipient');
        $employee->givePermissionTo('memos.view.present');
        $employee->givePermissionTo('memos.view.notified');
        $employee->givePermissionTo('memos.view.other');
        $employee->givePermissionTo('memos.create');
        $employee->givePermissionTo('memos.update.sender');
        $employee->givePermissionTo('memos.delete.sender');
        $employee->givePermissionTo('memos.email.sender');
        $employee->givePermissionTo('memos.createpdf.sender');

        // people
        $employee->givePermissionTo('people.view');
        $employee->givePermissionTo('people.create');
        $employee->givePermissionTo('people.update');
        $employee->givePermissionTo('people.email');
        $employee->givePermissionTo('people.createpdf');

        // projects
        $employee->givePermissionTo('projects.view');
        $employee->givePermissionTo('projects.create');
        $employee->givePermissionTo('projects.update');
        $employee->givePermissionTo('projects.email');

        // search
        $employee->givePermissionTo('search');

        // service reports
        $employee->givePermissionTo('service-reports.view.own');
        $employee->givePermissionTo('service-reports.view.other');
        $employee->givePermissionTo('service-reports.create');
        $employee->givePermissionTo('service-reports.update.own');
        $employee->givePermissionTo('service-reports.delete.own');
        $employee->givePermissionTo('service-reports.email.own');
        $employee->givePermissionTo('service-reports.email.other');
        $employee->givePermissionTo('service-reports.createpdf.own');
        $employee->givePermissionTo('service-reports.createpdf.other');
        $employee->givePermissionTo('service-reports.send-signature-request.own');
        $employee->givePermissionTo('service-reports.send-signature-request.other');
        $employee->givePermissionTo('service-reports.send-download-request.own');
        $employee->givePermissionTo('service-reports.send-download-request.other');
        $employee->givePermissionTo('service-reports.get-signature.own');
        $employee->givePermissionTo('service-reports.get-signature.other');

        // tasks
        $employee->givePermissionTo('tasks.view.responsible');
        $employee->givePermissionTo('tasks.view.involved');
        $employee->givePermissionTo('tasks.view.other');
        $employee->givePermissionTo('tasks.view.private.responsible');
        $employee->givePermissionTo('tasks.view.private.involved');
        $employee->givePermissionTo('tasks.create');
        $employee->givePermissionTo('tasks.create.private');
        $employee->givePermissionTo('tasks.update.responsible');
        $employee->givePermissionTo('tasks.update.private.responsible');
        $employee->givePermissionTo('tasks.delete.responsible');
        $employee->givePermissionTo('tasks.delete.private.responsible');
        $employee->givePermissionTo('tasks.email.responsible');
        $employee->givePermissionTo('tasks.email.involved');
        $employee->givePermissionTo('tasks.email.other');
        $employee->givePermissionTo('tasks.email.private.responsible');
        $employee->givePermissionTo('tasks.email.private.involved');
        $employee->givePermissionTo('tasks.createpdf.responsible');
        $employee->givePermissionTo('tasks.createpdf.involved');
        $employee->givePermissionTo('tasks.createpdf.other');
        $employee->givePermissionTo('tasks.createpdf.private.responsible');
        $employee->givePermissionTo('tasks.createpdf.private.involved');

        // task comments
        $employee->givePermissionTo('tasks.comments.create');
        $employee->givePermissionTo('tasks.comments.update.own');

        // tools
        $employee->givePermissionTo('tools.scanqr');
    }
}
