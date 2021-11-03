<?php

namespace Application\Service\Url;

use EntretienProfessionnel\Entity\Db\EntretienProfessionnel;
use Zend\View\Renderer\PhpRenderer;

class UrlService {

    /** @var PhpRenderer */
    protected $renderer;
    /** @var array */
    protected $variables;

    /**
     * @param PhpRenderer $renderer
     * @return UrlService
     */
    public function setRenderer($renderer)
    {
        $this->renderer = $renderer;
        return $this;
    }

    /**
     * @param array $variables
     * @return UrlService
     */
    public function setVariables(array $variables): UrlService
    {
        $this->variables = $variables;
        return $this;
    }

    /**
     * @param string
     * @return mixed
     */
    public function getVariable(string $key)
    {
        if (! isset($this->variables[$key])) return null;
        return $this->variables[$key];
    }

    /**
     * @return string
     */
    public function getUrlApp() : string
    {
        $url = $this->renderer->url('home', [], ['force_canonical' => true], true);
        return $url;
    }

    /**
     * @return string
     */
    public function getUrlEntretienAccepter() : string
    {
        /** @var EntretienProfessionnel $entretien */
        $entretien = $this->getVariable('entretien');
        if ($entretien === null) return "<span style='color:darkred'>Variable [entretien] non founie à UrlService</span>";
        $url = $this->renderer->url('entretien-professionnel/accepter-entretien', ['entretien-professionnel' => $entretien->getId(), 'token' => $entretien->getToken()], ['force_canonical' => true], true);
        return $url;
    }

    /**
     * @return string
     */
    public function getUrlEntretienRenseigner() : string
    {
        /** @var EntretienProfessionnel $entretien */
        $entretien = $this->getVariable('entretien');
        if ($entretien === null) return "<span style='color:darkred'>Variable [entretien] non founie à UrlService</span>";
        $url = $this->renderer->url('entretien-professionnel/renseigner', ['entretien' => $entretien->getId()], ['force_canonical' => true], true);
        return $url;
    }
}