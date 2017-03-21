<?php
namespace App\Dao;

use App\Service\Mailing\IEmailRecipient;

class Sender implements IEmailRecipient
{

    /**
     *
     * @var array
     */
    protected $config;

    function __construct(array $config)
    {
        $this->config = $config;
    }
    
    /*
     * (non-PHPdoc) @see \App\Service\Mailing\IEmailRecipient::getEmail()
     */
    public function getEmail()
    {
        return $this->config['email'];
    }
    
    /*
     * (non-PHPdoc) @see \App\Service\Mailing\IEmailRecipient::getLabel()
     */
    public function getLabel()
    {
        return $this->config['label'];
    }
}