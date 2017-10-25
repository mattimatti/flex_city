<?php
namespace App\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;
use Slim\App;
use RedBeanPHP\R;

class AbstractCommand extends Command
{

    /**
     *
     * @var App
     */
    protected $slim;

    /**
     * (non-PHPdoc)
     *
     * @see \Symfony\Component\Console\Command\Command::configure()
     */
    protected function configure()
    {}

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Hello World');
    }

    /**
     *
     * @param array $config            
     */
    public function openConnection($config)
    {
        if (! R::hasDatabase($config['dbname'])) {
            R::addDatabase($config['dbname'], 'mysql:host=' . $config['host'] . ';dbname=' . $config['dbname'], $config['user'], $config['password']);
        }
        R::selectDatabase($config['dbname']);
    }

    /**
     *
     * @return an array with the domains
     */
    public function getDomains()
    {
        return array(
            "flexinthecity.eu"
        );
    }

    /**
     *
     * @return \Slim\App
     */
    public function getSlim()
    {
        return $this->slim;
    }

    /**
     *
     * @return ContainerInterface
     */
    public function getServiceContainer()
    {
        return $this->getSlim()->getContainer();
    }

    /**
     *
     * @param \Slim\App $slim            
     */
    public function setSlim($slim)
    {
        $this->slim = $slim;
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
