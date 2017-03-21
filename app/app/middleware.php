<?php
// Application middleware
$container = $app->getContainer();

$app->add($container->get('csrf'));

$app->add($container->get('sessionMiddleware'));



