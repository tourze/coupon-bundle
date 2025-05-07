<?php

namespace CouponBundle\Event;

use CouponBundle\Traits\CouponAware;
use Symfony\Contracts\EventDispatcher\Event;

class DetectCouponEvent extends Event
{
    use CouponAware;

    private string $couponId;

    public function getCouponId(): string
    {
        return $this->couponId;
    }

    public function setCouponId(string $couponId): void
    {
        $this->couponId = $couponId;
    }
}
