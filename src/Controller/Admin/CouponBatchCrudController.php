<?php

namespace CouponBundle\Controller\Admin;

use CouponBundle\Entity\Batch;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CouponBatchCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Batch::class;
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
