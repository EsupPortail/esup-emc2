<?php 

namespace Structure\Form\AjouterGestionnaire;

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
        /** @see AgentController::rechercherAction() */
        $urlGestionnaire =  $urlManager->__invoke('agent/rechercher', [], [], true);

        /** @var AjouterGestionnaireForm $form */
        $form = new AjouterGestionnaireForm();
        $form->setUrlGestionnaire($urlGestionnaire);
        return $form;
    }
}