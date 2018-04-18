<?php

namespace MattJanssen\ApiResponseBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeBuilder;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * API Response Bundle Configuration
 *
 * @author Matt Janssen <matt@mattjanssen.com>
 */
class Configuration implements ConfigurationInterface
{
    const SERIALIZER_ARRAY = 'array';
    const SERIALIZER_JSON_ENCODE = 'json_encode';
    const SERIALIZER_JSON_GROUP_ENCODE = 'json_group_encode';
    const SERIALIZER_JMS_SERIALIZER = 'jms_serializer';

    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('api_response');

        $rootNode
            ->children()
                ->integerNode('log_trigger')
                    ->info('Minimum HTTP return status code which triggers response exception logging.')
                    ->defaultValue(Response::HTTP_INTERNAL_SERVER_ERROR)
                ->end()
            ->end();

        $this->buildConfigNode(
            $rootNode->children()
                ->arrayNode('defaults')
                    ->children()
        );

        $this->buildConfigNode(
            $rootNode->children()
                ->arrayNode('paths')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('pattern')->end()
                            ->scalarNode('prefix')->end()
        );

        return $treeBuilder;
    }

    /**
     * @param NodeBuilder $nodeBuilder
     *
     * @return ArrayNodeDefinition
     */
    private function buildConfigNode(NodeBuilder $nodeBuilder)
    {
        return $nodeBuilder
            ->scalarNode('serializer')->end()
            ->variableNode('serialize_groups')->end()
            ->scalarNode('cors_allow_origin_regex')->end()
            ->arrayNode('cors_allow_headers')
                ->prototype('scalar')->end()
            ->end()
            ->integerNode('cors_max_age')->end()
        ;
    }
}
