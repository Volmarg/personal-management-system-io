<?php


namespace App\Entity;


use Doctrine\Common\Collections\ArrayCollection;

class AbstractEntity
{
    /**
     * @var ArrayCollection
     */
    protected ArrayCollection $dataBag;

    public function __construct()
    {
        $this->dataBag = new ArrayCollection();
    }

    /**
     * @return ArrayCollection
     */
    public function getDataBag(): ArrayCollection
    {
        return $this->dataBag;
    }

}