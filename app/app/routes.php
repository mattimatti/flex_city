<?php
use App\Acl;
// Routes

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
    ->setName('admin_hostess')
    ->allow([
    Acl::ADMIN
]);

$app->any('/admin/hostess/edit/id/{id}', "App\Action\Admin\HostessAction")
    ->setName('hostess_edit')
    ->allow([
    Acl::ADMIN
]);

$app->any('/admin/event_user', "App\Action\Admin\EventUserAction")
    ->setName('event_user')
    ->allow([
    Acl::ADMIN
]);

$app->any('/admin/stores', "App\Action\Admin\StoresAction")
    ->setName('admin_stores')
    ->allow([
    Acl::ADMIN
]);


$app->any('/admin/stores/edit/id/{id}', "App\Action\Admin\StoresAction")
->setName('stores_edit')
->allow([
    Acl::ADMIN
    ]);

$app->any('/admin/locations', "App\Action\Admin\LocationsAction")
    ->setName('admin_locations')
    ->allow([
    Acl::ADMIN
]);

$app->any('/admin/locations/edit/id/{id}', "App\Action\Admin\LocationsAction")
    ->setName('locations_edit')
    ->allow([
    Acl::ADMIN
]);

$app->any('/admin/exporter/{resource}[/{event_id}]', "App\Action\Admin\ExportAction")
    ->setName('admin_export')
    ->allow([
    Acl::ADMIN
]);

$app->any('/unsubscribe[/{email}]', "App\Action\LeadUnsubscribeAction")
    ->setName('lead_unsubscribe')
    ->allow([
    Acl::GUEST
]);

// ////////////////////////////////////////////////////////////////////
// AUTH
// ////////////////////////////////////////////////////////////////////

$app->any('/hostess', "App\Action\HostessEventSelectionAction")
    ->setName('hostess')
    ->allow([
    Acl::HOSTESS
]);

$app->any('/hostess/register', "App\Action\LeadRegisterAction")
    ->setName('hostess_register')
    ->allow([
    Acl::HOSTESS
]);

// ////////////////////////////////////////////////////////////////////
// AUTH
// ////////////////////////////////////////////////////////////////////

$app->any('/auth/login', "App\Action\Auth\LoginAction")
    ->setName('login')
    ->allow([
    Acl::GUEST
]);

$app->get('/auth/logout', "App\Action\Auth\LogoutAction")
    ->setName('logout')
    ->allow([
    Acl::GUEST
]);

// ////////////////////////////////////////////////////////////////////
// PUBLIC
// ////////////////////////////////////////////////////////////////////

$app->get('/', "App\Action\HomeAction")
    ->setName('root')
    ->allow([
    Acl::GUEST
]);

$app->post('/', "App\Action\HomeRegisterAction")
    ->setName('homepage-post')
    ->allow([
    Acl::GUEST
]);

$app->get('/pages/{page}', "App\Action\PagesAction")
    ->setName('page_custom')
    ->allow([
    Acl::GUEST
]);

$app->get('/lang/{locale}', "App\Action\LangDriverAction")
    ->setName('page_lang')
    ->allow([
    Acl::GUEST
]);

$app->get('/mail/preview', "App\Action\MailPreviewAction")
    ->setName('mail_preview')
    ->allow([
    Acl::GUEST
]);
    
    


// $app->get('/event/{permalink}/signup', "App\Action\EventSignupAction")
//     ->setName('landing')
//     ->allow([
//     Acl::GUEST
// ]);




