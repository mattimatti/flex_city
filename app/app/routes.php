<?php
use App\Acl;
// Routes

$app->get('/', "App\Action\HomeAction")
    ->setName('homepage')
    ->allow([
    Acl::GUEST
]);

$app->get('/admin', "App\Action\AdminAction")
    ->setName('admin')
    ->allow([
    Acl::ADMIN
]);

$app->any('/admin/events[/{action}]', "App\Action\AdminEventsAction")
    ->setName('admin_events')
    ->allow([
    Acl::ADMIN
]);

$app->any('/admin/leads', "App\Action\AdminLeadsAction")
    ->setName('admin_leads')
    ->allow([
    Acl::ADMIN
]);

$app->any('/admin/hostess', "App\Action\AdminHostessAction")
    ->setName('admin_leads')
    ->allow([
    Acl::ADMIN
]);


$app->any('/admin/exporter/{resource}', "App\Action\AdminExportAction")
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




