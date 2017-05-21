<?php

namespace AppBundle\Service;

use PiPHP\GPIO\GPIO;
use PiPHP\GPIO\Pin\PinInterface;

class GpioService
{
    private $gpio;

    public function __construct()
    {
        $this->gpio = new Gpio();
    }

    /**
     * 温度計から温度を取得
     *
     * @return float
     */
    public function getCurrentTemperature()
    {
        //$command = sprintf(
        //    'cat /sys/bus/w1/devices/%s/w1_slave | perl -e \'while(<stdin>){ if(/t=([-0-9]+)/){print $1/1000+%f,"\n";} }\'',
        //    '28-0516a43dafff',
        //    7.5
        //);
        //return exec($command);
        return 20.0;
    }

    /**
     * ソリッドステートリレーを介した電源制御
     *
     * @param boolean
     */
    public function setPower($bool)
    {
        $pin = $this->gpio->getOutputPin(26);
        $pin->setValue(PinInterface::VALUE_HIGH);

        return;
    }
}
