<?php

namespace CouponBundle\DependencyInjection;

use CouponBundle\Entity\Coupon;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Tourze\CouponContracts\CouponInterface;
use Tourze\DoctrineResolveTargetEntityBundle\DependencyInjection\ResolveTargetEntityPass;

class CouponExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../Resources/config')
        );
        $loader->load('services.yaml');

        $container->addCompilerPass(
            new ResolveTargetEntityPass(CouponInterface::class, Coupon::class),
            PassConfig::TYPE_BEFORE_OPTIMIZATION,
            1000,
        );
    }
}
