<?php

namespace CouponBundle\Controller\Admin;

use CouponBundle\Entity\Attribute;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CouponAttributeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Attribute::class;
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
