<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class AppCookingCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:cooking')
            ->setDescription('調理開始コマンド')
            ->addArgument('argument', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $cookingJob = $this->getContainer()->get('doctrine')->getRepository('AppBundle:CookingJob')->findOneByIsCooking(true);
        $gpioService = $this->getContainer()->get('app.gpio');

        for ($i=0; $i<10; $i++) {
            exec('touch /Users/symmt/Program/Study/SousVideCookerPi/$(date "+%Y%m%d-%H%M%S").txt');
            sleep(3);
        }
    }

}
