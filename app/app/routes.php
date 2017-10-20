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
    Acl::ADMIN
]);

$app->post('/', "App\Action\LeadRegisterAction")
    ->setName('homepage-post')
    ->allow([
    Acl::GUEST
]);
