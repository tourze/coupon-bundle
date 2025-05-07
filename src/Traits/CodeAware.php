<?php

namespace CouponBundle\Traits;

use CouponBundle\Entity\Code;

trait CodeAware
{
    private ?Code $code = null;

    public function getCode(): ?Code
    {
        return $this->code;
    }

    public function setCode(?Code $code): void
    {
        $this->code = $code;
    }
}
