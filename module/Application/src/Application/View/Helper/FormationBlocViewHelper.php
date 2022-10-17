<?php

namespace Application\View\Helper;

use Formation\Entity\Db\Interfaces\HasFormationCollectionInterface;
use Laminas\View\Helper\AbstractHelper;
use Laminas\View\Helper\Partial;
use Laminas\View\Resolver\TemplatePathStack;

class FormationBlocViewHelper extends AbstractHelper
{
    /**
     * @param array $formations
     * @param array $options
     * @return string|Partial
     */
    /**
     * @param array $formations
     * @param HasFormationCollectionInterface|null $objet
     * @param array $options
     * @return string|Partial
     */
    public function __invoke(array $formations, ?HasFormationCollectionInterface $objet = null, $options = [])
    {
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('formation-bloc', ['formations' => $formations, 'objet' => $objet, 'options' => $options]);
    }
}