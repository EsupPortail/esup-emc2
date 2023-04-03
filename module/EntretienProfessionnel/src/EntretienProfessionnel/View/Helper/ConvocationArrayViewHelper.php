<?php

namespace EntretienProfessionnel\View\Helper;

use Application\Entity\Db\Agent;
use EntretienProfessionnel\Entity\Db\Campagne;
use Laminas\View\Helper\AbstractHelper;
use Laminas\View\Helper\Partial;
use Laminas\View\Renderer\PhpRenderer;
use Laminas\View\Resolver\TemplatePathStack;

/**
 * le tableau d'options peut recevoir un ensemble de booléen pour les droits afin de ne pas recalculer ou transmettre des valeurs différentes.
 * $options['droits']['afficher' => Boolean, 'renseigner' => Boolean, 'modifier' => Boolean, 'exporter' => Boolean, 'historiser' => Boolean, 'supprimer' => Boolean]
 */

class ConvocationArrayViewHelper extends AbstractHelper
{
    /**
     * @param Agent[] $agents
     * @param Campagne $campagne
     * @param array $options
     * @return string|Partial
     */
    public function __invoke(array $agents, Campagne $campagne, array $options = [])
    {
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('convocation-array', ['agents' => $agents, 'campagne' => $campagne, 'options' => $options]);
    }
}