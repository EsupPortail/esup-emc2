<?php 

namespace Structure\Form\AjouterGestionnaire;

use Interop\Container\ContainerInterface;
use Laminas\View\Helper\Url;
use Laminas\View\HelperPluginManager;

class AjouterGestionnaireFormFactory {

    /**
     * @param ContainerInterface $container
     * @return AjouterGestionnaireForm
     */
    public function __invoke(ContainerInterface $container)
    {
        /** @var HelperPluginManager $pluginManager */
        $pluginManager = $container->get('ViewHelperManager');
        /** @var Url $urlManager */
        $urlManager = $pluginManager->get('Url');
        /** @see AgentController::rechercherAction() */
        $urlGestionnaire =  $urlManager->__invoke('agent/rechercher', [], [], true);

        /** @var AjouterGestionnaireHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(AjouterGestionnaireHydrator::class);

        /** @var AjouterGestionnaireForm $form */
        $form = new AjouterGestionnaireForm();
        $form->setUrlGestionnaire($urlGestionnaire);
        $form->setHydrator($hydrator);
        return $form;
    }
}