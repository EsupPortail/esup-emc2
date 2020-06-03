<?php

namespace Application\View\Helper;

use Application\Entity\Db\FichePoste;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Helper\Partial;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Resolver\TemplatePathStack;

//TODO parametrer DIPLSAYS
//TODO parametrer ALLOWED

class FichesPostesAsArrayViewHelper extends AbstractHelper
{
    /**
     * @param FichePoste[] $fiches
     * @param array $options
     * @return string|Partial
     */
    public function __invoke($fiches, $options = [])
    {
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));


        $displays = [
            'agent' => true,
            'poste' => false,
            'fiche-principale' => true,
            'modification' => false,
            'action' => true,
        ];

        return $view->partial('fiches-postes-as-table', ['fiches' => $fiches, 'displays' => $displays, 'options' => $options]);
    }
}