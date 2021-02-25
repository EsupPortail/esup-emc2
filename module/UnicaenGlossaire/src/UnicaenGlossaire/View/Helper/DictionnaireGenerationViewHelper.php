<?php

namespace UnicaenGlossaire\View\Helper;

use Application\View\Renderer\PhpRenderer;
use UnicaenGlossaire\Entity\Db\Definition;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Helper\Partial;
use Zend\View\Resolver\TemplatePathStack;

class DictionnaireGenerationViewHelper extends AbstractHelper
{
    /** @var Definition[] */
    private $definitions;

    /**
     * @param Definition[] $definitions
     * @return DictionnaireGenerationViewHelper
     */
    public function setDefinitions(array $definitions): DictionnaireGenerationViewHelper
    {
        $this->definitions = $definitions;
        return $this;
    }

    /**
     * @param array $options
     * @return string|Partial
     */
    public function __invoke($options = [])
    {
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('dictionnaire', ['definitions' => $this->definitions, 'options' => $options]);
    }
}
