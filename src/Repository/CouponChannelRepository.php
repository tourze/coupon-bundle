<?php

namespace CouponBundle\Repository;

use CouponBundle\Entity\CouponChannel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use DoctrineEnhanceBundle\Repository\CommonRepositoryAware;

/**
 * @method CouponChannel|null find($id, $lockMode = null, $lockVersion = null)
 * @method CouponChannel|null findOneBy(array $criteria, array $orderBy = null)
 * @method CouponChannel[]    findAll()
 * @method CouponChannel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CouponChannelRepository extends ServiceEntityRepository
{
    use CommonRepositoryAware;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CouponChannel::class);
    }
}
