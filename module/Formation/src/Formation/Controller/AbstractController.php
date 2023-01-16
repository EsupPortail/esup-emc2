<?php

namespace Formation\Controller;

use UnicaenApp\Controller\Plugin\MultipageFormPlugin;
use UnicaenApp\Form\MultipageForm;
use Laminas\Mvc\MvcEvent;
use Laminas\Mvc\Plugin\FlashMessenger\FlashMessenger;
use ZfcUser\Controller\Plugin\ZfcUserAuthentication;
use Laminas\Http\Request as HttpRequest;
use Laminas\Mvc\Controller\AbstractActionController;

/**
 * Class AbstractController
 *
 * @method ZfcUserAuthentication zfcUserAuthentication()
 * @method HttpRequest getRequest()
 * @method FlashMessenger flashMessenger()
 * @method MultipageFormPlugin multipageForm(?MultipageForm $form = null)
 */
class AbstractController extends AbstractActionController
{
    /**
     * Modification du layout pour toutes les pages "Octopass"
     */
    public function onDispatch(MvcEvent $e)
    {
        // Call the base class' onDispatch() first and grab the response
        $response = parent::onDispatch($e);

        // Set alternative layout
        $this->layout()->setTemplate('mes-formations/layout');

        // Return the response
        return $response;
    }
}