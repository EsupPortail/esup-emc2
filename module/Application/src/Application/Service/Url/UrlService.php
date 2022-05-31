<?php

namespace Application\Service\Url;

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
    public function setRenderer($renderer) : UrlService
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
    public function getUrlFichePoste() : string
    {
        $ficheposte = $this->variables['ficheposte'];
        $url = $this->renderer->url('fiche-poste/afficher', ['fiche-poste' => $ficheposte->getId()], ['force_canonical' => true], true);
        return $url;
    }

}