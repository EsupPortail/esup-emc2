<?php 

namespace Structure\Form\AjouterResponsable;

use Interop\Container\ContainerInterface;
use Zend\View\Helper\Url;
use Zend\View\HelperPluginManager;

class AjouterResponsableFormFactory {

    /**
     * @param ContainerInterface $container
     * @return AjouterResponsableForm
     */
    public function __invoke(ContainerInterface $container)
    {
        /** @var HelperPluginManager $pluginManager */
        $pluginManager = $container->get('ViewHelperManager');
        /** @var Url $urlManager */
        $urlManager = $pluginManager->get('Url');
        /** @see AgentController::rechercherAction() */
        $urlResponsable =  $urlManager->__invoke('agent/rechercher', [], [], true);

        /** @var AjouterResponsableHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(AjouterResponsableHydrator::class);

        /** @var AjouterResponsableForm $form */
        $form = new AjouterResponsableForm();
        $form->setUrlResponsable($urlResponsable);
        $form->setHydrator($hydrator);
        return $form;
    }
}