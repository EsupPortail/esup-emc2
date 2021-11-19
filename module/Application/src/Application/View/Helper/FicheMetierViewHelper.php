<?php

namespace Application\View\Helper;

    use Application\Entity\Db\FicheMetier;
    use Application\Entity\Db\FichePoste;
    use Application\Entity\Db\FicheTypeExterne;
    use Application\View\Renderer\PhpRenderer;
    use Zend\View\Helper\AbstractHelper;
    use Zend\View\Helper\Partial;
    use Zend\View\Resolver\TemplatePathStack;

class FicheMetierViewHelper extends AbstractHelper
{
    /**
     * @param FicheMetier $fichemetier
     * @param array $options
     * @return string|Partial
     */
    public function __invoke(FicheMetier $fichemetier, array $options = [])
    {
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('fiche-metier', ['fiche' => $fichemetier, 'options' => $options]);
    }
}