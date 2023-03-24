<?php

namespace Application\View\Helper;

use Interop\Container\ContainerInterface;
use UnicaenAuthentification\Options\ModuleOptions;
use UnicaenAuthentification\View\Helper\ShibConnectViewHelper;

class TestConnectViewHelperFactory
{
    /**
     * @param ContainerInterface $container
     * @return TestConnectViewHelper
     */
    public function __invoke(ContainerInterface $container)
    {
        /** @var ModuleOptions $moduleOptions */
        $moduleOptions = $container->get('unicaen-auth_module_options');
        $config = $moduleOptions->getShib();

        $enabled = isset($config['enabled']) && (bool) $config['enabled'];
        $title = $config['title'] ?? null;
        $description = $config['description'] ?? null;

        $helper = new TestConnectViewHelper();
        $helper->setEnabled($enabled);
        $helper->setTitle($title ?? TestConnectViewHelper::TITLE);
        $helper->setDescription($description);

        return $helper;
    }
}