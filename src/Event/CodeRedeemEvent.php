<?php

namespace CouponBundle\Event;

use CouponBundle\Traits\CodeAware;
use Symfony\Contracts\EventDispatcher\Event;

class CodeRedeemEvent extends Event
{
    use CodeAware;

    private ?object $extra = null;

    public function getExtra(): ?object
    {
        return $this->extra;
    }

    public function setExtra(?object $extra): void
    {
        $this->extra = $extra;
    }
}
