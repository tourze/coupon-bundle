<?php

namespace CouponBundle\Event;

use CouponBundle\Traits\CodeAware;
use Symfony\Contracts\EventDispatcher\Event;

class CodeLockedEvent extends Event
{
    use CodeAware;
}
