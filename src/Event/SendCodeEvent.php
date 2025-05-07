<?php

namespace CouponBundle\Event;

use AppBundle\Event\HaveUserAware;
use CouponBundle\Traits\CodeAware;
use CouponBundle\Traits\CouponAware;
use Symfony\Contracts\EventDispatcher\Event;

class SendCodeEvent extends Event
{
    use HaveUserAware;
    use CouponAware;
    use CodeAware;

    public string $extend = '';

    public function getExtend(): ?string
    {
        return $this->extend;
    }

    public function setExtend(?string $extend): void
    {
        $this->extend = $extend;
    }
}
