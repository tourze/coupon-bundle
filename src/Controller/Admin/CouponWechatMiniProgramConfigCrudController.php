<?php

namespace CouponBundle\Controller\Admin;

use CouponBundle\Entity\WechatMiniProgramConfig;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CouponWechatMiniProgramConfigCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return WechatMiniProgramConfig::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
