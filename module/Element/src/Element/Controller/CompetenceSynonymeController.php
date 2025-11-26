<?php

namespace Element\Controller;

use Element\Entity\Db\CompetenceSynonyme;
use Element\Form\CompetenceSynonyme\CompetenceSynonymeFormAwareTrait;
use Element\Service\Competence\CompetenceServiceAwareTrait;
use Element\Service\CompetenceSynonyme\CompetenceSynonymeServiceAwareTrait;
use Laminas\Http\Request;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class CompetenceSynonymeController extends AbstractActionController
{
    use CompetenceServiceAwareTrait;
    use CompetenceSynonymeServiceAwareTrait;
    use CompetenceSynonymeFormAwareTrait;

    public function indexAction(): ViewModel
    {
        $synonymes = $this->getCompetenceSynonymeService()->getCompetencesSynonymes();

        return new ViewModel([
            'synonymes' => $synonymes,
        ]);
    }

    public function afficherAction(): ViewModel
    {
        $competence = $this->getCompetenceService()->getRequestedCompetence($this);
        $synonymes = $this->getCompetenceSynonymeService()->getCompetencesSynonymesByCompetence($competence);

        return new ViewModel([
            'competence' => $competence,
            'synonymes' => $synonymes,
        ]);
    }

    public function ajouterAction(): ViewModel
    {
        $competence = $this->getCompetenceService()->getRequestedCompetence($this);

        $synonyme = new CompetenceSynonyme();
        $form = $this->getCompetenceSynonymeForm();
        $form->setAttribute('action', $this->url()->fromRoute('element/competence-synonyme/ajouter', ['competence' => $competence?->getId()], [], true));
        $form->bind($synonyme);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getCompetenceSynonymeService()->create($synonyme);
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Ajout d'un synonyme pour la competence [".$competence->getLibelle()."]",
            'form' => $form,
            //todo masquer si competence
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public function modifierAction(): ViewModel
    {
        $competenceSynonyme = $this->getCompetenceSynonymeService()->getRequestedCompetenceSynonyme($this);
        $competence = $competenceSynonyme->getCompetence();
        $form = $this->getCompetenceSynonymeForm();
        $form->setAttribute('action', $this->url()->fromRoute('element/competence-synonyme/modifier', ['competence-synonyme' => $competenceSynonyme->getId()], [], true));
        $form->bind($competenceSynonyme);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getCompetenceSynonymeService()->update($competenceSynonyme);
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Modification du synonyme pour la competence [".$competence->getLibelle()."]",
            'form' => $form,
            //todo masquer si competence
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public function supprimerAction(): ViewModel
    {
        $competenceSynonyme = $this->getCompetenceSynonymeService()->getRequestedCompetenceSynonyme($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getCompetenceSynonymeService()->delete($competenceSynonyme);
            exit();
        }

        $vm = new ViewModel();
        if ($competenceSynonyme !== null) {
            $vm->setTemplate('default/confirmation');
            $vm->setVariables([
                'title' => "Suppression du synonyme",
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('element/competence-synonyme/supprimer', ["competence-synonyme" => $competenceSynonyme->getId()], [], true),
            ]);
        }
        return $vm;
    }


}
