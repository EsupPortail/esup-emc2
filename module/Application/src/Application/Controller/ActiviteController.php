<?php

namespace Application\Controller;

use Application\Entity\Db\Activite;
use Application\Entity\Db\ActiviteDescription;
use Application\Form\Activite\ActiviteForm;
use Application\Form\Activite\ActiviteFormAwareTrait;
use Application\Form\SelectionApplication\SelectionApplicationFormAwareTrait;
use Application\Form\SelectionCompetence\SelectionCompetenceFormAwareTrait;
use Application\Form\SelectionFormation\SelectionFormationFormAwareTrait;
use Application\Service\Activite\ActiviteServiceAwareTrait;
use Application\Service\ActiviteDescription\ActiviteDescriptionServiceAwareTrait;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ActiviteController  extends AbstractActionController {
    /** Traits associé aux services */
    use ActiviteServiceAwareTrait;
    use ActiviteDescriptionServiceAwareTrait;
    /** Traits associé aux formulaires */
    use ActiviteFormAwareTrait;
    use SelectionApplicationFormAwareTrait;
    use SelectionCompetenceFormAwareTrait;
    use SelectionFormationFormAwareTrait;

    public function indexAction()
    {
        /** @var Activite[] $activites */
        $activites = $this->getActiviteService()->getActivites('id');

        return new ViewModel([
            'activites' => $activites,
        ]);
    }

    public function creerAction()
    {
        /** @var Activite $activite */
        $activite = new Activite();

        /** @var ActiviteForm $form */
        $form = $this->getActiviteForm();
        $form->setAttribute('action', $this->url()->fromRoute('activite/creer',[],[], true));
        $form->bind($activite);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);

            if ($form->isValid()) {
                $this->getActiviteService()->create($activite);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Ajouter une activité',
            'form' => $form,
        ]);
        return $vm;
    }

    public function editerAction()
    {
        /** @var Activite $activite */
        $activite = $this->getActiviteService()->getRequestedActivite($this, 'activite');

        /** @var ActiviteForm $form */
        $form = $this->getActiviteForm();
        $form->setAttribute('action', $this->url()->fromRoute('activite/editer',['id' => $activite->getId()],[], true));
        $form->bind($activite);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);

            if ($form->isValid()) {
                $this->getActiviteService()->update($activite);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Modifier une activité',
            'form' => $form,
        ]);
        return $vm;
    }

    public function historiserAction()
    {
        /** @var Activite $activite */
        $activite = $this->getActiviteService()->getRequestedActivite($this, 'activite');

        $this->getActiviteService()->historise($activite);
        return $this->redirect()->toRoute('activite');
    }

    public function restaurerAction()
    {
        /** @var Activite $activite */
        $activite = $this->getActiviteService()->getRequestedActivite($this, 'activite');

        $this->getActiviteService()->restore($activite);
        return $this->redirect()->toRoute('activite');
    }


    public function detruireAction()
    {
        /** @var Activite $activite */
        $activite = $this->getActiviteService()->getRequestedActivite($this, 'activite');

        $this->getActiviteService()->delete($activite);
        return $this->redirect()->toRoute('activite');
    }

    public function afficherAction()
    {
        $activite = $this->getActiviteService()->getRequestedActivite($this, 'activite');

        return new ViewModel([
            'title' => 'Visualisation d\'une activité',
            'activite' => $activite,
        ]);
    }

    public function modifierAction()
    {
        $activite = null;
        if ($this->params()->fromRoute('activite') !== null) {
            $activite = $this->getActiviteService()->getRequestedActivite($this, 'activite');
        }
        if ($activite === null) $activite = new Activite();

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();

            $seen = [];
            for($position = 1 ; $position < $data['count'] ; $position++) {
                $current = $position;
                if (isset($data['description_'.$position]) AND $data['description_'.$position] != '') {
                    $description_text =  $data['description_' . $position];
                    if (isset($data['reference_'.$position]) AND $data['reference_'.$position] != '') {
                        $description = $this->getActiviteDescriptionService()->getActiviteDescription($data['reference_'.$position]);
                        $description->setDescription($data['description_' . $position]);
                        $this->getActiviteDescriptionService()->update($description);
                        $seen[] = $description->getId();
                    } else {
                        $description = new ActiviteDescription();
                        $description->setActivite($activite);
                        $description->setDescription($data['description_' . $position]);
                        $this->getActiviteDescriptionService()->create($description);
                        $activite->addDescription($description);
                        $seen[] = $description->getId();
                    }
                }
            }
            foreach ($activite->getDescriptions() as $description) {
                if (array_search($description->getId(), $seen) === false) {
                    $this->getActiviteDescriptionService()->delete($description);
                    $activite->removeDescription($description);
                }
            }
            $this->getActiviteService()->update($activite);

            $a = 1;
        }


