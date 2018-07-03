<?php
namespace RS\DiExtraBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{

    /**
     * Generates the configuration tree builder.
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $builder = new TreeBuilder();
        $builder
            ->root('rs_di_extra', 'array')
                ->children()
                    ->arrayNode('locations')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->booleanNode('all_bundles')->defaultFalse()->end()
                            ->arrayNode('bundles')
                                ->beforeNormalization()
                                    ->ifString()
                                    ->then(function ($v) {
                                        return preg_split('/\s*,\s*/', $v);
                                    })
                                ->end()
                                ->prototype('scalar')->end()
                            ->end()
                            ->arrayNode('disallow_bundles')
                                ->beforeNormalization()
                                    ->ifString()
                                    ->then(function ($v) {
                                        return preg_split('/\s*,\s*/', $v);
                                    })
                                ->end()
                                ->prototype('scalar')->end()
                            ->end()
                            ->arrayNode('directories')
                                ->beforeNormalization()
                                    ->ifString()
                                    ->then(function ($v) {
                                        return preg_split('/\s*,\s*/', $v);
                                    })
                                ->end()
                                ->prototype('scalar')->end()
                            ->end()
                        ->end()
                    ->end()
                    ->booleanNode('doctrine_integration')
                        ->validate()
                            ->always(function ($v) {
                                if ($v && !class_exists('Doctrine\ORM\EntityManager')) {
                                    throw new \Exception('Doctrine integration is only available for the Doctrine ORM at the moment.');
                                }
                                return $v;
                            })
                        ->end()
                        ->defaultValue(class_exists('Doctrine\ORM\EntityManager'))
                    ->end()
                ->end()
            ->end()
            ;
        return $builder;
    }
}
