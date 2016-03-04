<?php

namespace Contest\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var ArrayCollection Image
     *
     * Inverse Side
     *
     * @ORM\ManyToMany(targetEntity="Contest\ContestBundle\Entity\Image", mappedBy="votes", cascade={"persist", "merge", "remove"})
     */
    private $votes;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

    public function hadAlreadyVoted($image)
    {
        foreach ($this->votes as $vote) {
            if ($image->getId() == $vote->getId()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Add votes.
     *
     * @param \Contest\ContestBundle\Entity\Image $votes
     *
     * @return User
     */
    public function addVote(\Contest\ContestBundle\Entity\Image $votes)
    {
        $this->votes[] = $votes;

        return $this;
    }

    /**
     * Remove votes.
     *
     * @param \Contest\ContestBundle\Entity\Image $votes
     */
    public function removeVote(\Contest\ContestBundle\Entity\Image $votes)
    {
        $this->votes->removeElement($votes);
    }

    /**
     * Get votes.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVotes()
    {
        return $this->votes;
    }
}
