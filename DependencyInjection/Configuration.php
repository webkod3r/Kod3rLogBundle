<?php
namespace Kod3r\LogBundle\DependencyInjection;

use Monolog\Logger;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 *
 * @author Pablo Molina <web.kod3r@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('kod3r_log');

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        $supportedLogLevels = array('info', 'notice', 'warning', 'error');

        $rootNode
            ->children()
                ->scalarNode('channel_name')
                    ->defaultValue('backtrace')
                    ->info('This parameter is used to define the log channel to use in controller, and configuration.')
                    ->example('Ex: backtrace, my_channel, etc')
                    ->cannotBeEmpty()
                    ->cannotBeOverwritten()
                ->end() // channel_name
                ->scalarNode('log_level')
                    ->defaultValue('info')
                    ->info('The default log level to store in DataBase.')
                    ->example('Ex: info, notice, warning, error')
                    ->validate()
                        ->ifNotInArray($supportedLogLevels)
                        ->thenInvalid('The log_level %s is not supported. Please choose one of '.json_encode($supportedLogLevels))
                    ->end()
                ->end() // log_level
                ->scalarNode('logger_service')
                    ->defaultValue('kod3r_log.logger_database')
                ->end() //
            ->end();

        return $treeBuilder;
    }
}
