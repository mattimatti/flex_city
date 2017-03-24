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
