<?php

namespace Element\Controller;

use Application\Entity\Db\Agent;
use Application\Entity\Db\FicheMetier;
use Element\Entity\Db\CompetenceElement;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class CompetenceElementController extends AbstractActionController {
    /** GESTION DES COMPETENCES ELEMENTS ==> Faire CONTROLLER ? *******************************************************/

    public function ajouterCompetenceElementAction()
    {
        $type = $this->params()->fromRoute('type');
        $multiple = $this->params()->fromQuery('multiple');

        $hasCompetenceCollection = null;
        switch($type) {
            case Agent::class : $hasCompetenceCollection = $this->getAgentService()->getRequestedAgent($this, 'id');
                break;
            case FicheMetier::class : $hasCompetenceCollection = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'id');
                break;
        }
        $clef = $this->params()->fromRoute('clef');

        if ($hasCompetenceCollection !== null) {
            $element = new CompetenceElement();

            $form = $this->getCompetenceElementForm();

            if ($multiple === '1') {
                $form->get('competence')->setAttribute('multiple', 'multiple');
                $form->remove('clef');
                $form->remove('niveau');
            }

            $form->setAttribute('action', $this->url()->fromRoute('competence/ajouter-competence-element',
                ['type' => $type, 'id' => $hasCompetenceCollection->getId(), 'clef' => $clef],
                ['query' => ['multiple' => $multiple]], true));
            $form->bind($element);
            if ($clef === 'masquer') $form->masquerClef();

            $request = $this->getRequest();
            if ($request->isPost()) {
                $data = $request->getPost();
                if ($multiple !== '1') {
                    $form->setData($data);
                    if ($form->isValid()) {
                        $this->getCompetenceElementService()->create($element);
                        $hasCompetenceCollection->addCompetenceElement($element);
                        switch ($type) {
                            case Agent::class :
                                $this->getAgentService()->update($hasCompetenceCollection);
                                break;
                            case FicheMetier::class :
                                $this->getFicheMetierService()->update($hasCompetenceCollection);
                                break;
                        }
                    }
                } else {
                    $niveau = $this->getNiveauService()->getMaitriseNiveau($data['niveau']);
                    $clef = (isset($data['clef']) AND $data['clef'] === "1")?true:false;
                    foreach ($data['competence'] as $competenceId) {
                        $competence = $this->getCompetenceService()->getCompetence($competenceId);
                        if ($competence !== null AND !$hasCompetenceCollection->hasCompetence($competence)) {
                            $competenceElement = new CompetenceElement();
                            $competenceElement->setClef($clef);
                            $competenceElement->setCompetence($competence);
                            $competenceElement->setNiveauMaitrise($niveau);
                            $competenceElement->setClef($clef);
                            $hasCompetenceCollection->addCompetenceElement($competenceElement);
                            $this->getCompetenceElementService()->create($competenceElement);
                        }
                    }
                    switch ($type) {
                        case Agent::class :
                            $this->getAgentService()->update($hasCompetenceCollection);
                            break;
                        case FicheMetier::class :
                            $this->getFicheMetierService()->update($hasCompetenceCollection);
                            break;
                    }
                }
            }

            $vm = new ViewModel([
                'title' => "Ajout d'une compétence",
                'form' => $form,
            ]);
            $vm->setTemplate('application/default/default-form');
            return $vm;
        }
        exit();
    }
}