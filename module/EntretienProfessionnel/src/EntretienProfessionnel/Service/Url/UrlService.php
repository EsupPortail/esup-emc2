<?php

namespace EntretienProfessionnel\Service\Url;

use EntretienProfessionnel\Entity\Db\EntretienProfessionnel;

class UrlService extends \Application\Service\Url\UrlService
{

    /** @noinspection PhpUnused */
    public function getUrlEntretienAccepter() : string
    {
        /** @var EntretienProfessionnel $entretien */
        $entretien = $this->getVariable('entretien');
        if ($entretien === null) return "<span style='color:darkred'>Variable [entretien] non founie à UrlService</span>";
        $url = $this->renderer->url('entretien-professionnel/accepter-entretien', ['entretien-professionnel' => $entretien->getId(), 'token' => $entretien->getToken()], ['force_canonical' => true], true);
        return $url;
    }

    /** @noinspection PhpUnused */
    public function getUrlEntretienRenseigner() : string
    {
        /** @var EntretienProfessionnel $entretien */
        $entretien = $this->getVariable('entretien');
        if ($entretien === null) return "<span style='color:darkred'>Variable [entretien] non founie à UrlService</span>";
        $url = $this->renderer->url('entretien-professionnel/acceder', ['entretien-professionnel' => $entretien->getId()], ['force_canonical' => true], true);
        return $url;
    }
}