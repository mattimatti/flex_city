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
        if (ENVIRONMENT == 'development') {
            return array(
                "jp.timberland.app"
            );
        }
        
        return array(
            "flexinthecity.timberland.co.uk",
            "flexinthecity.timberland.it",
            "flexinthecity.timberland.de",
            "flexinthecity.timberland.fr",
            "flexinthecity.timberland.nl",
            "flexinthecity.timberland.es",
            "flexinthecity.timberland.at",
            "flexinthecity.timberland.ch",
            "flexinthecity.timberland.se"
        );
    }
    
    // https://flexinthecity.timberland.co.uk
    // https://flexinthecity.timberland.it.
    // https://flexinthecity.timberland.de
    // https://flexinthecity.timberland.fr
    // https://flexinthecity.timberland.nl
    // https://flexinthecity.timberland.es
    // https://flexinthecity.timberland.at
    // https://flexinthecity.timberland.ch/de_ch
    // https://flexinthecity.timberland.ch/it_ch
    // https://flexinthecity.timberland.ch/fr_ch
    // https://flexinthecity.timberland.ch/en_ch
    // https://flexinthecity.timberland.se
    
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
}
