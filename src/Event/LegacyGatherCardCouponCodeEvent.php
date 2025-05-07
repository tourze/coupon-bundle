<?php

namespace CouponBundle\Event;

class LegacyGatherCardCouponCodeEvent
{
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
