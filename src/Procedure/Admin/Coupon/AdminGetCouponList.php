<?php

namespace CouponBundle\Procedure\Admin\Coupon;

use CouponBundle\Entity\Coupon;
use CouponBundle\Repository\CouponRepository;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Tourze\JsonRPC\Core\Attribute\MethodDoc;
use Tourze\JsonRPC\Core\Attribute\MethodExpose;
use Tourze\JsonRPC\Core\Attribute\MethodTag;
use Tourze\JsonRPC\Core\Procedure\BaseProcedure;
use Tourze\JsonRPCLogBundle\Attribute\Log;
use Tourze\JsonRPCPaginatorBundle\Procedure\PaginatorTrait;

#[MethodTag('优惠券管理')]
#[Log]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
#[MethodDoc('拉取优惠券列表')]
#[MethodExpose('AdminGetCouponList')]
class AdminGetCouponList extends BaseProcedure
{
    use PaginatorTrait;

    public function __construct(
        private readonly CouponRepository $repository,
    ) {
    }

    public function execute(): array
    {
        $qb = $this->repository->createQueryBuilder('a');
        $qb->orderBy('a.id', 'DESC');

        return $this->fetchList($qb, $this->formatItem(...));
    }

    public function formatItem(Coupon $item): array
    {
        return $item->retrieveAdminArray();
    }
}
