<?php
namespace App\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Fixtures;
use Symfony\Component\Console\Input\InputArgument;
use RedBeanPHP\R;
use App\Dao\UserRepository;
use App\Debug;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;

class ChangeAdminPasswordCommand extends AbstractCommand
{

    protected function configure()
    {
        $this->setName('app:change-admin-password')
            ->setDescription('Change the admin Password')
            ->setHelp('Changes the password');
        
        $this->addOption('password', 'p', InputOption::VALUE_REQUIRED);
        
        // optional domain..
        $this->addOption('domain', 'd', InputOption::VALUE_OPTIONAL);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $password = $input->getOption('password');

        // handle domains..
        $domains = $this->getDomains();
        
        $domain = $input->getOption('domain');
        
        if ($domain) {
            $domains = array(
                $domain
            );
        }
        

        foreach ($domains as $domain) {
            
            // outputs multiple lines to the console (adding "\n" at the end of each line)
            $output->writeln([
                'Change admin password',
                '============',
                ''
            ]);
            
            $output->writeln('Domain: ' . $domain);
            $output->writeln("Changing to $password");
            
            $dbconfig = $this->getDbConfig($domain, $output);
            
            $output->writeln(print_r($dbconfig, 1));
            
            $userService = $this->getServiceContainer()->get('userService');
            
            $payload = array();
            $payload['password'] = $password;
            
            $output->writeln("Changing admin password to $password");
            
            $admin = $userService->getRepo()->get(1);
            $output->writeln(print_r($admin->export(), 1));
            
            $userService->update($payload, $admin);
            
            $admin = $userService->getRepo()->get(1);
            $output->writeln(print_r($admin->export(), 1));
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
