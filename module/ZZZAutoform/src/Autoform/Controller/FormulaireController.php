<?php 

namespace Autoform\Controller;

use Autoform\Entity\Db\Categorie;
use Autoform\Entity\Db\Champ;
use Autoform\Entity\Db\Formulaire;
use Autoform\Entity\Db\FormulaireInstance;
use Autoform\Form\Categorie\CategorieForm;
use Autoform\Form\Categorie\CategorieFormAwareTrait;
use Autoform\Form\Champ\ChampFormAwareTrait;
use Autoform\Form\Formulaire\FormulaireFormAwareTrait;
use Autoform\Service\Categorie\CategorieServiceAwareTrait;
use Autoform\Service\Champ\ChampServiceAwareTrait;
use Autoform\Service\Formulaire\FormulaireInstanceServiceAwareTrait;
use Autoform\Service\Formulaire\FormulaireReponseServiceAwareTrait;
use Autoform\Service\Formulaire\FormulaireServiceAwareTrait;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class FormulaireController extends AbstractActionController {
    use CategorieServiceAwareTrait;
    use ChampServiceAwareTrait;
    use FormulaireServiceAwareTrait;
    use FormulaireReponseServiceAwareTrait;
    use FormulaireInstanceServiceAwareTrait;

    use CategorieFormAwareTrait;
    use ChampFormAwareTrait;
    use FormulaireFormAwareTrait;

    /** GESTION DES FORMULAIRES ***************************************************************************************/

    public function indexAction()
    {
        $formulaires = $this->getFormulaireService()->getFormulaires();

        return new ViewModel([
            'formulaires' => $formulaires,
        ]);
    }

    public function creerAction()
    {
        $formulaire = new Formulaire();

        $form = $this->getFormulaireForm();
        $form->setAttribute('action', $this->url()->fromRoute('autoform/creer-formulaire', [], [], true));
        $form->bind($formulaire);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFormulaireService()->create($formulaire);
            }
        }


        $vm = new ViewModel();
        $vm->setTemplate('autoform/default/default-form');
        $vm->setVariables([
            'title' => 'Créer un nouveau formulaire',
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierAction() {
        /** @var Formulaire $formulaire */
        $formulaire = $this->getFormulaireService()->getRequestedFormulaire($this, 'formulaire');

        return new ViewModel([
            'formulaire' => $formulaire,
        ]);
    }

    public function modifierDescriptionAction() {
        /** @var Formulaire $formulaire */
        $formulaire = $this->getFormulaireService()->getRequestedFormulaire($this, 'formulaire');

        $form = $this->getFormulaireForm();
        $form->setAttribute('action', $this->url()->fromRoute('autoform/formulaire/modifier-description', ['formulaire' => $formulaire->getId()], [], true));
        $form->bind($formulaire);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFormulaireService()->update($formulaire);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('autoform/default/default-form');
        $vm->setVariables([
            'title' => 'Modifier la description du formulaire',
            'form' => $form,
        ]);
        return $vm;
    }

    public function historiserAction() {
        /** @var Formulaire $formulaire */
        $formulaire = $this->getFormulaireService()->getRequestedFormulaire($this, 'formulaire');
        $this->getFormulaireService()->historise($formulaire);

        return $this->redirect()->toRoute('autoform/formulaires', [], [] , true);
    }

    public function restaurerAction() {
        /** @var Formulaire $formulaire */
        $formulaire = $this->getFormulaireService()->getRequestedFormulaire($this, 'formulaire');
        $this->getFormulaireService()->restaure($formulaire);

        return $this->redirect()->toRoute('autoform/formulaires', [], [] , true);
    }

    public function detruireAction() {
        /** @var Formulaire $formulaire */
        $formulaire = $this->getFormulaireService()->getRequestedFormulaire($this, 'formulaire');
        $this->getFormulaireService()->delete($formulaire);

        return $this->redirect()->toRoute('autoform/formulaires', [], [] , true);
    }

    /** GESTION DES CATEGORIES ****************************************************************************************/

    public function ajouterCategorieAction() {
        $formulaire = $this->getFormulaireService()->getRequestedFormulaire($this, 'formulaire');
        $categorie = new Categorie();

        /** @var CategorieForm $form */
        $form = $this->getCategorieForm();
        $form->setAttribute('action', $this->url()->fromRoute('autoform/formulaire/modifier/ajouter-categorie', ['formulaire' => $formulaire->getId()], [], true));
        $form->bind($categorie);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFormulaireService()->compacter($formulaire);
                $categorie->setFormulaire($formulaire);
                $categorie->setOrdre(count($formulaire->getCategories()) + 1);
                $categorie->setCode($formulaire->getId(). "_" . uniqid());
                $this->getCategorieService()->create($categorie);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('autoform/default/default-form');
        $vm->setVariables([
            'title' => 'Ajouter une catégorie',
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierCategorieAction() {
        $formulaire = $this->getFormulaireService()->getRequestedFormulaire($this, 'formulaire');
        $categorie = $this->getCategorieService()->getRequestedCategorie($this, 'categorie');

        $form = $this->getCategorieForm();
        $form->setAttribute('action', $this->url()->fromRoute('autoform/formulaire/modifier/modifier-categorie', ['formulaire' => $formulaire->getId()], [], true));
        $form->bind($categorie);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getCategorieService()->update($categorie);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('autoform/default/default-form');
        $vm->setVariables([
            'title' => 'Modifier une catégorie',
            'form' => $form,
        ]);
        return $vm;
    }

    public function historiserCategorieAction() {
        $formulaire = $this->getFormulaireService()->getRequestedFormulaire($this, 'formulaire');
        $categorie = $this->getCategorieService()->getRequestedCategorie($this, 'categorie');
        $this->getCategorieService()->historise($categorie);

        return $this->redirect()->toRoute('autoform/formulaire/modifier', ['formulaire' => $formulaire->getId()], [], true);
    }

    public function restaurerCategorieAction() {
        $formulaire = $this->getFormulaireService()->getRequestedFormulaire($this, 'formulaire');
        $categorie = $this->getCategorieService()->getRequestedCategorie($this, 'categorie');
        $this->getCategorieService()->restaure($categorie);

        return $this->redirect()->toRoute('autoform/formulaire/modifier', ['formulaire' => $formulaire->getId()], [], true);
    }

    public function detruireCategorieAction() {
        $formulaire = $this->getFormulaireService()->getRequestedFormulaire($this, 'formulaire');
        $categorie = $this->getCategorieService()->getRequestedCategorie($this, 'categorie');
        $this->getCategorieService()->delete($categorie);
        $this->getFormulaireService()->compacter($formulaire);

        return $this->redirect()->toRoute('autoform/formulaire/modifier', ['formulaire' => $formulaire->getId()], [], true);
    }

    public function bougerCategorieAction() {
        $formulaire = $this->getFormulaireService()->getRequestedFormulaire($this, 'formulaire');
        $categorie = $this->getCategorieService()->getRequestedCategorie($this, 'categorie');
        $direction = $this->params()->fromRoute('direction');

        $categories = $this->getCategorieService()->getCategoriesAvecSens($categorie, $direction);
        if ($categories && current($categories)) {
            $this->getCategorieService()->swapCategories($categorie, current($categories));
        }

        return $this->redirect()->toRoute('autoform/formulaire/modifier', ['formulaire' => $formulaire->getId()], [], true);
    }

    /** GESTION DES CHAMPS ********************************************************************************************/

    public function ajouterChampAction() {
        $formulaire = $this->getFormulaireService()->getRequestedFormulaire($this, 'formulaire');
        $categorie = $this->getCategorieService()->getRequestedCategorie($this, 'categorie');
        $champ = new Champ();

        $form = $this->getChampForm();
        $form->setAttribute('action', $this->url()->fromRoute('autoform/formulaire/categorie/ajouter-champ', ['formulaire' => $formulaire->getId(), 'categorie' => $categorie->getId()], [], true));
        $form->bind($champ);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getCategorieService()->compacter($categorie);
                $champ->setCategorie($categorie);
                $champ->setOrdre(count($categorie->getChamps()) + 1);
                $champ->setCode($formulaire->getId(). "_". $categorie->getId() ."_" . uniqid());
                $this->getChampService()->create($champ);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('autoform/formulaire/modifier-champ');
        $vm->setVariables([
            'title' => 'Ajouter un champ',
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierChampAction()
    {
        $formulaire = $this->getFormulaireService()->getRequestedFormulaire($this, 'formulaire');
        $categorie = $this->getCategorieService()->getRequestedCategorie($this, 'categorie');
        $champ = $this->getChampService()->getRequestedChamp($this, 'champ');

        $form = $this->getChampForm();
        $form->setAttribute('action', $this->url()->fromRoute('autoform/formulaire/categorie/champ/modifier', ['formulaire' => $formulaire->getId(), 'categorie' => $categorie->getId(), 'champ' => $champ->getId()], [], true));
        $form->bind($champ);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getChampService()->update($champ);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('autoform/formulaire/modifier-champ');
        $vm->setVariables([
            'title' => 'Modifier un champ',
            'form' => $form,
        ]);
        return $vm;
    }

    public function historiserChampAction() {
        $formulaire = $this->getFormulaireService()->getRequestedFormulaire($this, 'formulaire');
        $champ = $this->getChampService()->getRequestedChamp($this, 'champ');
        $this->getChampService()->historise($champ);

        return $this->redirect()->toRoute('autoform/formulaire/modifier', ['formulaire' => $formulaire->getId()], [], true);
    }

    public function restaurerChampAction() {
        $formulaire = $this->getFormulaireService()->getRequestedFormulaire($this, 'formulaire');
        $champ = $this->getChampService()->getRequestedChamp($this, 'champ');
        $this->getChampService()->restaure($champ);

        return $this->redirect()->toRoute('autoform/formulaire/modifier', ['formulaire' => $formulaire->getId()], [], true);
    }

    public function detruireChampAction() {
        $formulaire = $this->getFormulaireService()->getRequestedFormulaire($this, 'formulaire');
        $categorie = $this->getCategorieService()->getRequestedCategorie($this, 'categorie');
        $champ = $this->getChampService()->getRequestedChamp($this, 'champ');
        $this->getChampService()->delete($champ);
        $this->getCategorieService()->compacter($categorie);

        return $this->redirect()->toRoute('autoform/formulaire/modifier', ['formulaire' => $formulaire->getId()], [], true);
    }

    public function bougerChampAction() {
        $formulaire = $this->getFormulaireService()->getRequestedFormulaire($this, 'formulaire');
        $champ = $this->getChampService()->getRequestedChamp($this, 'champ');
        $direction = $this->params()->fromRoute('direction');

        $champs = $this->getChampService()->getChampsAvecSens($champ, $direction);
        if ($champs && current($champs)) {
            $this->getChampService()->swapChamps($champ, current($champs));
        }

        return $this->redirect()->toRoute('autoform/formulaire/modifier', ['formulaire' => $formulaire->getId()], [], true);
    }

    /** AFFICHAGES ****************************************************************************************************/

    /**
     * si dans les paramètres passés en query on a retour alors la redirection "finale" doit être vers cette adresse
     */
    public function afficherFormulaireAction() {
        $formulaire = $this->getFormulaireService()->getRequestedFormulaire($this, 'formulaire');
        $instance = $this->getFormulaireInstanceService()->getRequestedFormulaireInstance($this, 'instance');
        $retour = $this->params()->fromQuery('retour');

        if ($instance === null) {
            $instance = new FormulaireInstance();
            $instance->setFormulaire($formulaire);
            $instance = $this->getFormulaireInstanceService()->create($instance);

            if ($retour) {
                return $this->redirect()->toUrl($retour);
            } else {
                return $this->redirect()->toRoute('autoform/formulaire/afficher-formulaire', ['formulaire' => $formulaire->getId(), 'instance' => $instance->getId()], [], true);
            }
        }

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $this->getFormulaireReponseService()->updateFormulaireReponse($formulaire, $instance, $data);
            if ($retour) {
                return $this->redirect()->toUrl($retour);
            } else {
                return $this->redirect()->toRoute('autoform/formulaire/afficher-formulaire', ['formulaire' => $formulaire->getId(), 'instance' => $instance->getId()], [], true);
            }
        }

        $reponses = $this->getFormulaireReponseService()->getFormulaireResponsesByFormulaireInstance($instance);
        $url = $this->url()->fromRoute('autoform/formulaire/afficher-formulaire', ['formulaire' => $formulaire->getId(), 'instance' => $instance->getId()], [], true);
        if ($retour) $url = $this->url()->fromRoute('autoform/formulaire/afficher-formulaire', ['formulaire' => $formulaire->getId(), 'instance' => $instance->getId()], ['query' => ['retour'=>$retour]], true);

        return new ViewModel([
            'formulaire'    => $formulaire,
            'instance'      => $instance,
            'reponses'      => $reponses,
            'url'           => $url,
            'retour'        => $retour,
        ]);
    }

    public function afficherResultatAction() {
        $formulaire = $this->getFormulaireService()->getRequestedFormulaire($this, 'formulaire');
        $instance = $this->getFormulaireInstanceService()->getRequestedFormulaireInstance($this, 'instance');
        $reponses = $this->getFormulaireReponseService()->getFormulaireResponsesByFormulaireInstance($instance);

        return new ViewModel([
            'formulaire' => $formulaire,
            'reponses' => $reponses,
        ]);
    }
}