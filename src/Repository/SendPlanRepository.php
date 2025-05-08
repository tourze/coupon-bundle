<?php

namespace CouponBundle\Repository;

use CouponBundle\Entity\SendPlan;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @method SendPlan|null find($id, $lockMode = null, $lockVersion = null)
 * @method SendPlan|null findOneBy(array $criteria, array $orderBy = null)
 * @method SendPlan[]    findAll()
 * @method SendPlan[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SendPlanRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SendPlan::class);
    }
}
