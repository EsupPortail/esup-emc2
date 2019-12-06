<?php

namespace Application\View\Helper;

use Application\Entity\Db\FichePoste;
use Application\Entity\Db\Poste;
use Application\View\Renderer\PhpRenderer;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Helper\Partial;
use Zend\View\Resolver\TemplatePathStack;

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

        return $view->partial('fiches-postes-as-table', ['fiches' => $fiches, 'options' => $options]);
    }
}