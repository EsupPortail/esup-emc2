<?php

namespace Formation\View\Helper;

use Application\View\Renderer\PhpRenderer;
use Formation\Entity\Db\FormationInstance;
use Laminas\View\Helper\AbstractHelper;
use Laminas\View\Helper\Partial;
use Laminas\View\Resolver\TemplatePathStack;

/**
 * le tableau d'options peut recevoir un ensemble de booléen pour les droits afin de ne pas recalculer ou transmettre des valeurs différentes.
 * $options['droits']['afficher' => Boolean, 'historiser' => Boolean, 'supprimer' => Boolean]
 */

class FormationInstanceArrayViewHelper extends AbstractHelper
{
    /**
     * @param FormationInstance[] $instances
     * @param array $options
     * @return string|Partial
     */
    public function __invoke(array $instances, array $options = [])
    {
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        usort($instances, function (FormationInstance $a, FormationInstance $b) { return $a->getDebut(true) < $b->getDebut(true); });

        return $view->partial('formation-instance-array', ['instances' => $instances, 'options' => $options]);
    }
}