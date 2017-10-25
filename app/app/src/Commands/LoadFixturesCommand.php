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
        
        // $this->addArgument('domain', InputArgument::REQUIRED, 'The domain.');
    }

    
    /**
     * (non-PHPdoc)
     * @see \App\Commands\AbstractCommand::execute()
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $domains = $this->getDomains();
        
        foreach ($domains as $domain) {
            
            // outputs multiple lines to the console (adding "\n" at the end of each line)
            $output->writeln([
                'Load Fixtures',
                '============',
                ''
            ]);
            
            // $domain = $input->getArgument('domain');
            $output->writeln('Domain: ' . $domain);
            
            $dbconfig = $this->getDbConfig($domain, $output);
            
            //$output->writeln(print_r($dbconfig, 1));
            
            $fixtures = new Fixtures(true);
            $fixtures->openConnection($dbconfig);
            $fixtures->truncate();
            $fixtures->load();
            $fixtures->dump();
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
