<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CookingJob
 *
 * @ORM\Table(name="cooking_job")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CookingJobRepository")
 */
class CookingJob
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var bool
     *
     * @ORM\Column(name="isCooking", type="boolean")
     */
    private $isCooking;

    /**
     * @var float
     *
     * @ORM\Column(name="cookingTemperature", type="float")
     */
    private $cookingTemperature;

    /**
     * @var int
     *
     * @ORM\Column(name="cookingTime", type="integer")
     */
    private $cookingTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="cookingStartTime", type="datetimetz")
     */
    private $cookingStartTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="cookingEndTime", type="datetimetz")
     */
    private $cookingEndTime;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set isCooking
     *
     * @param boolean $isCooking
     *
     * @return CookingJob
     */
    public function setIsCooking($isCooking)
    {
        $this->isCooking = $isCooking;

        return $this;
    }

    /**
     * Get isCooking
     *
     * @return bool
     */
    public function getIsCooking()
    {
        return $this->isCooking;
    }

    /**
     * Set cookingTemperature
     *
     * @param float $cookingTemperature
     *
     * @return CookingJob
     */
    public function setCookingTemperature($cookingTemperature)
    {
        $this->cookingTemperature = $cookingTemperature;

        return $this;
    }

    /**
     * Get cookingTemperature
     *
     * @return float
     */
    public function getCookingTemperature()
    {
        return $this->cookingTemperature;
    }

    /**
     * Set cookingTime
     *
     * @param integer $cookingTime
     *
     * @return CookingJob
     */
    public function setCookingTime($cookingTime)
    {
        $this->cookingTime = $cookingTime;

        return $this;
    }

    /**
     * Get cookingTime
     *
     * @return int
     */
    public function getCookingTime()
    {
        return $this->cookingTime;
    }

    /**
     * Set cookingStartTime
     *
     * @param \DateTime $cookingStartTime
     *
     * @return CookingJob
     */
    public function setCookingStartTime($cookingStartTime)
    {
        $this->cookingStartTime = $cookingStartTime;

        return $this;
    }

    /**
     * Get cookingStartTime
     *
     * @return \DateTime
     */
    public function getCookingStartTime()
    {
        return $this->cookingStartTime;
    }

    /**
     * Set cookingEndTime
     *
     * @param \DateTime $cookingEndTime
     *
     * @return CookingJob
     */
    public function setCookingEndTime($cookingEndTime)
    {
        $this->cookingEndTime = $cookingEndTime;

        return $this;
    }

    /**
     * Get cookingEndTime
     *
     * @return \DateTime
     */
    public function getCookingEndTime()
    {
        return $this->cookingEndTime;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return CookingJob
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
}

