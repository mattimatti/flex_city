<?php
namespace App\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Fixtures;

class LoadFixturesCommand extends Command
{

    protected function configure()
    {
        $this->setName('app:load-fixtures')
            ->setDescription('Load The fixtures in database')
            ->setHelp('This command allows you to create a user...');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $fixtures = new Fixtures(true);
        $fixtures->load();
        $fixtures->dump();
    }
}
