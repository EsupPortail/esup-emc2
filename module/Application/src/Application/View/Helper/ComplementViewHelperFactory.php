<?php

namespace Application\View\Helper;

use Application\Entity\Db\Complement;
use Interop\Container\ContainerInterface;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Helper\Partial;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Resolver\TemplatePathStack;


class ComplementViewHelperFactory
{
    public function __invoke(ContainerInterface $container) : ComplementViewHelper
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        $helper = new ComplementViewHelper();
        $helper->setEntityManager($entityManager);
        return $helper;
    }
}