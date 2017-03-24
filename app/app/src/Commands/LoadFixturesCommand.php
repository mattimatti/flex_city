<?php
namespace App\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Fixtures;
use Symfony\Component\Console\Input\InputArgument;
use RedBeanPHP\R;

class LoadFixturesCommand extends AbstractCommand
{

    protected function configure()
    {
        $this->setName('app:load-fixtures')
            ->setDescription('Load The fixtures in database')
            ->setHelp('This command allows you to load fixtures');
        
        $this->addArgument('domain', InputArgument::REQUIRED, 'The username of the user.');
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
        
        if(file_exists($dbsettings)){
            $output->writeln('DBSettings file FOUND');
            $settings = require $dbsettings;
            $dbconfig = array_replace_recursive($dbconfig, $settings);
        }
        
        
        $output->writeln(print_r($dbconfig, 1));
        
        $fixtures = new Fixtures(true);
        $fixtures->openConnection($dbconfig);
        $fixtures->load();
        $fixtures->dump();
    }
}
