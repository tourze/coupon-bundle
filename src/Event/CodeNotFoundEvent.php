<?php

namespace CouponBundle\Event;

use AppBundle\Entity\BizUser;
use CouponBundle\Traits\CodeAware;
use Symfony\Contracts\EventDispatcher\Event;

class CodeNotFoundEvent extends Event
{
    use CodeAware;

    /**
     * @var string SN编码
     */
    private string $sn;

    /**
     * @var BizUser 要查找的用户
     */
    private BizUser $user;

    public function getSn(): string
    {
        return $this->sn;
    }

    public function setSn(string $sn): void
    {
        $this->sn = $sn;
    }

    public function getUser(): BizUser
    {
        return $this->user;
    }

    public function setUser(BizUser $user): void
    {
        $this->user = $user;
    }
}
