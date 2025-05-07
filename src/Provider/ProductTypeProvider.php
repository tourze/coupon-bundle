<?php

namespace CouponBundle\Provider;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Tourze\EnumExtra\SelectDataFetcher;

#[AutoconfigureTag('product.type.provider')]
class ProductTypeProvider implements SelectDataFetcher
{
    public function genSelectData(): iterable
    {
        $title = $_ENV['COUPON_TYPE_SPU_NAME'] ?? '优惠券商品';
        yield [
            'label' => $title,
            'text' => $title,
            'value' => 'coupon',
            'name' => $title,
        ];
    }
}
