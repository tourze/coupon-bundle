<?php

namespace CouponBundle\Procedure\Coupon;

use CouponBundle\Entity\Coupon;
use CouponBundle\Service\CodeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Tourze\JsonRPC\Core\Attribute\MethodDoc;
use Tourze\JsonRPC\Core\Attribute\MethodExpose;
use Tourze\JsonRPC\Core\Attribute\MethodParam;
use Tourze\JsonRPC\Core\Attribute\MethodTag;
use Tourze\JsonRPC\Core\Procedure\BaseProcedure;
use Tourze\JsonRPCPaginatorBundle\Procedure\PaginatorTrait;

#[MethodTag('优惠券管理')]
#[MethodDoc('获取所有优惠券')]
#[MethodExpose('AdminGetCouponEntityList')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
class AdminGetCouponEntityList extends BaseProcedure
{
    use PaginatorTrait;

    #[MethodParam('优惠券ID')]
    public ?string $id = null;

    #[MethodParam('优惠券名称')]
    public ?string $name = null;

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly Security $security,
        private readonly CodeService $codeService,
    ) {
    }

    public function execute(): array
    {
        $qb = $this->entityManager
            ->createQueryBuilder()
            ->from(Coupon::class, 'a')
            ->select('a');
        if (null !== $this->id) {
            $qb->andWhere('a.id = :id')->setParameter('id', $this->id);
        }
        if (null !== $this->name) {
            $qb->andWhere('a.name LIKE :name');
            $qb->setParameter('name', '%' . $this->name . '%');
        }

        return $this->fetchList($qb, $this->formatItem(...));
    }

    private function formatItem(Coupon $coupon): array
    {
        $arr = $coupon->retrieveAdminArray();
        $arr['validStock'] = $this->codeService->getValidStock($coupon);
        $arr['gatherStock'] = $this->codeService->getGatherStock($coupon);

        return $arr;
    }
}
