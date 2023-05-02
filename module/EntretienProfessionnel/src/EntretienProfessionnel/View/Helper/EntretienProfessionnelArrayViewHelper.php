<?php

namespace EntretienProfessionnel\View\Helper;

use EntretienProfessionnel\Entity\Db\EntretienProfessionnel;
use Laminas\View\Helper\AbstractHelper;
use Laminas\View\Helper\Partial;
use Laminas\View\Renderer\PhpRenderer;
use Laminas\View\Resolver\TemplatePathStack;

/**
 * le tableau d'options peut recevoir un ensemble de booléen pour les droits afin de ne pas recalculer ou transmettre des valeurs différentes.
 * $options['affichages']['campagne' => Boolean, 'structure' => Boolean, 'agent' => Boolean, 'responsable' => Boolean, 'date' => Boolean, 'etat' => Boolean, 'action' => Boolean]
 * $options['droits']['afficher' => Boolean, 'renseigner' => Boolean, 'modifier' => Boolean, 'exporter' => Boolean, 'historiser' => Boolean, 'supprimer' => Boolean]
 */

class EntretienProfessionnelArrayViewHelper extends AbstractHelper
{
    protected array $entretiens;
    protected array $options;

    public function __invoke(array $entretiens, array $options = [])
    {
        $this->entretiens = $entretiens;
        $this->options = $options;

        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('entretien-professionnel-array', ['entretiens' => $this->entretiens, 'options' => $this->options]);
    }

    public function __toString()
    {
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('entretien-professionnel-array', ['entretiens' => $this->entretiens, 'options' => $this->options]);
    }
}