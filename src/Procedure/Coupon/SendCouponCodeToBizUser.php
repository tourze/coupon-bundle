<?php

namespace CouponBundle\Procedure\Coupon;

use AppBundle\Repository\BizUserRepository;
use Carbon\Carbon;
use CouponBundle\Exception\CouponRequirementException;
use CouponBundle\Exception\PickCodeNotFoundException;
use CouponBundle\Repository\CodeRepository;
use CouponBundle\Repository\CouponRepository;
use CouponBundle\Service\CouponService;
use Tourze\JsonRPC\Core\Attribute\MethodDoc;
use Tourze\JsonRPC\Core\Attribute\MethodExpose;
use Tourze\JsonRPC\Core\Attribute\MethodParam;
use Tourze\JsonRPC\Core\Attribute\MethodTag;
use Tourze\JsonRPC\Core\Exception\ApiException;
use Tourze\JsonRPCLockBundle\Procedure\LockableProcedure;
use Tourze\JsonRPCLogBundle\Attribute\Log;
use Doctrine\ORM\EntityManagerInterface;

#[MethodDoc('领取优惠券')]
#[MethodTag('优惠券模块')]
#[MethodExpose('SendCouponCodeToBizUser')]
#[Log]
class SendCouponCodeToBizUser extends LockableProcedure
{
    #[MethodParam('优惠券ID')]
    public string $couponId;

    #[MethodParam('用户ID')]
    public string $userId = '';

    public function __construct(
        private readonly CouponRepository $couponRepository,
        private readonly CodeRepository $codeRepository,
        private readonly CouponService $codeService,
        private readonly BizUserRepository $bizUserRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function execute(): array
    {
        $user = $this->bizUserRepository->find($this->userId);
        if (empty($user)) {
            throw new ApiException('暂无记录');
        }
        $coupon = $this->couponRepository->findOneBy([
            'id' => $this->couponId,
        ]);
        if (!$coupon) {
            throw new ApiException('找不到指定优惠券');
        }

        // 查找是否满足领取条件
        try {
            $this->codeService->checkCouponRequirement($user, $coupon);
        } catch (CouponRequirementException $exception) {
            throw new ApiException($exception->getMessage());
        }

        try {
            $code = $this->codeService->pickCode($user, $coupon);
        } catch (PickCodeNotFoundException $e) {
            throw new ApiException('优惠券已被抢光', $e->getCode(), previous: $e);
        }

        $code->setGatherTime(Carbon::now());
        $code->setExpireTime(Carbon::now()->addDays($coupon->getExpireDay())); // 过期时间
        $code->setOwner($user);
        $this->entityManager->persist($code);
        $this->entityManager->flush();

        $result = $code->retrieveApiArray();
        $result['__message'] = '领取成功';

        return $result;
    }
}
