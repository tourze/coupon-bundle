<?php

namespace CouponBundle\Exception;

class CodeNotFoundException extends \Exception
{
    private string $sn;

    public function getSn(): string
    {
        return $this->sn;
    }

    public function setSn(string $sn): void
    {
        $this->sn = $sn;
    }
}
