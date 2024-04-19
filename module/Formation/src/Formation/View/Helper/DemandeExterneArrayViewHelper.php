<?php

namespace Formation\View\Helper;

use Formation\Entity\Db\DemandeExterne;
use Formation\Provider\Parametre\FormationParametres;
use Laminas\View\Helper\AbstractHelper;
use Laminas\View\Helper\Partial;
use Laminas\View\Renderer\PhpRenderer;
use Laminas\View\Resolver\TemplatePathStack;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;


class DemandeExterneArrayViewHelper extends AbstractHelper
{
    use ParametreServiceAwareTrait;

    /**
     * @param DemandeExterne[] $demandes
     * @param array $options
     * @return string|Partial
     */
    public function __invoke(array $demandes, array $options = []): string|Partial
    {
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('demande-externe-array', ['demandes' => $demandes, 'plafond' => $this->getParametreService()->getValeurForParametre(FormationParametres::TYPE, FormationParametres::DEMANDE_EXTERNE_PLAFOND),'options' => $options]);
    }
}