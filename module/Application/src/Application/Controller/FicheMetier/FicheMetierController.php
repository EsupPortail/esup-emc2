<?php

namespace Application\Controller\FicheMetier;

use Application\Entity\Db\FicheMetier;
use Application\Form\FicheMetier\FicheMetierCreationForm;
use Application\Service\FicheMetier\FicheMetierServiceAwareTrait;
use UnicaenApp\Exception\RuntimeException;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class FicheMetierController extends AbstractActionController
{
    use FicheMetierServiceAwareTrait;

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

        return new ViewModel([
            'fiche' => $fiche,
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
        $form = $this->getServiceLocator()->get('FormElementManager')->get(FicheMetierCreationForm::class);
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
}