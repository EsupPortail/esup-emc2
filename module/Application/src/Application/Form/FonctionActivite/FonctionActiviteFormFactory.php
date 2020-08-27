<?php

namespace Application\Form\FonctionActivite;

use Application\Service\Fonction\FonctionService;
use Interop\Container\ContainerInterface;

class FonctionActiviteFormFactory
{
    /**
     * @param ContainerInterface $container
     * @return FonctionActiviteForm
     */
    public function __invoke(ContainerInterface $container)
    {
        /** @var FonctionService */
        $fonctionService = $container->get(FonctionService::class);

        /** @var FonctionActiviteHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(FonctionActiviteHydrator::class);

        /** @var FonctionActiviteForm $form */
        $form = new FonctionActiviteForm();
        $form->setHydrator($hydrator);
        $form->setFonctionService($fonctionService);
        return $form;
    }
}