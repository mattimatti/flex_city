<?php
use App\Acl;
// Routes

$app->get('/', "App\Action\HomeAction")
    ->setName('homepage')
    ->allow([
    Acl::GUEST
]);

$app->get('/admin', "App\Action\Admin\IndexAction")
    ->setName('admin')
    ->allow([
    Acl::ADMIN
]);

$app->any('/admin/events[/{action}]', "App\Action\Admin\EventsAction")
    ->setName('admin_events')
    ->allow([
    Acl::ADMIN
]);

$app->any('/admin/leads', "App\Action\Admin\LeadsAction")
    ->setName('admin_leads')
    ->allow([
    Acl::ADMIN
]);

$app->any('/admin/hostess', "App\Action\Admin\HostessAction")
    ->setName('admin_leads')
    ->allow([
    Acl::ADMIN
]);

$app->any('/admin/stores', "App\Action\Admin\StoresAction")
    ->setName('admin_stores')
    ->allow([
    Acl::ADMIN
]);

$app->any('/admin/locations', "App\Action\Admin\LocationsAction")
    ->setName('admin_locations')
    ->allow([
    Acl::ADMIN
]);


$app->any('/admin/exporter/{resource}[/{event_id}]', "App\Action\Admin\ExportAction")
    ->setName('admin_export')
    ->allow([
    Acl::ADMIN
]);

$app->any('/hostess', "App\Action\HostessEventSelectionAction")
    ->setName('hostess')
    ->allow([
    Acl::HOSTESS
]);

$app->any('/hostess/register', "App\Action\HostessLeadRegisterAction")
    ->setName('hostess_register')
    ->allow([
    Acl::HOSTESS
]);




//////////////////////////////////////////////////////////////////////


$app->any('/auth/login', "App\Action\LoginAction")
    ->setName('login')
    ->allow([
    Acl::GUEST
]);

$app->get('/auth/logout', "App\Action\LogoutAction")
    ->setName('logout')
    ->allow([
    Acl::GUEST
]);




//////////////////////////////////////////////////////////////////////


$app->get('/event/{permalink}', "App\Action\EventLandingAction")
    ->setName('landing')
    ->allow([
    Acl::GUEST
]);

$app->get('/event/{permalink}/signup', "App\Action\EventSignupAction")
    ->setName('landing')
    ->allow([
    Acl::GUEST
]);




