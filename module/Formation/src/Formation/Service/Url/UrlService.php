<?php

namespace Formation\Service\Url;

use Formation\Controller\FormationInstanceController;
use Formation\Controller\IndexController;
use Formation\Entity\Db\FormationInstance;

class UrlService extends \Application\Service\Url\UrlService
{
    /** @noinspection PhpUnused :: macro */
    public function getMesFormationsUrl() : string
    {
        /** @see IndexController::indexAction() */
        $url = $this->renderer->url('mes-formations', [], ['force_canonical' => true], true);
        return UrlService::trueLink($url);
    }

    /** @noinspection PhpUnused :: macro */
    public function getUrlSessionAfficher() : string
    {
        /** @var FormationInstance $instance */
        $instance = $this->getVariable('instance');
        if ($instance === null) return "<span style='color:darkred'>Variable [instance] non founie à UrlService</span>";
        /** @see FormationInstanceController::afficherAction() */
        $url = $this->renderer->url('formation-instance/afficher', ['formation-instance' => $instance->getId()], ['force_canonical' => true], true);
        return  UrlService::trueLink($url);
    }

}