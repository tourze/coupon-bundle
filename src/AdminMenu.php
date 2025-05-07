<?php

namespace CouponBundle;

use CouponBundle\Entity\Code;
use CouponBundle\Entity\Coupon;
use CouponBundle\Entity\SendPlan;
use Knp\Menu\ItemInterface;
use Tourze\EasyAdminMenuBundle\Attribute\MenuProvider;
use Tourze\EasyAdminMenuBundle\Service\LinkGeneratorInterface;

#[MenuProvider]
class AdminMenu
{
    public function __construct(private readonly LinkGeneratorInterface $linkGenerator)
    {
    }

    public function __invoke(ItemInterface $item): void
    {
        if (!$item->getChild('优惠券管理')) {
            $item->addChild('优惠券管理');
        }
        $item->getChild('优惠券管理')->addChild('优惠券')->setUri($this->linkGenerator->getCurdListPage(Coupon::class));
        $item->getChild('优惠券管理')->addChild('码管理')->setUri($this->linkGenerator->getCurdListPage(Code::class));
        $item->getChild('优惠券管理')->addChild('发送计划')->setUri($this->linkGenerator->getCurdListPage(SendPlan::class));
    }
}
