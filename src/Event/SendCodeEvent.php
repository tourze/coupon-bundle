<?php

namespace CouponBundle\Event;

use CouponBundle\Traits\CodeAware;
use CouponBundle\Traits\CouponAware;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Contracts\EventDispatcher\Event;

class SendCodeEvent extends Event
{
    use CouponAware;
    use CodeAware;

    private UserInterface $user;

    public function getUser(): UserInterface
    {
        return $this->user;
    }

    public function setUser(UserInterface $user): void
    {
        $this->user = $user;
    }

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
