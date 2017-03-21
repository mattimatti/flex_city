<?php
namespace App\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DefaultCommand extends Command
{

    protected function configure()
    {
        $this->setName("app:default");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Hello World');
    }
}
