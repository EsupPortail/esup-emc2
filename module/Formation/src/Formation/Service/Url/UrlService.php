<?php

namespace Formation\Service\Url;

use Formation\Controller\FormationInstanceDocumentController;
use Formation\Controller\IndexController;
use Formation\Controller\PlanDeFormationController;
use Formation\Entity\Db\Session;
use Formation\Entity\Db\Inscription;

class UrlService extends \Application\Service\Url\UrlService
{
    /** @noinspection PhpUnused :: macro MesFormations#AppLink */
    public function getMesFormationsUrl() : string
    {
        /** @see IndexController::indexAction() */
        $url = $this->renderer->url('mes-formations', [], ['force_canonical' => true], true);
        return UrlService::trueLink($url, 'Mes Formations');
    }

    /** @noinspection PhpUnused :: macro */
    public function getUrlSessionAfficher() : string
    {
        /** @var Session $session */
        $session = $this->getVariable('instance');
        if ($session === null) return "<span style='color:darkred'>Variable [instance] non founie à UrlService</span>";
        /** @see SessionController::afficherAction() */
        $url = $this->renderer->url('formation/session/afficher', ['session' => $session->getId()], ['force_canonical' => true], true);
        return  UrlService::trueLink($url);
    }

    /** @noinspection PhpUnused :: macro URL#PlanDeFormation */
    public function getUrlPlanDeFormation() : string
    {
        /** @see PlanDeFormationController::courantAction() */
        $url = $this->renderer->url('plan-de-formation/courant', [], ['force_canonical' => true], true);
        return  UrlService::trueLink($url);
    }

    /** @noinspection PhpUnused :: macro URL#Convocation  */
    public function getUrlConvocation() : string
    {
        /** @var Inscription $inscription */
        $inscription = $this->getVariable('inscription');
        if ($inscription === null) return "<span style='color:darkred'>Variable [inscription] non founie à UrlService</span>";

        /** @see FormationInstanceDocumentController::genererConvocationAction() */
        $url = $this->renderer->url('formation/session/generer-convocation', ['inscription' => $inscription->getId()], ['force_canonical' => true], true);
        return  UrlService::trueLink($url);
    }

    /** @noinspection PhpUnused :: macro URL#Attestation  */
    public function getUrlAttestation() : string
    {
        /** @var Inscription $inscription */
        $inscription = $this->getVariable('inscription');
        if ($inscription === null) return "<span style='color:darkred'>Variable [inscription] non founie à UrlService</span>";

        /** @see FormationInstanceDocumentController::genererAttestationAction() */
        $url = $this->renderer->url('formation/session/generer-attestation', ['inscription' => $inscription->getId()], ['force_canonical' => true], true);
        return  UrlService::trueLink($url);
    }

    /** @noinspection PhpUnused :: macro URL#Absence  */
    public function getUrlAbsence() : string
    {
        /** @var Inscription $inscription */
        $inscription = $this->getVariable('inscription');
        if ($inscription === null) return "<span style='color:darkred'>Variable [inscription] non founie à UrlService</span>";

        /** @see FormationInstanceDocumentController::genererAbsenceAction() */
        $url = $this->renderer->url('formation/session/generer-absence', ['inscription' => $inscription->getId()], ['force_canonical' => true], true);
        return  UrlService::trueLink($url);
    }
}