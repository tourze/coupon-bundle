<?php

namespace CouponBundle\Repository;

use CouponBundle\Entity\ReadStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @method ReadStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReadStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReadStatus[]    findAll()
 * @method ReadStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReadStatusRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReadStatus::class);
    }
}
