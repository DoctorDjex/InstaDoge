<?php

namespace Contest\ContestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
/**
 * Category
 *
 * @ORM\Table(name="category")
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
     * @ORM\Column(name="name", type="string" , length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"name"}, updatable=false, unique=true)
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     *
     */
    private $slug;

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }
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
     * @ORM\OneToMany(targetEntity="Contest", mappedBy="category", cascade={"remove", "persist"})
     */
    protected $contests;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getContests()
    {
        return $this->contests;
    }

    /**
     * @param mixed $contests
     */
    public function setContests($contests)
    {
        $this->contests = $contests;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->contests = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add contests
     *
     * @param \Contest\ContestBundle\Entity\Contest $contests
     * @return Category
     */
    public function addContest(\Contest\ContestBundle\Entity\Contest $contests)
    {
        $this->contests[] = $contests;

        return $this;
    }

    /**
     * Remove contests
     *
     * @param \Contest\ContestBundle\Entity\Contest $contests
     */
    public function removeContest(\Contest\ContestBundle\Entity\Contest $contests)
    {
        $this->contests->removeElement($contests);
    }

    /**
     * @return string
     */
    public function __toString() {
        return $this->name;
    }
}
