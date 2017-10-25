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
        
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $password = $input->getOption('password');
        
        // handle domains..
        $domains = $this->getDomains();
        
        
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
            
            $this->openConnection($dbconfig);
            
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


}
