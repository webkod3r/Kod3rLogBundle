<?php

namespace Kod3r\LogBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class Kod3rLogExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configToSet = array(
            'handlers' => array(
                'backtrace' => array(
                    'type' => 'service',
                    'level' => 'info',
                    'id' => 'kod3r_log.logger_database',
                    'channels' => '["!doctrine"]', // Exclude doctrine channel
                )
            )
        );

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

//        $param = sprintf('monolog.handlers.%s.type', $config['channel_name']);
//        $container->setParameter($param, 'service');
//        $param = sprintf('monolog.handlers.%s.id', $config['channel_name']);
//        $container->setParameter($param, $config['logger_service']);
//        $param = sprintf('monolog.handlers.%s.level', $config['channel_name']);
//        $container->setParameter($param, $config['log_level']);
//        $param = sprintf('monolog.handlers.%s.channels', $config['channel_name']);
//        $container->setParameter($param, '["!doctrine"]'); // Exclude doctrine channel

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }

    /**
     * @inheritDoc
     */
    public function prepend( ContainerBuilder $container )
    {
        // get all bundles
        $bundles = $container->getParameter('kernel.bundles');

        // determine if AcmeGoodbyeBundle is registered
        if( isset( $bundles[ 'MonologBundle' ] ) ){
            // required config for this bundle
            $config = array(
                'handlers' => array(
                    'backtrace' => array(
                        'type' => 'service',
                        'level' => 'info',
                        'id' => 'kod3r_log.logger_database',
                        'channels' => '["!doctrine"]', // Exclude doctrine channel
                    )
                )
            );

            foreach( $container->getExtensions() as $name => $extension ){
                if( 'monolog' == $name ){
                    //$container->prependExtensionConfig($name, $config);
                }
            }
        }

        // process the configuration of Kod3rLogExtension
        $configs = $container->getExtensionConfig($this->getAlias());

        // use the Configuration class to generate a config array with the settings "kod3r_log"
        $config = $this->processConfiguration(new Configuration(), $configs);
    }
}
