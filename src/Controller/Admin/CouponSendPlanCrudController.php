<?php

namespace CouponBundle\Controller\Admin;

use CouponBundle\Entity\SendPlan;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CouponSendPlanCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return SendPlan::class;
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
