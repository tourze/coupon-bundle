<?php

namespace CouponBundle\Event;

class LegacyConsumeCouponCodeEvent
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
