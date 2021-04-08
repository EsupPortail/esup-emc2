<?php

namespace UnicaenEtat\View\Helper;

use Application\View\Renderer\PhpRenderer;
use Interop\Container\ContainerInterface;
use UnicaenEtat\Entity\Db\Etat;
use UnicaenEtat\Service\Etat\EtatService;
use UnicaenEtat\Service\Etat\EtatServiceAwareTrait;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Helper\Partial;
use Zend\View\Resolver\TemplatePathStack;

class EtatBadgeViewHelperFactory extends AbstractHelper
{
    /**
     * @param ContainerInterface $container
     * @return EtatBadgeViewHelper
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EtatService $etatService
         */
        $etatService = $container->get(EtatService::class);

        $helper = new EtatBadgeViewHelper();
        $helper->setEtatService($etatService);
        return $helper;
    }
}