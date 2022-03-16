<?php

namespace Element\View\Helper;

use Element\Entity\Db\Interfaces\HasApplicationCollectionInterface;
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
     * @param HasApplicationCollectionInterface|null $objet
     * @param array $options
     * @return string|Partial
     */
    public function __invoke(array $applications, ?HasApplicationCollectionInterface $objet = null, $options = [])
    {
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('application-bloc', ['applications' => $applications, 'objet' => $objet, 'options' => $options]);
    }
}