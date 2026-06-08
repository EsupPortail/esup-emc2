<?php

namespace Carriere\Controller;

use Agent\Service\AgentGrade\AgentGradeServiceAwareTrait;
use Application\Service\Util\UtilServiceAwareTrait;
use Carriere\Entity\Db\Correspondance;
use Carriere\Form\Specialite\SpecialiteFormAwareTrait;
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

    use SpecialiteFormAwareTrait;

    public function indexAction() : ViewModel
    {
        $user = $this->getUserService()->getConnectedUser();
        $agents = $this->getUtilService()->getAgentsSousReponsabilite($user);

        $avecAgent = $this->getParametreService()->getValeurForParametre(CarriereParametres::TYPE, CarriereParametres::CORRESPONDANCE_AVEC_AGENT) === true;
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

    public function ajouterAction() : ViewModel
    {
        $specialite = new Correspondance();

        $form = $this->getSpecialiteForm();
        $form->setAttribute('action', $this->url()->fromRoute('correspondance/ajouter', [], [], true));
        $form->bind($specialite);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getCorrespondanceService()->create($specialite);
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Ajouter une spécialité",
            'form' => $form,
        ]);
        $vm->setTemplate('carriere/correspondance/formulaire');
        return $vm;
    }

    public function modifierAction() : ViewModel
    {
        $specialite = $this->getCorrespondanceService()->getRequestedCorrespondance($this);

        $form = $this->getSpecialiteForm();
        $form->setAttribute('action', $this->url()->fromRoute('correspondance/modifier', ['correspondance' => $specialite?->getId()], [], true));
        $form->bind($specialite);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getCorrespondanceService()->update($specialite);
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Modifier la spécialité [".$specialite->getType()?->getCode()." ".$specialite->getCategorie()."]",
            'form' => $form,
        ]);
        $vm->setTemplate('carriere/correspondance/formulaire');
        return $vm;
    }

    public function supprimerAction() : ViewModel
    {
        $specialite = $this->getCorrespondanceService()->getRequestedCorrespondance($this);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getCorrespondanceService()->delete($specialite);
            exit();
        }

        $vm = new ViewModel();
        if ($specialite !== null) {

            $warning = null; //todo

            $vm->setTemplate('default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de la spécialité [".$specialite->getType()?->getCode()." ".$specialite->getCategorie()."]",
                'text' => "La suppression est définitive, êtes-vous sûr&middot;e de vouloir continuer ?",
                'warning' => $warning,
                'action' => $this->url()->fromRoute('correspondance/supprimer', ["correspondance" => $specialite->getId()], [], true),
            ]);
        }
        return $vm;
    }
}