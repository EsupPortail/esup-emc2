<?php

namespace Formation\View\Helper;

use Application\View\Renderer\PhpRenderer;
use Formation\Entity\Db\Formation;
use Formation\Entity\Db\FormationInstance;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Helper\Partial;
use Zend\View\Resolver\TemplatePathStack;


class FormationInstanceInformationsViewHelper extends AbstractHelper
{
    /**
     * @param FormationInstance $instance
     * @param string $mode
     * @param array $options
     * @return string|Partial
     */
    public function __invoke(FormationInstance $instance, $mode = 'liste', $options = [])
    {
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('formation-instance-informations', ['instance' => $instance, 'mode' => $mode, 'options' => $options]);
    }
}