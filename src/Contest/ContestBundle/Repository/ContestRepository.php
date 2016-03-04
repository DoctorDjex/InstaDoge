<?php

namespace Contest\ContestBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * ContestRepository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ContestRepository extends EntityRepository
{
    public function findActivesQb()
    {
        $date = new \DateTime();

        $qb = $this->createQueryBuilder('c')
            ->andWhere('c.beginDate <= :date')
            ->andWhere('c.endDate > :date');

        $qb->setParameter('date', $date->format('Y-m-d'));

        return $qb;
    }

    public function findActivesByName($name)
    {
        $date = new \DateTime();
        $qb = $this->createQueryBuilder('c');
        $qb->andWhere('c.beginDate <= :date')
            ->andWhere('c.endDate > :date')
            ->andWhere(
                $qb->expr()->like('c.title', ':name')
            );

        $qb->setParameter('date', $date->format('Y-m-d'));
        $qb->setParameter('name', '%'.$name.'%');

        return $qb->getQuery()->getResult();
    }

    public function findFinished()
    {
        $date = new \DateTime();

        $qb = $this->createQueryBuilder('c')
            ->andWhere('c.endDate < :date')
            ->orderBy('c.endDate', 'DESC')
            ->setMaxResults(10);

        $qb->setParameter('date', $date->format('Y-m-d'));

        return $qb->getQuery()->getResult();
    }

    public function findActivesByOwner($owner)
    {
        $date = new \DateTime();
        $qb = $this->createQueryBuilder('c')
            ->andWhere('c.endDate < :date')
            ->andWhere('c.owner = :owner')
            ->andWhere('c.winner is NULL');

        $qb->setParameter('date', $date->format('Y-m-d'));
        $qb->setParameter('owner', $owner);

        return $qb->getQuery()->getResult();
    }
}
