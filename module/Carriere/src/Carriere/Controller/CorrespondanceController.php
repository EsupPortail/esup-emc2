<?php

namespace Carriere\Controller;

use Agent\Service\AgentGrade\AgentGradeServiceAwareTrait;
use Application\Service\Util\UtilServiceAwareTrait;
use Carriere\Provider\Parametre\CarriereParametres;
use Carriere\Service\Correspondance\CorrespondanceServiceAwareTrait;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;

class CorrespondanceController extends AbstractActionController
{
    use AgentGradeServiceAwareTrait;
    use CorrespondanceServiceAwareTrait;
    use ParametreServiceAwareTrait;
    use UserServiceAwareTrait;
    use UtilServiceAwareTrait;

    public function indexAction() : ViewModel
    {
        $user = $this->getUserService()->getConnectedUser();
        $agents = $this->getUtilService()->getAgentsSousReponsabilite($user);

        $avecAgent = $this->getParametreService()->getValeurForParametre(CarriereParametres::TYPE, CarriereParametres::CORPS_AVEC_AGENT) === true;
        $specialites = $this->getCorrespondanceService()->getCorrespondances('libelleLong', 'ASC', false);

        $dictionnaire = $this->getAgentGradeService()->generateRecensementBySpecialite($agents);

        return new ViewModel([
            "specialites" => $specialites,
            "agents" => $agents,
            "dictionnaire" => $dictionnaire,

            "avecAgent" => $avecAgent,
        ]);
    }

    public function afficherAction(): ViewModel
    {
        $correspondance = $this->getCorrespondanceService()->getRequestedCorrespondance($this);

        return new ViewModel([
            'title' => "Affichage de la correspondance",
            'correspondance' => $correspondance,
        ]);
    }

    public function afficherAgentsAction() : ViewModel
    {
        $specialite = $this->getCorrespondanceService()->getRequestedCorrespondance($this);

        $user = $this->getUserService()->getConnectedUser();
        $agents = $this->getUtilService()->getAgentsSousReponsabilite($user);
        $dictionnaire = $this->getAgentGradeService()->generateDictionnaireWithSpecialite($specialite, $agents);

        return new ViewModel([
            'title' => 'Agents ayant la spécialité [' . $specialite->getType()?->getCode() .' ' . $specialite->getCategorie() . ']',
            'specialite' => $specialite,
            'agentGrades' => $dictionnaire,
            'agents' => $agents,
        ]);
    }
}