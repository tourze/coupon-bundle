<?php

namespace CouponBundle\Controller\Admin;

use CouponBundle\Entity\Satisfy;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CouponSatisfyCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Satisfy::class;
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
