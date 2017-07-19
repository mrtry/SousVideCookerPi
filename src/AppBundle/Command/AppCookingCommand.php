<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class AppCookingCommand extends ContainerAwareCommand
{
    const Kp = 2.0;
    const Ki = 4.0;

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
        $gpioService = $this->getContainer()->get('app.gpio');

        $manager = $this->getContainer()->get('doctrine')->getManager();
        $cookingJob = $this
                ->getContainer()
                ->get('doctrine')
                ->getRepository('AppBundle:CookingJob')
                ->findOneByIsCooking(true)
            ;

        if (!$cookingJob) {
            return $output->writeln('Nothing CookingJob');
        }

        $previous = 0;

        do {
            $manager->refresh($cookingJob);

            if ($cookingJob->getCookingEndTime() < new \DateTime('now')) {
                $cookingJob->setIsCooking(false);
            }

            $temperature = $gpioService->getCurrentTemperature();

            $pg = $this->proportionalController($temperature, $cookingJob->getCookingTemperature(), self::Kp);
            $ig = $this->integralController($previous, $temperature, $cookingJob->getCookingTemperature(), self::Ki);
            $power = $pg + $ig;

            if ($power > 0) {
                $power += 0.13;
            }

            $previous = $temperature;

            $output->writeln(
                sprintf('%s, %f, %f, %f, %s, %s', (new \DateTime('now'))->format('H:i:s'), $temperature, self::Kp, self::Ki, $cookingJob->getCookingTemperature(), $cookingJob->getCookingTime())
            );

            $this->out($power);

        } while (
            $cookingJob->getIsCooking()
        );

        $cookingJob->setIsCooking(false);
        $manager->flush();

        $gpioService->setPower(false);
        $output->writeln('finished');
    }

    protected function out($power)
    {
        $gpioService = $this->getContainer()->get('app.gpio');

        if ($power > 1) {
            $power = 1;
        }

        $on = $power * 10;
        $off = (1 - $power) * 10;

        if ($on > 0) {
            $gpioService->setPower(true);
            sleep($on);
        }

        if ($off > 0) {
            $gpioService->setPower(false);
            sleep($off);
        }
    }

    protected function proportionalController($temperature, $target, $kp)
    {
        $d = $target - $temperature;
        if ($d < 0) {
            return 0;
        }

        $power = $d / $target * $kp;
        return $power;
    }

    protected function integralController($previous, $now, $target, $ki)
    {
        if ($previous == 0) {
            return 0;
        }

        $d1 = $target - $now;
        $d2 = $target - $previous;

        if ($d1 < 0) {
            return 0;
        }

        if ($d2 < 0) {
            $d2 = 0;
        }

        return ($d1 + $d2) * 10 / 2 * $ki;
    }
}
