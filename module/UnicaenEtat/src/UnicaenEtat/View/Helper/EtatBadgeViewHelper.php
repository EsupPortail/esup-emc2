<?php

namespace UnicaenEtat\View\Helper;

use Application\View\Renderer\PhpRenderer;
use UnicaenEtat\Entity\Db\Etat;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Helper\Partial;
use Zend\View\Resolver\TemplatePathStack;

class EtatBadgeViewHelper extends AbstractHelper
{
    /**
     * @param Etat|null $etat
     * @param array $options
     * @return string|Partial
     */
    public function __invoke(?Etat $etat, $options = [])
    {
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('etatbadge', ['etat' => $etat, 'options' => $options]);
    }
}