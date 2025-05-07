<?php

namespace CouponBundle\Procedure\Coupon;

use CouponBundle\Message\CreateCodeMessage;
use CouponBundle\Repository\CouponRepository;
use CouponBundle\Service\CouponService;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Tourze\JsonRPC\Core\Attribute\MethodDoc;
use Tourze\JsonRPC\Core\Attribute\MethodExpose;
use Tourze\JsonRPC\Core\Attribute\MethodParam;
use Tourze\JsonRPC\Core\Attribute\MethodTag;
use Tourze\JsonRPC\Core\Exception\ApiException;
use Tourze\JsonRPCLockBundle\Procedure\LockableProcedure;
use Tourze\JsonRPCLogBundle\Attribute\Log;

#[MethodTag('优惠券管理')]
#[MethodDoc('生成优惠券码')]
#[MethodExpose('AdminGenerateCouponCode')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
#[Autoconfigure(public: true)]
#[Log]
class AdminGenerateCouponCode extends LockableProcedure
{
    #[MethodParam('优惠券ID')]
    public string $id;

    #[MethodParam('生成数量')]
    public int $quantity;

    public function __construct(
        private readonly CouponRepository $couponRepository,
        private readonly MessageBusInterface $messageBus,
        private readonly CouponService $codeService,
    ) {
    }

    public function execute(): array
    {
        $coupon = $this->couponRepository->find($this->id);
        if (!$coupon) {
            throw new ApiException('找不到指定优惠券');
        }

        // 如果大于100个，我们就异步处理
        if ($this->quantity >= 100) {
            $message = new CreateCodeMessage();
            $message->setCouponId($coupon->getId());
            $this->messageBus->dispatch($message);

            return [
                '__message' => '异步生成后，请稍候',
            ];
        }

        while ($this->quantity > 0) {
            $this->codeService->createOneCode($coupon);
            --$this->quantity;
        }

        return [
            '__message' => '生成成功',
        ];
    }
}
