<?php

namespace CouponBundle\Repository;

use CouponBundle\Entity\CouponStat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @method CouponStat|null find($id, $lockMode = null, $lockVersion = null)
 * @method CouponStat|null findOneBy(array $criteria, array $orderBy = null)
 * @method CouponStat[]    findAll()
 * @method CouponStat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CouponStatRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CouponStat::class);
    }
}
