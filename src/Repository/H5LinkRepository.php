<?php

namespace CouponBundle\Repository;

use CouponBundle\Entity\H5Link;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use DoctrineEnhanceBundle\Repository\CommonRepositoryAware;

/**
 * @method H5Link|null find($id, $lockMode = null, $lockVersion = null)
 * @method H5Link|null findOneBy(array $criteria, array $orderBy = null)
 * @method H5Link[]    findAll()
 * @method H5Link[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class H5LinkRepository extends ServiceEntityRepository
{
    use CommonRepositoryAware;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, H5Link::class);
    }
}
