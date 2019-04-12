<?php

namespace Application\Controller\FicheMetier;

use Application\Entity\Db\FichePoste;
use Application\Entity\Db\FicheTypeExterne;
use Application\Entity\Db\SpecificitePoste;
use Application\Form\AssocierAgent\AssocierAgentFormAwareTrait;
use Application\Form\FicheMetier\AjouterFicheTypeFormAwareTrait;
use Application\Form\FicheMetier\AssocierMetierTypeForm;
use Application\Form\FicheMetier\AssocierMetierTypeFormAwareTrait;
use Application\Form\FicheMetier\AssocierPosteFormAwareTrait;
use Application\Form\FicheMetier\FicheMetierCreationForm;
use Application\Form\FicheMetier\FicheMetierCreationFormAwareTrait;
use Application\Form\FicheMetier\SpecificitePosteForm;
use Application\Form\FicheMetier\SpecificitePosteFormAwareTrait;
use Application\Service\Activite\ActiviteServiceAwareTrait;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\FicheMetier\FicheMetierServiceAwareTrait;
use UnicaenApp\Exception\RuntimeException;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class FicheMetierController extends AbstractActionController
{
    /** Traits utilisés pour les services */
    use FicheMetierServiceAwareTrait;
    use AgentServiceAwareTrait;
    use ActiviteServiceAwareTrait;
    /** Traits utilisés pour les formulaires*/
    use AssocierAgentFormAwareTrait;
    use AssocierMetierTypeFormAwareTrait;
    use AssocierPosteFormAwareTrait;
    use FicheMetierCreationFormAwareTrait;
    use SpecificitePosteFormAwareTrait;
    use AjouterFicheTypeFormAwareTrait;

       public function afficherAction() {

        $ficheId = $this->params()->fromRoute('id');
        $fiche = $this->getFicheMetierService()->getFicheMetier($ficheId);

        if ($fiche === null) throw new RuntimeException("Aucune fiche ne porte l'identifiant [".$ficheId."]");

        $activites = [];
        if ($fiche->getMetierType()) {
            $activites = $this->getActiviteService()->getActivitesByFicheMetierType($fiche->getMetierType());
        }

        return new ViewModel([
            'fiche' => $fiche,
            'activites' => $activites,
        ]);
    }

    public function editerAction()
    {
        $libelle = 'Environnement du poste de travail dans l\'organisation';
        return new ViewModel([
            'title' => "Édition de <em>".$libelle."</em>",
        ]);
    }

    public function creerAction()
    {
        /** @var FicheMetierCreationForm $form */
        $form = $this->getFicherMetierCreationForm();
        $fiche = new FichePoste();
        $form->bind($fiche);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {

                $fiche = $this->getFicheMetierService()->creer($fiche);
                $this->redirect()->toRoute('fiche-metier/afficher', ['id' => $fiche->getId()], [], true);
            }
        }

        return new ViewModel([
           'form' => $form,
        ]);
    }

    /** FICHE METIER TYPE *********************************************************************************************/

    public function associerMetierTypeAction()
    {
        $ficheId = $this->params()->fromRoute('fiche');
        $fiche = $this->getFicheMetierService()->getFicheMetier($ficheId);

        /** @var AssocierMetierTypeForm $form */
        $form = $this->getAssocierMetierTypeForm();
        $form->setAttribute('action', $this->url()->fromRoute('fiche-metier/associer-metier-type', ['fiche' => $fiche->getId()], [], true));
        $form->bind($fiche);

        /**@var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFicheMetierService()->update($fiche);
            }
        }


        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Associer un metier type',
            'form' => $form,
        ]);
        return $vm;

    }

    /** POSTE *********************************************************************************************************/

    public function associerPosteAction()
    {
        $ficheId = $this->params()->fromRoute('fiche');
        $fiche = $this->getFicheMetierService()->getFicheMetier($ficheId);

        /** @var AssocierAgentForm $form */
        $form = $this->getAssocierPosteForm();
        $form->setAttribute('action', $this->url()->fromRoute('fiche-metier/associer-poste', ['fiche' => $fiche->getId()], [], true));
        $form->bind($fiche);

        /**@var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFicheMetierService()->update($fiche);
            }
        }


        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Associer un poste',
            'form' => $form,
        ]);
        return $vm;

    }
    /** FicheTypeExterne **********************************************************************************************/

    public function ajouterFicheTypeAction() {

        $ficheId = $this->params()->fromRoute('fiche');
        $fiche = $this->getFicheMetierService()->getFicheMetier($ficheId);


        $ficheTypeExterne = new FicheTypeExterne();
        $form = $this->getAjouterFicheTypeForm();
        $form->setAttribute('action', $this->url()->fromRoute('fiche-metier/ajouter-fiche-type', ['fiche' => $fiche->getId()], [], true));
        $form->bind($ficheTypeExterne);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $ficheTypeExterne->setFicheMetier($fiche);
                $this->getFicheMetierService()->createFicheTypeExterne($ficheTypeExterne);
                //$this->redirect()->toRoute('fiche-metier/afficher',['fiche' => $fiche->getId()], [], true);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Ajout d\'une fiche type',
            'form'  => $form,
        ]);
        return $vm;
    }

    public function modifierFicheTypeAction()
    {
        $ficheMetierId = $this->params()->fromRoute('fiche');
        $ficheMetier = $this->getFicheMetierService()->getFicheMetier($ficheMetierId);
        $ficheTypeExterneId = $this->params()->fromRoute('fiche-type-externe');
        $ficheTypeExterne = $this->getFicheMetierService()->getFicheTypeExterne($ficheTypeExterneId);

        $form = $this->getAjouterFicheTypeForm();
        $form->setAttribute('action', $this->url()->fromRoute('fiche-metier/modifier-fiche-type', ['fiche' => $ficheMetier->getId(), 'fiche-type-externe' => $ficheTypeExterne->getId()], [], true));
        $form->bind($ficheTypeExterne);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $ficheTypeExterne->setFicheMetier($ficheMetier);
                $this->getFicheMetierService()->updateFicheTypeExterne($ficheTypeExterne);
                //$this->redirect()->toRoute('fiche-metier/afficher',['fiche' => $fiche->getId()], [], true);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Modification d\'une fiche type',
            'form'  => $form,
        ]);
        return $vm;
    }

    public function retirerFicheTypeAction() {

        $ficheMetierId = $this->params()->fromRoute('fiche');
        $ficheMetier = $this->getFicheMetierService()->getFicheMetier($ficheMetierId);
        $ficheTypeExterneId = $this->params()->fromRoute('fiche-type-externe');
        $ficheTypeExterne = $this->getFicheMetierService()->getFicheTypeExterne($ficheTypeExterneId);

        if ($ficheTypeExterne && $ficheMetier) $this->getFicheMetierService()->deleteFicheTypeExterne($ficheTypeExterne);

        $this->redirect()->toRoute('fiche-metier/afficher',['id' => $ficheMetier->getId()], [], true);
    }

    public function selectionnerActiviteAction() {
        $ficheMetierId = $this->params()->fromRoute('fiche');
        $ficheMetier = $this->getFicheMetierService()->getFicheMetier($ficheMetierId);
        $ficheTypeExterneId = $this->params()->fromRoute('fiche-type-externe');
        $ficheTypeExterne = $this->getFicheMetierService()->getFicheTypeExterne($ficheTypeExterneId);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();

            $result = [];
            foreach ($data as $key => $value) {
                if ($value === 'on') $result[] = $key;
            }
            $result = implode(";", $result);
            $ficheTypeExterne->setActivites($result);
            $this->getFicheMetierService()->updateFicheTypeExterne($ficheTypeExterne);
        }

        return new ViewModel([
            'ficheMetier' => $ficheMetier,
            'ficheTypeExterne' => $ficheTypeExterne,
        ]);

    }


}