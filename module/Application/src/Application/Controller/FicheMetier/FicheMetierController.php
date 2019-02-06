<?php

namespace Application\Controller\FicheMetier;

use Application\Entity\Db\FicheMetier;
use Application\Entity\Db\SpecificitePoste;
use Application\Form\FicheMetier\AssocierAgentForm;
use Application\Form\FicheMetier\AssocierAgentFormAwareTrait;
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

    public function indexAction() {

        $fichesMetiers = $this->getFicheMetierService()->getFichesMetiers();

        return new ViewModel([
            'fichesMetiers' => $fichesMetiers,
        ]);
    }

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

    public function historiserAction()
    {
        $ficheId = $this->params()->fromRoute('id');
        $fiche = $this->getFicheMetierService()->getFicheMetier($ficheId);

        if ($fiche === null) throw new RuntimeException("Aucune fiche ne porte l'identifiant [".$ficheId."]");

        $this->getFicheMetierService()->historiser($fiche);
        $this->redirect()->toRoute('fiche-metier',[], [], true);
    }

    public function restaurerAction()
    {
        $ficheId = $this->params()->fromRoute('id');
        $fiche = $this->getFicheMetierService()->getFicheMetier($ficheId);

        if ($fiche === null) throw new RuntimeException("Aucune fiche ne porte l'identifiant [".$ficheId."]");

        $this->getFicheMetierService()->restaurer($fiche);
        $this->redirect()->toRoute('fiche-metier',[], [], true);
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
        $fiche = new FicheMetier();
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

    /** AGENT *********************************************************************************************************/

    public function associerAgentAction()
    {
        $ficheId = $this->params()->fromRoute('fiche');
        $fiche = $this->getFicheMetierService()->getFicheMetier($ficheId);

        /** @var AssocierAgentForm $form */
        $form = $this->getAssocierAgentForm();
        $form->setAttribute('action', $this->url()->fromRoute('fiche-metier/associer-agent', ['fiche' => $fiche->getId()], [], true));
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
//        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Associer un agent',
            'form' => $form,
            'agents' => $this->getAgentService()->getAgents(),
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
    /** SPECIFICITE POSTE *********************************************************************************************/

    public function editerSpecificitePosteAction()
    {
        $ficheId = $this->params()->fromRoute('fiche');
        $fiche = $this->getFicheMetierService()->getFicheMetier($ficheId);

        $specificite = null;
        if ($fiche->getSpecificite()) {
            $specificite = $fiche->getSpecificite();
        } else {
            $specificite = new SpecificitePoste();
            $fiche->setSpecificite($specificite);
            $this->getFicheMetierService()->createSpecificitePoste($specificite);
        }

        /** @var SpecificitePosteForm $form */
        $form = $form = $this->getSpecificitePosteForm();
        $form->setAttribute('action', $this->url()->fromRoute('fiche-metier/specificite', ['fiche' => $fiche->getId()], [], true));
        $form->bind($specificite);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $specificite->setFiche($fiche);
                $this->getFicheMetierService()->updateSpecificitePoste($specificite);
                $this->getFicheMetierService()->update($fiche);
            }
        }

        return new ViewModel([
            'title' => 'Éditer spécificité du poste',
            'form' => $form,
        ]);

    }
}