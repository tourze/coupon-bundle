<?php

namespace CouponBundle\Request;

use CouponBundle\Entity\Coupon;

class SendCodeRequest
{
    public function __construct(
        private readonly Coupon $coupon,
    ) {
    }

    public function getCoupon(): Coupon
    {
        return $this->coupon;
    }
}
