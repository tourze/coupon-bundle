<?php

namespace CouponBundle\Controller\Admin;

use CouponBundle\Entity\Discount;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CouponDiscountCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Discount::class;
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
