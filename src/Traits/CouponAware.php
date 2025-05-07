<?php

namespace CouponBundle\Traits;

use CouponBundle\Entity\Coupon;

trait CouponAware
{
    private ?Coupon $coupon = null;

    public function getCoupon(): ?Coupon
    {
        return $this->coupon;
    }

    public function setCoupon(?Coupon $coupon): void
    {
        $this->coupon = $coupon;
    }
}
