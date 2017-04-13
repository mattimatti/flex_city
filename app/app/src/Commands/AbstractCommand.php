<?php
namespace App\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;
use Slim\App;

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
     * @return an array with the domains
     */
    public function getDomains()
    {
//         return array(
//             "jp.timberland.app"
//         );
        
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
     * @return the $slim
     */
    public function getSlim()
    {
        return $this->slim;
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
