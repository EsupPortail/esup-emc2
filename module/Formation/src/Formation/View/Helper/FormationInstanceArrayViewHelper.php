<?php

namespace Formation\View\Helper;

use Formation\Entity\Db\Session;
use Laminas\View\Helper\AbstractHelper;
use Laminas\View\Helper\Partial;
use Laminas\View\Renderer\PhpRenderer;
use Laminas\View\Resolver\TemplatePathStack;

/**
 * Le tableau d'options peut recevoir un ensemble de booléen pour les droits afin de ne pas recalculer ou transmettre des valeurs différentes.
 * $options['droits']['afficher' => Boolean, 'historiser' => Boolean, 'supprimer' => Boolean]
 */

class FormationInstanceArrayViewHelper extends AbstractHelper
{
    /**
     * @param Session[] $sessions
     * @param array $options
     * @return string|Partial
     */
    public function __invoke(array $sessions, array $options = []): string|Partial
    {
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        usort($sessions, function (Session $a, Session $b) { return $a->getDebut(true) <=> $b->getDebut(true); });

        return $view->partial('sessions-array', ['sessions' => $sessions, 'options' => $options]);
    }
}