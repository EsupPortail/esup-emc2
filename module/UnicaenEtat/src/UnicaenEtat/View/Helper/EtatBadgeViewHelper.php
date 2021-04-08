<?php

namespace UnicaenEtat\View\Helper;

use Application\View\Renderer\PhpRenderer;
use UnicaenEtat\Entity\Db\Etat;
use UnicaenEtat\Service\Etat\EtatServiceAwareTrait;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Helper\Partial;
use Zend\View\Resolver\TemplatePathStack;

class EtatBadgeViewHelper extends AbstractHelper
{
    use EtatServiceAwareTrait;
    /**
     * @param Etat|string|int|null $etat
     * @param array $options
     * @return string|Partial
     */
    public function __invoke($etat, $options = [])
    {
        if (is_int($etat)) { $etat = $this->getEtatService()->getEtat($etat); }
        if (is_string($etat)) { $etat = $this->getEtatService()->getEtatByCode($etat); }

        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('etatbadge', ['etat' => $etat, 'options' => $options]);
    }
}