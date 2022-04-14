<?php

namespace EntretienProfessionnel\Service\Url;

use Interop\Container\ContainerInterface;
use Zend\Router\Http\TreeRouteStack;
use Zend\ServiceManager\Factory\DelegatorFactoryInterface;
use Zend\Uri\Http;

class TreeRouteStackConsoleDelegatorFactory implements DelegatorFactoryInterface
{
    public function __invoke(ContainerInterface $container, $name, callable $callback, array $options = null)
    {
        /** @var TreeRouteStack $treeRouteStack */
        $treeRouteStack = $callback();

        echo $container->get('config')['server_url'];
        if (!$treeRouteStack->getRequestUri()) {
            $treeRouteStack->setRequestUri(
                new Http($container->get('config')['server_url'])
            );
        }

        return $treeRouteStack;
    }
}