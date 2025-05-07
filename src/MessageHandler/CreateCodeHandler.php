<?php

namespace CouponBundle\MessageHandler;

use CouponBundle\Message\CreateCodeMessage;
use CouponBundle\Repository\CouponRepository;
use CouponBundle\Service\CouponService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CreateCodeHandler
{
    public function __construct(
        private readonly CouponRepository $couponRepository,
        private readonly CouponService $codeService,
    ) {
    }

    public function __invoke(CreateCodeMessage $message): void
    {
        $coupon = $this->couponRepository->findOneBy([
            'id' => $message->getCouponId(),
            'valid' => true,
        ]);
        if (!$coupon) {
            throw new \Exception('生成code时，找不到优惠券');
        }

        $c = $message->getQuantity();
        while ($c > 0) {
            $this->codeService->createOneCode($coupon);
            --$c;
        }
    }
}
