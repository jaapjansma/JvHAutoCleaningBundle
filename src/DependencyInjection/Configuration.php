<?php

namespace JvH\JvHAutoCleaningBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface {
    /**
     * Generates the configuration tree builder.
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('jvh_auto_cleaning');
        $treeBuilder->getRootNode()
            ->children()
            ->scalarNode('cronjob_batch_size')->defaultValue(100)->end()
            // Last login threshold in days. From when should we clean up members
            // 1095 = 3 x 365 days (3 years).
            ->scalarNode('last_login_threshold')->defaultValue(3 * 365)->end()
            // Grace period in days. User will not be removed during grace period.
            ->scalarNode('grace_period')->defaultValue(14)->end()
            // Mark maximum of 100 members per batch.
            ->scalarNode('max_member_autoclean_batch_size')->defaultValue(100)->end()

            // Clear iso orders after this period in days.
            // Only orders which are not linked to a member will be cleared
            ->scalarNode('iso_order_cleanup_after')->defaultValue(3 * 365)->end()
            // Clear iso packaging slip after this period in days.
            // Only packaging slips which are not linked to a member will be cleared
            ->scalarNode('iso_packaging_slip_cleanup_after')->defaultValue(3 * 365)->end()
            // Clear iso packaging slip after this period in days.
            // Only packaging slips which are not linked to a member will be cleared
            ->scalarNode('iso_booking_cleanup_after')->defaultValue(3 * 365)->end()
            ->end();
        return $treeBuilder;
    }


}