//        $form = $this->getActiviteBisForm();
//        $form->setAttribute('action', $this->url()->fromRoute('activite/modifier-bis', ['activite' => $activite->getId()], [], true));
//        $form->bind($activite);

        return new ViewModel([
            'title' => 'Modification des sous-activités',
            'activite' => $activite,
        ]);
    }

    /**
     * Action convertissant les anciennes description d'activité en nouvelles versions
     */
    public function convertAction()
    {
        $activite = $this->getActiviteService()->getRequestedActivite($this, 'activite');
        $descriptions = $activite->getDescription();

        /** retirer le <ul></ul> */
        $descriptions = str_replace(["\n","\r"],["",""], $descriptions);
        $descriptions = preg_replace("/^<ul><li>/", "", $descriptions);
        $descriptions = preg_replace("/<\/li><\/ul>$/", "", $descriptions);
        $elements = explode("</li><li>", $descriptions);

        $new = [];
        foreach ($elements as $element) {
            $description = new ActiviteDescription();
            $description->setActivite($activite);
            $description->setDescription($element);
            $new[] = $description;
        }

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();

            if($data['reponse'] === 'oui') {
                $descriptions = $activite->getDescriptions();
                foreach ($descriptions as $description) $this->getActiviteDescriptionService()->delete($description);
                $activite->clearDescriptions();
                $this->getActiviteService()->update($activite);

                foreach ($new as $item) {
                    $item->setActivite($activite);
                    $this->getActiviteDescriptionService()->create($item);
                    $activite->addDescription($item);
                }
                $this->getActiviteService()->update($activite);
            }
            exit();
        }

        return new ViewModel([
            'title' => "Convertion de l'activite [".$activite->getLibelle()."].",
            'activite' => $activite,
            'new' => $new,
            'action' => $this->url()->fromRoute('activite/convert', ['activite' => $activite->getId()], [], true),
        ]);
    }

    public function modifierLibelleAction() {

    }

    public function ajouterDescriptionsAction() {

    }

    public function modifierDescriptionAction() {

    }

    public function modifierApplicationAction() {
        $activite = $this->getActiviteService()->getRequestedActivite($this);

        $form = $this->getSelectionApplicationForm();
        $form->bind($activite);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $this->getActiviteService()->updateApplications($activite, $data);
            exit();
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Sélection des applications associées à l'activité",
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierCompetenceAction() {
        $activite = $this->getActiviteService()->getRequestedActivite($this);

        $form = $this->getSelectionCompetenceForm();
        $form->bind($activite);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $this->getActiviteService()->updateCompetences($activite, $data);
            exit();
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Sélection des compétences associées à l'activité",
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierFormationAction() {
        $activite = $this->getActiviteService()->getRequestedActivite($this);

        $form = $this->getSelectionFormationForm();
        $form->bind($activite);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $this->getActiviteService()->updateFormations($activite, $data);
            exit();
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Sélection des formations associées à l'activité",
            'form' => $form,
        ]);
        return $vm;
    }

}