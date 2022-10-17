<?php

namespace Application\View\Helper;

use Application\Entity\Db\Complement;
use Interop\Container\ContainerInterface;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Laminas\View\Helper\AbstractHelper;
use Laminas\View\Helper\Partial;
use Laminas\View\Renderer\PhpRenderer;
use Laminas\View\Resolver\TemplatePathStack;


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