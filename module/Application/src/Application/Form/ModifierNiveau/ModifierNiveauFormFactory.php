<?php

namespace Application\Form\ModifierNiveau;

use Application\Service\Niveau\NiveauService;
use Interop\Container\ContainerInterface;

class ModifierNiveauFormFactory
{
    /**
     * @param ContainerInterface $container
     * @return ModifierNiveauForm
     */
    public function __invoke(ContainerInterface $container)
    {
        /** @var NiveauService $niveauService */
        $niveauService = $container->get(NiveauService::class);

        /** @var ModifierNiveauHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(ModifierNiveauHydrator::class);

        /** @var ModifierNiveauForm $form */
        $form = new ModifierNiveauForm();
        $form->setNiveauService($niveauService);
        $form->setHydrator($hydrator);
        return $form;
    }
}