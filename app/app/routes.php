<?php
use App\Acl;
// Routes

$app->get('/admin', "App\Action\Admin\IndexAction")
    ->setName('admin')
    ->allow([
    Acl::ADMIN
]);


$app->any('/admin/leads', "App\Action\Admin\LeadsAction")
    ->setName('admin_leads')
    ->allow([
    Acl::ADMIN
]);


// $app->any('/admin/exporter/{resource}[/{event_id}]', "App\Action\Admin\ExportAction")
//     ->setName('admin_export')
//     ->allow([
//     Acl::ADMIN
// ]);


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
