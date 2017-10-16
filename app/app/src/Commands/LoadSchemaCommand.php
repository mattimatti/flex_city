<?php
namespace App\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class LoadSchemaCommand extends AbstractCommand
{

    protected function configure()
    {
        $this->setName('app:load-schema')
            ->setDescription('Load schema in database')
            ->setHelp('This command allows you to load schema in database');
        
        // $this->addArgument('domain', InputArgument::REQUIRED, 'the domain.');
    }

    /**
     * (non-PHPdoc)
     *
     * @see \App\Commands\AbstractCommand::execute()
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $domains = $this->getDomains();
        
        foreach ($domains as $domain) {
            
            // outputs multiple lines to the console (adding "\n" at the end of each line)
            $output->writeln([
                'Load Database Schema ',
                '============',
                ''
            ]);
            
            // $domain = $input->getArgument('domain');
            $output->writeln('Domain: ' . $domain);
            
            $dbconfig = $this->getDbConfig($domain, $output);
            
            $output->writeln(print_r($dbconfig, 1));
            
            // $out = @system('echo from sys');
            // $output->writeln($out);
            
            $user = $dbconfig['user'];
            $password = $dbconfig['password'];
            $dbname = $dbconfig['dbname'];
            
            $out = @system("mysql -u$user -p$password $dbname < data/structure.sql");
            $output->writeln($out);
            
            $output->writeln("Schema loaded");
            
            
//             $out = @system("mysql -u$user -p$password $dbname < data/permissions.sql");
//             $output->writeln($out);
            
//             $output->writeln("Schema loaded");
        }
    }

    /**
     *
     * @param unknown $domain            
     * @param unknown $output            
     * @throws \Exception
     * @return multitype:
     */
    public function getDbConfig($domain, $output)
    {
        $config = $this->getSlim()
        ->getContainer()
        ->get('settings');
        $config = $config->all();
        $dbconfig = $config['database'];
        
        return $dbconfig;
    }
}
