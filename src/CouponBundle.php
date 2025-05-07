<?php

namespace CouponBundle;

use AppBundle\Model\CouponEntity;
use CouponBundle\Entity\Coupon;
use DoctrineEnhanceBundle\DependencyInjection\Compiler\ResolveTargetEntitiesPass;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Tourze\BundleDependency\BundleDependencyInterface;
use Tourze\EasyAdmin\Attribute\Permission\AsPermission;

#[AsPermission(title: '优惠券模块')]
class CouponBundle extends Bundle implements BundleDependencyInterface
{
    public static function getBundleDependencies(): array
    {
        return [
            \Tourze\DoctrineSnowflakeBundle\DoctrineSnowflakeBundle::class => ['all' => true],
            \Tourze\DoctrineIndexedBundle\DoctrineIndexedBundle::class => ['all' => true],
            \AntdCpBundle\AntdCpBundle::class => ['all' => true],
            \Tourze\Symfony\CronJob\CronJobBundle::class => ['all' => true],
        ];
    }

    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        // 跟Coupon解耦
        $container->addCompilerPass(
            new ResolveTargetEntitiesPass(CouponEntity::class, Coupon::class),
            PassConfig::TYPE_BEFORE_OPTIMIZATION,
            1000,
        );
    }
}
