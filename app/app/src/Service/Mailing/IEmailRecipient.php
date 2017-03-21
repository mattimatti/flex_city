<?php
namespace App\Service\Mailing;

interface IEmailRecipient
{

    function getEmail();

    function getLabel();
}
