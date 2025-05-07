<?php

namespace CouponBundle\Controller\Admin;

use CouponBundle\Entity\CommandConfig;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CouponCommandConfigCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CommandConfig::class;
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
