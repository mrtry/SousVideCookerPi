<?php

namespace AppBundle\Service;

use PiPHP\GPIO\GPIO;
use PiPHP\GPIO\Pin\PinInterface;

class GpioService
{
    private $gpio;
    private $adjustTemperature;
    private $thermometerDeviceId;
    private $relayPinNumber;

    public function __construct(GPIO $gpio, $adjustTemperature, $thermometerDeviceId, $relayPinNumber)
    {
        $this->gpio = $gpio;
        $this->adjustTemperature    = $adjustTemperature;
        $this->thermometerDeviceId  = $thermometerDeviceId;
        $this->relayPinNumber       = $relayPinNumber;
    }

    /**
     * 温度計から温度を取得
     *
     * @return float
     */
    public function getCurrentTemperature()
    {
        $command = sprintf(
            'cat /sys/bus/w1/devices/%s/w1_slave | perl -e \'while(<stdin>){ if(/t=([-0-9]+)/){print $1/1000+%f,"\n";} }\'',
            $this->thermometerDeviceId,
            $this->adjustTemperature
        );
        return exec($command);
    }

    /**
     * ソリッドステートリレーを介した電源制御
     *
     * @param boolean
     */
    public function setPower($state)
    {
        $value = $state ? PinInterface::VALUE_HIGH : PinInterface::VALUE_LOW;

        $pin = $this->gpio->getOutputPin($this->relayPinNumber);
        $pin->setValue($value);
    }
}
