<?php

namespace CouponBundle\Controller\Admin;

use CouponBundle\Entity\CouponChannel;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CouponCouponChannelCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CouponChannel::class;
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
