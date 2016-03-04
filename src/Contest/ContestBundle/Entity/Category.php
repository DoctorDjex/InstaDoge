<?php

namespace Contest\ContestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
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

}
