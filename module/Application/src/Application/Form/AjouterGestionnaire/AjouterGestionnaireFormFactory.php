<?php 

namespace Application\Form\AjouterGestionnaire;

use Interop\Container\ContainerInterface;
use Zend\View\Helper\Url;
use Zend\View\HelperPluginManager;

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
        /** @see StructureController::rechercherAction() */
        $urlStructure =  $urlManager->__invoke('structure/rechercher', [], [], true);
        /** @see AgentController::rechercherGestionnaireAction() */
        $urlGestionnaire =  $urlManager->__invoke('agent/rechercher-gestionnaire', [], [], true);

        /** @var AjouterGestionnaireForm $form */
        $form = new AjouterGestionnaireForm();
        $form->setUrlStructure($urlStructure);
        $form->setUrlGestionnaire($urlGestionnaire);
        return $form;
    }
}