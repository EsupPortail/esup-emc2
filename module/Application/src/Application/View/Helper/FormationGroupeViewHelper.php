<?php

namespace Application\View\Helper;

use Application\Entity\Db\FormationGroupe;
use Application\Service\FicheMetierEtat\FicheMetierEtatServiceAwareTrait;
use Application\View\Renderer\PhpRenderer;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Helper\Partial;
use Zend\View\Resolver\TemplatePathStack;

class FormationGroupeViewHelper extends AbstractHelper
{
    use FicheMetierEtatServiceAwareTrait;

    /**
     * @param FormationGroupe $groupe
     * @param array $options
     * @return string|Partial
     */
    public function __invoke($groupe, $options = ['mode' => 'affichage'])
    {
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('formation-groupe', ['groupe' => $groupe, 'options' => $options]);
    }
}