<?php

namespace CouponBundle\Controller\Admin;

use CouponBundle\Entity\ReadStatus;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CouponReadStatusCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ReadStatus::class;
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
