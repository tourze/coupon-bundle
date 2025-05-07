<?php

namespace CouponBundle\Enum;

use Tourze\EnumExtra\Itemable;
use Tourze\EnumExtra\ItemTrait;
use Tourze\EnumExtra\Labelable;
use Tourze\EnumExtra\Selectable;
use Tourze\EnumExtra\SelectTrait;

/**
 * 可能不太需要这枚举了
 */
enum CouponType: string implements Labelable, Itemable, Selectable
{
    use ItemTrait;
    use SelectTrait;

    case FREIGHT = 'freight';
    case WEAPP_LINK = 'weapp-link';
    case H5_LINK = 'h5-link';
    case MONEY = 'money';

    // 优惠券
    case DISCOUNT = 'DISCOUNT';
    case COMMAND = 'COMMAND';
    //    case CASH = 'CASH';
    //    case RAND = 'RAND';
    //    //服务券
    //    case CJQ = 'CJQ';
    //    case MYQ = 'MYQ';
    //    case XZQ = 'XZQ';
    //    case MRQ = 'MRQ';
    //    case HLQ = 'HLQ';
    //    case TYQ = 'TYQ';
    //    case JYQ = 'JYQ';
    //    case GIFT = 'GIFT';

    case THIRD_PARTY = 'third-party';

    public function getLabel(): string
    {
        return match ($this) {
            self::WEAPP_LINK => '小程序外链',
            self::H5_LINK => 'H5外链',
            self::DISCOUNT => '满减券',
            self::MONEY => '现金券', // 直接抵扣整单的现金，其实就是订单券
            self::FREIGHT => '包邮券',
            self::COMMAND => '口令券',
            //            self::CASH => '金额',
            //            self::RAND => '随机券',
            //            self::CJQ => '裁剪券',
            //            self::MYQ => '免邮券',
            //            self::XZQ => '洗澡券',
            //            self::MRQ => '美容券',
            //            self::HLQ => '365换粮券',
            //            self::TYQ => '体验券',
            //            self::JYQ => '绝育券',
            //            self::GIFT => '礼品券',

            self::THIRD_PARTY => '三方优惠券',
        };
    }
}
