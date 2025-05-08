<?php

namespace CouponBundle\Event;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Contracts\EventDispatcher\Event;

class BeforeGetUserCouponListEvent extends Event
{
    /**
     * @var UserInterface 要查找的用户
     */
    private UserInterface $user;

    public function getUser(): UserInterface
    {
        return $this->user;
    }

    public function setUser(UserInterface $user): void
    {
        $this->user = $user;
    }
}
