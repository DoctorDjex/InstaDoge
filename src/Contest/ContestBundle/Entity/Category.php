<?php

namespace Contest\ContestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Category
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Category
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="Animaux", type="string", length=255)
     */
    private $animaux;

    /**
     * @var string
     *
     * @ORM\Column(name="Hightech", type="string", length=255)
     */
    private $hightech;

    /**
     * @var string
     *
     * @ORM\Column(name="Paysages", type="string", length=255)
     */
    private $paysages;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set animaux
     *
     * @param string $animaux
     * @return Category
     */
    public function setAnimaux($animaux)
    {
        $this->animaux = $animaux;

        return $this;
    }

    /**
     * Get animaux
     *
     * @return string 
     */
    public function getAnimaux()
    {
        return $this->animaux;
    }

    /**
     * Set hightech
     *
     * @param string $Hightech
     * @return Category
     */
    public function setHighTech($Hightech)
    {
        $this->hightech = $Hightech;

        return $this;
    }

    /**
     * Get hightech
     *
     * @return string 
     */
    public function getHightech()
    {
        return $this->hightech;
    }

    /**
     * Set paysages
     *
     * @param string $paysages
     * @return Category
     */
    public function setPaysages($paysages)
    {
        $this->paysages = $paysages;

        return $this;
    }

    /**
     * Get paysages
     *
     * @return string 
     */
    public function getPaysages()
    {
        return $this->paysages;
    }

    private $categories;

    /**
     * @ORM\OneToMany(targetEntity="Contest", mappedBy="cats", cascade={"remove", "persist"})
     */
    protected $conts;


}
