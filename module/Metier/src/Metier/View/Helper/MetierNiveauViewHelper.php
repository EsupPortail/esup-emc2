<?php

namespace Metier\View\Helper;

use Application\View\Renderer\PhpRenderer;
use Metier\Entity\Db\MetierNiveau;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Helper\Partial;
use Zend\View\Resolver\TemplatePathStack;

class MetierNiveauViewHelper extends AbstractHelper
{
    /**
     * @param MetierNiveau|null $metierNiveau
     * @param array $options
     * @return string|Partial
     */
    public function __invoke(?MetierNiveau $metierNiveau, $options = [])
    {
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('metier-niveau', ['metierniveau' => $metierNiveau, 'options' => $options]);
    }
}