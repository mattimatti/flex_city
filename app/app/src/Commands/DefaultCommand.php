<?php
namespace App\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DefaultCommand extends AbstractCommand
{

    protected function configure()
    {
        $this->setName("app:default");
    }

    /**
     * (non-PHPdoc)
     * 
     * @see \App\Commands\AbstractCommand::execute()
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Hello World');
    }
}
