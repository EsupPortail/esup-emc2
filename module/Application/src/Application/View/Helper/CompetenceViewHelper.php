<?php

namespace Application\View\Helper;

use Application\Entity\Db\Competence;
use Application\View\Renderer\PhpRenderer;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Helper\Partial;
use Zend\View\Resolver\TemplatePathStack;

/**
 * Class CompetenceViewHelper
 * @package Application\View\Helper
 *
 * OPTION => li : affichée entre <li> </li>
 *
 */
class CompetenceViewHelper extends AbstractHelper
{
    /**
     * @param Competence $competence
     * @param array $options
     * @return string|Partial
     */
    public function __invoke($competence, $options = [])
    {
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('competence', ['competence' => $competence, 'options' => $options]);
    }
}