<?php
namespace App\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class InsertAdminCommand extends AbstractCommand
{

    protected function configure()
    {
        $this->setName('app:insert-admin')
            ->setDescription('insert Admin')
            ->setHelp('This command allows you to insert admin in database');
        
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
                'Load Admin',
                '============',
                ''
            ]);
            
            // $domain = $input->getArgument('domain');
            $output->writeln('Domain: ' . $domain);
            
            $dbconfig = $this->getDbConfig($domain, $output);
            
            $user = $dbconfig['user'];
            $password = $dbconfig['password'];
            $dbname = $dbconfig['dbname'];
            
            $out = @system("mysql -u$user -p$password $dbname < data/fixtures.sql");
            $output->writeln($out);
            
            $output->writeln("Fixtures loaded");
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
        $folder = "web/domains/$domain/";
        
        if (file_exists($folder)) {
            $output->writeln("Folder $domain exists");
        }
        
        $settingsfile = $folder . "settings.php";
        
        $settings = array();
        
        if (file_exists($folder)) {
            $output->writeln('Settings file exists');
            $settings = require $settingsfile;
            $settings = $settings['settings'];
            
            if (! is_array($settings)) {
                throw new \Exception("Cannot load settings file");
            }
        } else {
            $output->writeln('Settings file do not exists');
            exit();
        }
        
        $config = $this->getSlim()
            ->getContainer()
            ->get('settings');
        
        $config = $config->all();
        
        if (! is_array($config)) {
            throw new \Exception("Cannot load settings file");
        }
        
        // $output->writeln(print_r($config, 1));
        // $output->writeln(print_r($settings, 1));
        
        $config = array_replace_recursive($config, $settings);
        
        if (! is_array($config)) {
            throw new \Exception("unable to merge");
        }
        
        $dbconfig = $config['database'];
        
        $dbsettings = "app/dbsettings.php";
        
        if (file_exists($dbsettings)) {
            $output->writeln('DBSettings file FOUND');
            $settings = require $dbsettings;
            $dbconfig = array_replace_recursive($dbconfig, $settings);
        }
        
        return $dbconfig;
    }
}
