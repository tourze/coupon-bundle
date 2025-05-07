<?php

namespace CouponBundle\Controller\Admin;

use CouponBundle\Entity\Channel;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CouponChannelCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Channel::class;
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
