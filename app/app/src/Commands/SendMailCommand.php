<?php
namespace App\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Pimple\Container;
use App\Service\MailService;

class SendMailCommand extends AbstractCommand
{

    /**
     *
     * @var Container
     */
    private $pimple;

    /**
     *
     * @param Container $pimple            
     */
    function __construct($pimple)
    {
        $this->pimple = $pimple;
        
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('app:send:mail')
            ->setDescription('Send Email')
            ->setHelp('Send Email');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $parameters = array();
        
        /* @var $mailService MailService */
        $mailService = $this->pimple->get('mailService');
        $mailService->send($parameters);
    }
}
