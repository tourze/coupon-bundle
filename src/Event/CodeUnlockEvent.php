<?php

namespace CouponBundle\Event;

use CouponBundle\Traits\CodeAware;
use Symfony\Contracts\EventDispatcher\Event;

class CodeUnlockEvent extends Event
{
    use CodeAware;
}
