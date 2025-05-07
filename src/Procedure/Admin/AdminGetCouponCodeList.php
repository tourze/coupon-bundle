<?php

namespace CouponBundle\Procedure\Admin;

use CouponBundle\Entity\Code;
use CouponBundle\Enum\CouponType;
use CouponBundle\Repository\CodeRepository;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Tourze\JsonRPC\Core\Attribute\MethodDoc;
use Tourze\JsonRPC\Core\Attribute\MethodExpose;
use Tourze\JsonRPC\Core\Attribute\MethodParam;
use Tourze\JsonRPC\Core\Attribute\MethodTag;
use Tourze\JsonRPC\Core\Procedure\BaseProcedure;
use Tourze\JsonRPCLogBundle\Attribute\Log;
use Tourze\JsonRPCPaginatorBundle\Procedure\PaginatorTrait;

#[MethodTag('优惠券管理')]
#[Log]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
#[MethodDoc('拉取券码列表')]
#[MethodExpose('AdminGetCouponCodeList')]
class AdminGetCouponCodeList extends BaseProcedure
{
    use PaginatorTrait;

    #[MethodParam('券码ID')]
    public string $id = '';

    #[MethodParam('用户昵称')]
    public string $nickname = '';

    #[MethodParam('优惠券类型')]
    public string $couponType = '';

    public function __construct(
        private readonly CodeRepository $repository,
    ) {
    }

    public function execute(): array
    {
        $qb = $this->repository->createQueryBuilder('a');

        if (!empty($this->nickname)) {
            $qb->innerJoin('a.owner', 'u');
            $qb->andWhere('u.nickname like :nickname');
            $qb->setParameter('nickname', "%{$this->nickname}%");
        }

        if (!empty($this->couponType)) {
            $couponType = CouponType::tryFrom($this->couponType);
            if ($couponType) {
                $qb->innerJoin('a.coupon', 'c');
                $qb->andWhere('c.type = :couponType');
                $qb->setParameter('couponType', $couponType);
            }
        }

        if (!empty($this->id)) {
            $qb->andWhere('a.id = :id')->setParameter('id', $this->id);
        }

        $qb->orderBy('a.id', 'DESC');

        return $this->fetchList($qb, $this->formatItem(...));
    }

    public function formatItem(Code $item): array
    {
        return $item->retrieveAdminArray();
    }
}
