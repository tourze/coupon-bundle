<?php

namespace CouponBundle\Procedure\Admin\Coupon;

use CouponBundle\Repository\CouponRepository;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Tourze\JsonRPC\Core\Attribute\MethodDoc;
use Tourze\JsonRPC\Core\Attribute\MethodExpose;
use Tourze\JsonRPC\Core\Attribute\MethodParam;
use Tourze\JsonRPC\Core\Attribute\MethodTag;
use Tourze\JsonRPC\Core\Exception\ApiException;
use Tourze\JsonRPC\Core\Procedure\BaseProcedure;
use Tourze\JsonRPCLogBundle\Attribute\Log;
use Doctrine\ORM\EntityManagerInterface;

#[MethodTag('优惠券管理')]
#[Log]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
#[MethodDoc('编辑优惠券')]
#[MethodExpose('AdminUpdateCoupon')]
class AdminUpdateCoupon extends BaseProcedure
{
    #[MethodParam('id')]
    public string $id;

    #[MethodParam('唯一编码')]
    public string $sn;

    #[MethodParam('名称')]
    public string $name;

    #[MethodParam('领取后过期天数')]
    public ?int $expireDay = null;

    #[MethodParam('ICON图标')]
    public ?string $iconImg = 'https://cdn.mixpwr.com/aichonghui/pic/other/shops/a4.png';

    #[MethodParam('列表背景')]
    public ?string $backImg = 'https://cdn.mixpwr.com/aichonghui/pic/myCenter/couponBg4.png';

    #[MethodParam('备注')]
    public ?string $remark = null;

    #[MethodParam('是否需要激活')]
    public ?bool $needActive = null;

    #[MethodParam('激活后有效天数')]
    public ?int $activeValidDay = null;

    #[MethodParam('使用说明')]
    public ?string $useDesc = null;

    #[MethodParam('有效')]
    public ?bool $valid = false;

    public function __construct(
        private readonly CouponRepository $repository,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function execute(): array
    {
        $record = $this->repository->findOneBy(['id' => $this->id]);
        if (!$record) {
            throw new ApiException('记录不存在');
        }
        $record->setSn($this->sn);
        $record->setName($this->name);
        $record->setExpireDay($this->expireDay);
        $record->setIconImg($this->iconImg);
        $record->setBackImg($this->backImg);
        $record->setRemark($this->remark);
        $record->setNeedActive($this->needActive);
        $record->setActiveValidDay($this->activeValidDay);
        $record->setUseDesc($this->useDesc);
        $record->setValid($this->valid);

        $this->entityManager->persist($record);
        $this->entityManager->flush();

        return ['__message' => '编辑成功'];
    }
}
