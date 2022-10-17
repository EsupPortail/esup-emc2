<?php

namespace Application\View\Helper;

use Application\Entity\Db\Agent;
use Carriere\Entity\Db\Categorie;
use Metier\Entity\Db\Metier;
use Application\Entity\Db\ParcoursDeFormation;
use Application\Service\ParcoursDeFormation\ParcoursDeFormationServiceAwareTrait;
use Application\View\Renderer\PhpRenderer;
use Laminas\View\Helper\AbstractHelper;
use Laminas\View\Helper\Partial;
use Laminas\View\Resolver\TemplatePathStack;

class ParcoursDeFormationViewHelper extends AbstractHelper
{
    use ParcoursDeFormationServiceAwareTrait;

    /**
     * @param ParcoursDeFormation $parcours
     * @param Agent $agent
     * @param array $options
     * @return string|Partial
     */
    public function __invoke($parcours, $agent = null, $options = [])
    {
        /** @var Metier|Categorie $reference */
        $reference = $this->getParcoursDeFormationService()->getReference($parcours);

        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('parcours-de-formation', ['parcours' => $parcours, 'reference' => $reference, 'agent' => $agent, 'options' => $options]);
    }
}