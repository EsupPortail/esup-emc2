<?php

namespace Formation\Controller;

use Application\Entity\Db\Interfaces\HasSourceInterface;
use Formation\Entity\Db\FormationGroupe;
use Formation\Form\FormationGroupe\FormationGroupeFormAwareTrait;
use Formation\Form\SelectionFormationGroupe\SelectionFormationGroupeFormAwareTrait;
use Formation\Service\Formation\FormationServiceAwareTrait;
use Formation\Service\FormationGroupe\FormationGroupeServiceAwareTrait;
use Laminas\Http\Request;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class FormationGroupeController extends AbstractActionController
{
    use FormationServiceAwareTrait;
    use FormationGroupeServiceAwareTrait;
    use FormationGroupeFormAwareTrait;
    use SelectionFormationGroupeFormAwareTrait;

    public function indexAction(): ViewModel
    {
        $source = $this->params()->fromQuery('source');
        $historise = $this->params()->fromQuery('historise');

        $groupes = $this->getFormationGroupeService()->getFormationsGroupes();
        if ($source !== null) $groupes = array_filter($groupes, function (FormationGroupe $a) use ($source) {
            return $a->getSource() === $source;
        });
        if ($historise !== null) $groupes = array_filter($groupes, function (FormationGroupe $a) use ($historise) {
            if ($historise === "1") return $a->estHistorise();
            if ($historise === "0") return $a->estNonHistorise();
            return true;
        });

        return new ViewModel([
            'groupes' => $groupes,
            'source' => $source,
            'historise' => $historise,
        ]);
    }

    /** CRUD **********************************************************************************************************/

    public function afficherAction(): ViewModel
    {
        $groupe = $this->getFormationGroupeService()->getRequestedFormationGroupe($this);

        return new ViewModel([
            'title' => 'Affichage du thème',
            'groupe' => $groupe,
        ]);
    }

    public function ajouterGroupeAction(): ViewModel
    {
        $groupe = new FormationGroupe();
        $form = $this->getFormationGroupeForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation-groupe/ajouter', [], [], true));
        $form->bind($groupe);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $groupe->setSource(HasSourceInterface::SOURCE_EMC2);
                $this->getFormationGroupeService()->create($groupe);
                $groupe->setIdSource($groupe->getId());
                $this->getFormationGroupeService()->update($groupe);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('default/default-form');
        $vm->setVariables([
            'title' => 'Ajouter un thème de formation',
            'form' => $form,
        ]);
        return $vm;
    }

    public function editerGroupeAction(): ViewModel
    {
        $groupe = $this->getFormationGroupeService()->getRequestedFormationGroupe($this);
        $form = $this->getFormationGroupeForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation-groupe/editer', ['formation-groupe' => $groupe->getId()], [], true));
        $form->bind($groupe);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFormationGroupeService()->update($groupe);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('default/default-form');
        $vm->setVariables([
            'title' => 'Modifier un thème de formation',
            'form' => $form,
        ]);
        return $vm;
    }

    public function historiserGroupeAction(): Response
    {
        $groupe = $this->getFormationGroupeService()->getRequestedFormationGroupe($this);
        $this->getFormationGroupeService()->historise($groupe);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('formation-groupe', [], [], true);
    }

    public function restaurerGroupeAction(): Response
    {
        $groupe = $this->getFormationGroupeService()->getRequestedFormationGroupe($this);
        $this->getFormationGroupeService()->restore($groupe);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('formation-groupe', [], [], true);
    }

    public function detruireGroupeAction(): ViewModel
    {
        $groupe = $this->getFormationGroupeService()->getRequestedFormationGroupe($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getFormationGroupeService()->delete($groupe);
            exit();
        }

        $vm = new ViewModel();
        if ($groupe !== null) {
            $vm->setTemplate('default/confirmation');
            $vm->setVariables([
                'title' => "Suppression du thème de formation [" . $groupe->getLibelle() . "]",
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('formation-groupe/detruire', ["formation-groupe" => $groupe->getId()], [], true),
            ]);
        }
        return $vm;
    }

    /** DEBOULONNAGE **************************************************************************************************/

    public function dedoublonnerAction(): ViewModel
    {
        $groupe = $this->getFormationGroupeService()->getRequestedFormationGroupe($this);

        $form = $this->getSelectionFormationGroupeForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation-groupe/dedoublonner', ['formation-groupe' => $groupe->getId()], [], true));

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $groupeSub = $this->getFormationGroupeService()->getFormationGroupe($data['groupes']);

            if ($groupeSub and $groupeSub !== $groupe) {
                //décalages des formations
                $formations = $groupe->getFormations();
                foreach ($formations as $formation) {
                    $formation->setGroupe($groupeSub);
                    $this->getFormationService()->update($formation);
                }

                $this->getFormationGroupeService()->delete($groupe);
                $this->getFormationGroupeService()->update($groupeSub);
            }

        }

        $vm = new ViewModel();
        $vm->setTemplate('default/default-form');
        $vm->setVariables([
            'title' => "Sélection de le groupe qui remplacera [" . $groupe->getLibelle() . "]",
            'form' => $form,
        ]);
        return $vm;
    }
}