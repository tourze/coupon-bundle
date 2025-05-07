<?php

namespace CouponBundle\Controller\Admin;

use CouponBundle\Entity\H5Link;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CouponH5LinkCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return H5Link::class;
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
