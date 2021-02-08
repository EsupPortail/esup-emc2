<?php

namespace Application\View\Helper;

use Application\Entity\Db\Agent;
use Application\Entity\Db\FicheMetier;
use Application\Entity\Db\FichePoste;
use Application\Service\ParcoursDeFormation\ParcoursDeFormationServiceAwareTrait;
use Application\View\Renderer\PhpRenderer;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Helper\Partial;
use Zend\View\Resolver\TemplatePathStack;

class ParcoursApplicationViewHelper extends AbstractHelper
{
    use ParcoursDeFormationServiceAwareTrait;

    /**
     * @param $fiche
     * @param Agent|null $agent
     * @param array $options
     * @return string|Partial
     */
    public function __invoke($fiche, ?Agent $agent = null, $options = [])
    {

        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        $applications = [];
        if ($fiche instanceof FichePoste) {
            foreach ($fiche->getFichesMetiers() as $fichesMetierType) {
                foreach ($fichesMetierType->getFicheType()->getApplicationListe() as $application) {
                    $applications[$application->getApplication()->getId()] = $application->getApplication();
                }
            }
        }
        if ($fiche instanceof FicheMetier) {
            foreach ($fiche->getApplicationListe() as $application) {
                $applications[$application->getApplication()->getId()] = $application->getApplication();
            }
        }

        $formations = [];



        return $view->partial('parcours-application', ['applications' => $applications, 'formations' => $formations, 'agent' => $agent, 'options' => $options]);
    }
}