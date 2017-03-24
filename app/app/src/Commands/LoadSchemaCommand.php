<?php
namespace App\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DefaultCommand extends LoadFixturesCommand
{
    
    
    protected function configure()
    {
        $this->setName('app:load-schema')
        ->setDescription('Load schema in database')
        ->setHelp('This command allows you to load schema in database');
    
        $this->addArgument('domain', InputArgument::REQUIRED, 'the domain.');
    }
    
    

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        
        // outputs multiple lines to the console (adding "\n" at the end of each line)
        $output->writeln([
            'Load Fixtures',
            '============',
            ''
        ]);
        
        $domain = $input->getArgument('domain');
        $output->writeln('Domain: ' . $domain);
        
        $dbconfig = $this->getDbConfig($domain, $output);
        
        $output->writeln(print_r($dbconfig, 1));
        
        
        $out = system('echo from sys');
        $output->writeln($out);

        $out = system('mysql');
        $output->writeln($out);
        
    }
}
