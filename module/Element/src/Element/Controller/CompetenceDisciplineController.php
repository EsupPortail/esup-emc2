<?php

namespace Element\Controller;

use Element\Entity\Db\CompetenceDiscipline;
use Element\Form\CompetenceDiscipline\CompetenceDisciplineFormAwareTrait;
use Element\Service\CompetenceDiscipline\CompetenceDisciplineServiceAwareTrait;
use FicheMetier\Service\FicheMetier\FicheMetierServiceAwareTrait;
use Laminas\Http\Request;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class CompetenceDisciplineController extends AbstractActionController {
    use CompetenceDisciplineServiceAwareTrait;
    use FicheMetierServiceAwareTrait;

    use CompetenceDisciplineFormAwareTrait;

    public function indexAction() : ViewModel
    {
        $disciplines = $this->getCompetenceDisciplineService()->getCompetencesDisciplines(true);
        $dictionnaire = $this->getFicheMetierService()->getFichesMetiersByDisciplines($disciplines);
        return new ViewModel([
            'disciplines' => $disciplines,
            'dictionnaire' => $dictionnaire,
        ]);
    }


    public function afficherAction() : ViewModel
    {
        $discipline = $this->getCompetenceDisciplineService()->getRequestedCompetenceDiscipline($this);
        $fichesMetiers = $this->getFicheMetierService()->getFichesMetiersByDiscipline($discipline);

        return new ViewModel([
            'title' => "Affichage d'une discipline de compétence",
            'discipline' => $discipline,
            'fichesMetiers' => $fichesMetiers,
        ]);
    }

    public function ajouterAction() : ViewModel
    {
        $discipline = new CompetenceDiscipline();
        $form = $this->getCompetenceDisciplineForm();
        $form->setAttribute('action', $this->url()->fromRoute('element/competence-discipline/ajouter', [], [], true));
        $form->bind($discipline);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getCompetenceDisciplineService()->create($discipline);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('default/default-form');
        $vm->setVariables([
            'title' => "Ajout d'une discipline de compétences",
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierAction() : ViewModel
    {
        $discipline = $this->getCompetenceDisciplineService()->getRequestedCompetenceDiscipline($this);
        $form = $this->getCompetenceDisciplineForm();
        $form->setAttribute('action', $this->url()->fromRoute('element/competence-discipline/modifier', ['competence-discipline' => $discipline->getId()], [], true));
        $form->bind($discipline);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getCompetenceDisciplineService()->update($discipline);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('default/default-form');
        $vm->setVariables([
            'title' => "Modification d'une discipline de compétences",
            'form' => $form,
        ]);
        return $vm;
    }

    public function historiserAction() : Response
    {
        $discipline = $this->getCompetenceDisciplineService()->getRequestedCompetenceDiscipline($this);
        $this->getCompetenceDisciplineService()->historise($discipline);
        return $this->redirect()->toRoute('element/competence-discipline', [], [], true);
    }

    public function restaurerAction() : Response
    {
        $discipline = $this->getCompetenceDisciplineService()->getRequestedCompetenceDiscipline($this);
        $this->getCompetenceDisciplineService()->restore($discipline);
        return $this->redirect()->toRoute('element/competence-discipline', [], [], true);
    }

    public function detruireAction() : ViewModel
    {
        $discipline = $this->getCompetenceDisciplineService()->getRequestedCompetenceDiscipline($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getCompetenceDisciplineService()->delete($discipline);
            exit();
        }

        $vm = new ViewModel();
        if ($discipline !== null) {
            $vm->setTemplate('default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de la discipline de compétence  " . $discipline->getLibelle(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('element/competence-discipline/detruire', ["competence-discipline" => $discipline->getId()], [], true),
            ]);
        }
        return $vm;
    }
}