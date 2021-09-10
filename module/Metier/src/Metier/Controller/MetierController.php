<?php

namespace Metier\Controller;

use Application\Entity\Db\NiveauEnveloppe;
use Application\Form\NiveauEnveloppe\NiveauEnveloppeFormAwareTrait;
use Application\Service\Niveau\NiveauServiceAwareTrait;
use Application\Service\NiveauEnveloppe\NiveauEnveloppeServiceAwareTrait;
use Metier\Entity\Db\Metier;
use Metier\Form\Metier\MetierFormAwareTrait;
use Metier\Service\Domaine\DomaineServiceAwareTrait;
use Metier\Service\FamilleProfessionnelle\FamilleProfessionnelleServiceAwareTrait;
use Metier\Service\Metier\MetierService;
use Metier\Service\Metier\MetierServiceAwareTrait;
use Metier\Service\Referentiel\ReferentielServiceAwareTrait;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class MetierController extends AbstractActionController {
    use DomaineServiceAwareTrait;
    use FamilleProfessionnelleServiceAwareTrait;
    use MetierServiceAwareTrait;
    use NiveauServiceAwareTrait;
    use NiveauEnveloppeServiceAwareTrait;
    use ReferentielServiceAwareTrait;

    use MetierFormAwareTrait;
    use NiveauEnveloppeFormAwareTrait;

    public function indexAction() {
        $familles = $this->getFamilleProfessionnelleService()->getFamillesProfessionnelles();
        $domaines = $this->getDomaineService()->getDomaines();
        $metiers = $this->getMetierService()->getMetiers();
        $referentiels = $this->getReferentielService()->getReferentiels();

        return new ViewModel([
            'metiers' => $metiers,
            'familles' => $familles,
            'domaines' => $domaines,
            'referentiels' => $referentiels,
        ]);
    }

    public function ajouterAction()
    {
        $metier = new Metier();
        $form = $this->getMetierForm();
        $form->setAttribute('action', $this->url()->fromRoute('metier/ajouter', [], [], true));
        $form->bind($metier);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getMetierService()->create($metier);
            }
        }

        $vm = new ViewModel([
            'title' => 'Ajouter un nouveau métier',
            'form' => $form,
        ]);
        $vm->setTemplate('metier/default/default-form');
        return $vm;
    }

    public function modifierAction()
    {
        $metier = $this->getMetierService()->getRequestedMetier($this);
        $form = $this->getMetierForm();
        $form->setAttribute('action', $this->url()->fromRoute('metier/modifier', ['metier' => $metier->getId()], [], true));
        $form->bind($metier);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getMetierService()->update($metier);
            }
        }

        $vm = new ViewModel([
            'title' => 'Modifier un métier',
            'form' => $form,
        ]);
        $vm->setTemplate('metier/default/default-form');
        return $vm;
    }

    public function historiserAction()
    {
        $metier = $this->getMetierService()->getRequestedMetier($this);

        if ($metier !== null) {
            $this->getMetierService()->historise($metier);
        }

        return $this->redirect()->toRoute('metier', [], ["fragment" => "metier"], true);
    }

    public function restaurerAction()
    {
        $metier = $this->getMetierService()->getRequestedMetier($this);

        if ($metier !== null) {
            $this->getMetierService()->restore($metier);
        }

        return $this->redirect()->toRoute('metier', [], ["fragment" => "metier"], true);
    }

    public function supprimerAction()
    {
        $metier = $this->getMetierService()->getRequestedMetier($this);


        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getMetierService()->delete($metier);
            exit();
        }

        $vm = new ViewModel();
        if ($metier !== null) {
            $fiches = $metier->getFichesMetiers();

            if (count($fiches) === 0) {
                $vm->setTemplate('metier/default/confirmation');
                $vm->setVariables([
                    'title' => "Suppression du métier " . $metier->getLibelle(),
                    'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                    'action' => $this->url()->fromRoute('metier/supprimer', ["metier" => $metier->getId()], [], true),
                ]);
            } else {
                $vm->setTemplate('metier/default/probleme');
                $vm->setVariables([
                    'title' => "Suppression du métier " . $metier->getLibelle() . " impossible",
                    'text' => "La suppresion du métier ". $metier->getLibelle() . " est impossible car celui-ci est associé à ". count($fiches). " fiche(s) métier(s).",
                ]);
            }
        }
        return $vm;
    }

    /** CARTOGRAPHIE ***************************************************************************************************/

    public function cartographieAction() {
        $results = $this->getMetierService()->generateCartographyArray();

        return new ViewModel([
            'results' => $results,
        ]);
    }

    /** NIVEAUX *******************************************************************************************************/


    public function modifierNiveauxAction()
    {
        $metier = $this->getMetierService()->getRequestedMetier($this);

        $niveaux = $metier->getNiveaux();
        if ($niveaux === null) {
            $niveaux = new NiveauEnveloppe();
        }

        $form = $this->getNiveauEnveloppeForm();
        $form->setAttribute('action', $this->url()->fromRoute('metier/modifier-niveaux', ['metier' => $metier->getId()], [], true));
        $form->bind($niveaux);

        $request = $this->getRequest();
        if($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                if ($niveaux->getHistoCreation()) {
                    $this->getNiveauEnveloppeService()->update($niveaux);
                } else {
                    $this->getNiveauEnveloppeService()->create($niveaux);
                    $metier->setNiveaux($niveaux);
                    $this->getMetierService()->update($metier);
                }
            }
        }

        $vm = new ViewModel([
            'title' => "Modifier les niveaux du métier [".MetierService::computeEcritureInclusive($metier->getLibelleFeminin(), $metier->getLibelleMasculin())."]",
            'form' => $form,
        ]);
        $vm->setTemplate('metier/default/default-form');
        return $vm;
    }

    public function initialiserNiveauxAction()
    {
        $metiers = $this->getMetierService()->getMetiers();
        foreach ($metiers as $metier) {
            $niveau = $this->getNiveauService()->getNiveau($metier->getNiveau());
            if ($metier->getNiveaux() === null AND $niveau !== null) {
                $niveaux = new NiveauEnveloppe();
                $niveaux->setBorneInferieure($niveau);
                $niveaux->setBorneSuperieure($niveau);
                $niveaux->setValeurRecommandee($niveau);
                $niveaux->setDescription("Recupérer de l'ancien système de niveau");
                $this->getNiveauEnveloppeService()->create($niveaux);
                $metier->setNiveaux($niveaux);
                $this->getMetierService()->update($metier);
            }
        }

        return $this->redirect()->toRoute('metier');
    }
}
