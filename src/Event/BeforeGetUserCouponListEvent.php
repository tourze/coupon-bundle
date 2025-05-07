<?php

namespace CouponBundle\Event;

use AppBundle\Entity\BizUser;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Contracts\EventDispatcher\Event;

class BeforeGetUserCouponListEvent extends Event
{
    /**
     * @var BizUser|UserInterface 要查找的用户
     */
    private BizUser|UserInterface $user;

    public function getUser(): BizUser|UserInterface
    {
        return $this->user;
    }

    public function setUser(BizUser|UserInterface $user): void
    {
        $this->user = $user;
    }
}
