<?php

namespace Formation\View\Helper;

use Application\View\Renderer\PhpRenderer;
use Formation\Entity\Db\FormationInstance;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Helper\Partial;
use Zend\View\Resolver\TemplatePathStack;

/**
 * le tableau d'options peut recevoir un ensemble de booléen pour les droits afin de ne pas recalculer ou transmettre des valeurs différentes.
 * $options['droits']['afficher' => Boolean, 'reenvoyer' => Boolean, 'supprimer' => Boolean]
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

        return $view->partial('formation-instance-array', ['instances' => $instances, 'options' => $options]);
    }
}