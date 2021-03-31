<?php

namespace Application\View\Helper;

use Application\Entity\Db\Interfaces\HasCompetenceCollectionInterface;
use Application\View\Renderer\PhpRenderer;
use Formation\Entity\Db\Interfaces\HasFormationCollectionInterface;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Helper\Partial;
use Zend\View\Resolver\TemplatePathStack;

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
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('formation-bloc', ['formations' => $formations, 'objet' => $objet, 'options' => $options]);
    }
}