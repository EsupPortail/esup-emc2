<?php

namespace Application\Service\Url;

use DateTime;
use Laminas\View\Renderer\PhpRenderer;

class UrlService {

    protected ?PhpRenderer $renderer = null;
    protected array $variables = [];

    /**
     * @param PhpRenderer $renderer
     * @return UrlService
     */
    public function setRenderer(PhpRenderer $renderer) : UrlService
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

    public function getVariable(string $key): mixed
    {
        if (! isset($this->variables[$key])) return null;
        return $this->variables[$key];
    }

    /** @noinspection PhpUnused */
    public function getUrlApp() : string
    {
        $url = $this->renderer->url('home', [], ['force_canonical' => true], true);
        return UrlService::trueLink($url);
    }

    /** @noinspection PhpUnused */
    public function getNameApp() : string
    {
        return 'EMC2 : Emploi Mobilité Carrière Compétence';
    }

    /** @noinspection PhpUnused */
    public function toStringDate() : string
    {
        $date = new DateTime();
        return $date->format('d/m/Y');
    }

    /** @noinspection PhpUnused */
    public function toStringDateTime() : string
    {
        $date = new DateTime();
        return $date->format('d/m/Y à H:m');
    }

    /** @noinspection PhpUnused */
    public function getUrlFichePoste() : string
    {
        $ficheposte = $this->variables['ficheposte'];
        $url = $this->renderer->url('fiche-poste/afficher', ['fiche-poste' => $ficheposte->getId()], ['force_canonical' => true], true);
        return UrlService::trueLink($url);
    }

    static public function trueLink(string $url, string $texte = null) : string
    {
        return "<a href='".$url."' target='_blank'>".($texte??$url)."</a>";
    }

}