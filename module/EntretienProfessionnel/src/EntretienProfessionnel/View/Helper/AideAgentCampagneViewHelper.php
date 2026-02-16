<?php

namespace EntretienProfessionnel\View\Helper;

use Application\Entity\Db\Agent;
use DateInterval;
use DateTime;
use EntretienProfessionnel\Entity\Db\Campagne;
use EntretienProfessionnel\Provider\Etat\EntretienProfessionnelEtats;
use EntretienProfessionnel\Provider\Parametre\EntretienProfessionnelParametres;
use EntretienProfessionnel\Service\Campagne\CampagneServiceAwareTrait;
use EntretienProfessionnel\Service\EntretienProfessionnel\EntretienProfessionnelServiceAwareTrait;
use EntretienProfessionnel\Service\Url\UrlServiceAwareTrait;
use Laminas\View\Helper\AbstractHelper;
use Laminas\View\Helper\Partial;
use Laminas\View\Renderer\PhpRenderer;
use Laminas\View\Resolver\TemplatePathStack;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;

class AideAgentCampagneViewHelper extends AbstractHelper
{
    use CampagneServiceAwareTrait;
    use EntretienProfessionnelServiceAwareTrait;
    use ParametreServiceAwareTrait;
    use UrlServiceAwareTrait;

    public function __invoke(Agent $agent, ?Campagne $campagne = null, array $options = []): string|Partial
    {

        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        $entretien = null;

        $infos = [];
        if ($campagne === null) {
            $actives = $this->getCampagneService()->getCampagnesActives();
            $campagne = current($actives)?current($actives):null;
        }
        if ($campagne !== null) {
            $entretien = $this->getEntretienProfessionnelService()->getEntretienProfessionnelByAgentAndCampagne($agent, $campagne);
            [$obligatoire, $facultatif, $raison] = $this->getCampagneService()->trierAgents($campagne,[$agent]);
            $infos['obligatoire'] = $obligatoire;
            $infos['facultatif'] = $facultatif;
            $infos['raison'] = $raison;
            $infos['delai-observation'] = $this->getParametreService()->getValeurForParametre(EntretienProfessionnelParametres::TYPE, EntretienProfessionnelParametres::DELAI_OBSERVATION_AGENT);

            $date = ($entretien)?DateTime::createFromFormat('Y-m-d H:i:s', $entretien->getDateEntretien()->format('Y-m-d H:i:s')):null;
            if ($date) {
                $date->add(new DateInterval('P'.$infos['delai-observation'].'D'));
                $infos['limite-observation'] = $date;
            }

            if ($entretien AND $entretien->isEtatActif(EntretienProfessionnelEtats::ETAT_ENTRETIEN_ACCEPTATION)) {
                $urlService = $this->getUrlService();
                $urlService->setVariables(['entretien' => $entretien]);
                $infos['url-acceptation'] = $urlService->getUrlEntretienAccepter(false);
            }
            if ($entretien) {
                $urlService = $this->getUrlService();
                $urlService->setVariables(['entretien' => $entretien]);
                $infos['url-entretien'] = $urlService->getUrlEntretienRenseigner(Agent::ROLE_AGENT, false);
            }
        }

        return $view->partial('aide-agent-campagne', ['agent' => $agent, 'campagne' => $campagne, 'entretien' => $entretien, 'infos' => $infos, 'options' => $options]);
    }
}