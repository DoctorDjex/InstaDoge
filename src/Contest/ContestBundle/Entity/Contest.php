<?php

namespace Contest\ContestBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Contest
 *
 * @ORM\Table(name="contest")
 * @ORM\Entity(repositoryClass="Contest\ContestBundle\Repository\ContestRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Contest
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
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @Gedmo\Blameable(on="create")
     * @ORM\ManyToOne(targetEntity="Contest\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="owner", referencedColumnName="id")
     */
    private $owner;

    /**
     * @var \Datetime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \Datetime
     *
     * @ORM\Column(name="begin_date", type="datetime")
     * @Assert\Expression("value <= this.getEndDate()", message="La date de début doit être inférieure à la date de fin.")
     */
    private $beginDate;

    /**
     * @var \Datetime
     *
     * @ORM\Column(name="end_date", type="datetime")
     */
    private $endDate;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"title"}, updatable=false, unique=true)
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     *
     */
    private $slug;

    /**
     *
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Image", mappedBy="contest" , cascade={"persist", "remove"})
     */
    private $images;

    /**
     * @ORM\OneToOne(targetEntity="Image")
     * @ORM\JoinColumn(name="winner_id", referencedColumnName="id")
     */
    private $winner;

    public function __construct() {
        $this->images = new ArrayCollection();
    }


    /**
     * @return \Datetime
     */
    public function getCreatedAt() {
        return $this->createdAt;
    }

    /**
     * @param \Datetime $createdAt
     */
    public function setCreatedAt( $createdAt ) {
        $this->createdAt = $createdAt;
    }

    /**
     * @return \Datetime
     */
    public function getBeginDate() {
        return $this->beginDate;
    }

    /**
     * @param \Datetime $beginDate
     */
    public function setBeginDate( $beginDate ) {
        $this->beginDate = $beginDate;
    }

    /**
     * @return \Datetime
     */
    public function getEndDate() {
        return $this->endDate;
    }

    /**
     * @param \Datetime $endDate
     */
    public function setEndDate( $endDate ) {
        $this->endDate = $endDate;
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
     * Set title
     *
     * @param string $title
     * @return Contest
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set owner
     *
     * @param string $owner
     * @return Contest
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return string 
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @return mixed
     */
    public function getImages() {
        return $this->images;
    }

    /**
     * @param mixed $images
     */
    public function setImages( $images ) {
        $this->images = $images;
    }

    /**
     * @return string
     */
    public function getSlug() {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug( $slug ) {
        $this->slug = $slug;
    }

    /**
     * @return mixed
     */
    public function getWinner()
    {
        return $this->winner;
    }

    /**
     * @param mixed $winner
     */
    public function setWinner($winner)
    {
        $this->winner = $winner;
    }

    public function countAllVotes(){
        $count = 0;

        foreach( $this->images as $image ){
            $count += count( $image->getVotes() );
        }

        return $count;
    }

    /**
     *
     * @ORM\PrePersist
     */
    public function setCreatedAtValue()
    {
        if ($this->getCreatedAt() == null) {
            $this->setCreatedAt(new \DateTime('now'));
        }
    }

    public function isOver(){
        $now = new \DateTime('now');
        return ($this->getEndDate()->getTimestamp() < $now->getTimestamp());
    }

    public function __toString() {
        return $this->title;
    }
}
