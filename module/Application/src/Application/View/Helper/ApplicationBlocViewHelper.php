<?php

namespace Application\View\Helper;

use Application\Entity\Db\Interfaces\HasCompetenceCollectionInterface;
use Application\View\Renderer\PhpRenderer;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Helper\Partial;
use Zend\View\Resolver\TemplatePathStack;

class ApplicationBlocViewHelper extends AbstractHelper
{
    /**
     * @param array $applications
     * @param array $options
     * @return string|Partial
     */
    /**
     * @param array $applications
     * @param HasCompetenceCollectionInterface|null $objet
     * @param array $options
     * @return string|Partial
     */
    public function __invoke(array $applications, ?HasCompetenceCollectionInterface $objet = null, $options = [])
    {
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('application-bloc', ['applications' => $applications, 'objet' => $objet, 'options' => $options]);
    }
}