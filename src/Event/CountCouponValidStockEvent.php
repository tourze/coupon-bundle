<?php

namespace CouponBundle\Event;

use CouponBundle\Entity\Coupon;
use Symfony\Contracts\EventDispatcher\Event;

class CountCouponValidStockEvent extends Event
{
    /**
     * @var Coupon 优惠券
     */
    private Coupon $coupon;

    private ?int $count = null;

    public function getCoupon(): Coupon
    {
        return $this->coupon;
    }

    public function setCoupon(Coupon $coupon): void
    {
        $this->coupon = $coupon;
    }

    public function getCount(): ?int
    {
        return $this->count;
    }

    public function setCount(?int $count): void
    {
        $this->count = $count;
    }
}
