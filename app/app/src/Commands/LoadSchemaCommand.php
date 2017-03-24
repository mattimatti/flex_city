<?php
namespace App\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LoadSchemaCommand extends AbstractCommand
{

    protected function configure()
    {
        $this->setName('app:load-schema')
            ->setDescription('Load schema in database')
            ->setHelp('This command allows you to load schema in database');
        
        $this->addArgument('domain', InputArgument::REQUIRED, 'the domain.');
    }

    /**
     * (non-PHPdoc)
     * 
     * @see \App\Commands\AbstractCommand::execute()
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        
        // outputs multiple lines to the console (adding "\n" at the end of each line)
        $output->writeln([
            'Load Schema',
            '============',
            ''
        ]);
        
//         $domain = $input->getArgument('domain');
//         $output->writeln('Domain: ' . $domain);
        
//         $dbconfig = $this->getDbConfig($domain, $output);
        
//         $output->writeln(print_r($dbconfig, 1));
        
//         $out = @system('echo from sys');
//         $output->writeln($out);
        
//         $out = @system('mysql');
//         $output->writeln($out);
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
