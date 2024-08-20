<?php

namespace Formation\View\Helper;

use Formation\Entity\Db\Inscription;
use Formation\Entity\Db\Session;
use Laminas\View\Helper\AbstractHelper;
use Laminas\View\Helper\Partial;
use Laminas\View\Renderer\PhpRenderer;
use Laminas\View\Resolver\TemplatePathStack;

class InscriptionsViewHelper extends AbstractHelper
{
    /**
     * @param Inscription[] $inscriptions
     *
     * Options :
     * 'url-retour' permet de forcer l'url de retour des actions du VH
     *
     * 'display-document' controle l'affichage de la colonne document/attestation (default: true)
     * 'display-liste' controle l'affichage de la colonne affichant les listes (default: true)
     * 'display-historise' controle l'affichage des lignes historisées (default: true)
     */
    public function __invoke(Session $session, array $inscriptions, array $options = []): string|Partial
    {
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('inscriptions', ['session' => $session, 'inscriptions' => $inscriptions, 'options' => $options]);
    }
}