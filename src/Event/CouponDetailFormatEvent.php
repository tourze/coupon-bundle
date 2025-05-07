<?php

namespace CouponBundle\Event;

use CouponBundle\Traits\CouponAware;
use Symfony\Contracts\EventDispatcher\Event;

class CouponDetailFormatEvent extends Event
{
    use CouponAware;

    private array $result = [];

    public function getResult(): array
    {
        return $this->result;
    }

    public function setResult(array $result): void
    {
        $this->result = $result;
    }
}
