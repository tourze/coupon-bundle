<?php

namespace CouponBundle\Repository;

use CouponBundle\Entity\Requirement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use DoctrineEnhanceBundle\Repository\CommonRepositoryAware;

/**
 * @method Requirement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Requirement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Requirement[]    findAll()
 * @method Requirement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RequirementRepository extends ServiceEntityRepository
{
    use CommonRepositoryAware;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Requirement::class);
    }
}
