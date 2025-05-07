<?php

namespace CouponBundle\Service;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Tourze\EnumExtra\SelectDataFetcher;

#[AutoconfigureTag('box-code.attribute-type.provider')]
class AttributeTypeProvider implements SelectDataFetcher
{
    public const TYPE_NAME = 'coupon';

    public function genSelectData(): iterable
    {
        $name = '优惠券';
        yield [
            'label' => $name,
            'text' => $name,
            'value' => self::TYPE_NAME,
            'name' => $name,
        ];
    }
}
