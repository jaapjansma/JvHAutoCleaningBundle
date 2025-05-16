<?php

namespace JvH\JvHAutoCleaningBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class JvHAutoCleaningExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

      $configuration = new Configuration();
      $processedConfig = $this->processConfiguration($configuration, $configs);
      $container->setParameter('jvh.auto_cleaning.max_member_autoclean_batch_size', $processedConfig['max_member_autoclean_batch_size']);
      $container->setParameter('jvh.auto_cleaning.last_login_threshold', $processedConfig['last_login_threshold']);
      $container->setParameter('jvh.auto_cleaning.grace_period', $processedConfig['grace_period']);
      $container->setParameter('jvh.auto_cleaning.cronjob_batch_size', $processedConfig['cronjob_batch_size']);
      $container->setParameter('jvh.auto_cleaning.iso_order_cleanup_after', $processedConfig['iso_order_cleanup_after']);
      $container->setParameter('jvh.auto_cleaning.iso_packaging_slip_cleanup_after', $processedConfig['iso_packaging_slip_cleanup_after']);
      $container->setParameter('jvh.auto_cleaning.iso_booking_cleanup_after', $processedConfig['iso_booking_cleanup_after']);
    }
}
