<?php

namespace Application\View\Helper;

use Application\Entity\Db\Poste;
use Application\View\Renderer\PhpRenderer;
use Laminas\View\Helper\AbstractHelper;
use Laminas\View\Helper\Partial;
use Laminas\View\Resolver\TemplatePathStack;

class PosteViewHelper extends AbstractHelper
{
    /**
     * @param Poste $poste
     * @param array $options
     * @return string|Partial
     */
    public function __invoke($poste, $options = [])
    {
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('poste', ['poste' => $poste, 'options' => $options]);
    }
}