<?php
namespace App\Action;

use Slim\Views\Twig;
use Psr\Log\LoggerInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use App\RedbeanAdapter;
use marcelbonnet\Slim\Auth\Authenticator;
use App\Debug;
use App\Dao\EventRepository;

final class PagesAction extends HomeAction
{

}