<?php

namespace Metier\Controller;

use Application\Service\Agent\AgentServiceAwareTrait;
use Carriere\Entity\Db\NiveauEnveloppe;
use Carriere\Form\NiveauEnveloppe\NiveauEnveloppeFormAwareTrait;
use Carriere\Service\Niveau\NiveauServiceAwareTrait;
use Carriere\Service\NiveauEnveloppe\NiveauEnveloppeServiceAwareTrait;
use Laminas\Http\Response;
use Metier\Entity\Db\Metier;
use Metier\Form\Metier\MetierFormAwareTrait;
use Metier\Service\Domaine\DomaineServiceAwareTrait;
use Metier\Service\FamilleProfessionnelle\FamilleProfessionnelleServiceAwareTrait;
use Metier\Service\Metier\MetierService;
use Metier\Service\Metier\MetierServiceAwareTrait;
use Metier\Service\Referentiel\ReferentielServiceAwareTrait;
use Laminas\Http\Request;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class MetierController extends AbstractActionController {
    use AgentServiceAwareTrait;
    use DomaineServiceAwareTrait;
    use FamilleProfessionnelleServiceAwareTrait;
    use MetierServiceAwareTrait;
    use NiveauServiceAwareTrait;
    use NiveauEnveloppeServiceAwareTrait;
    use ReferentielServiceAwareTrait;

    use MetierFormAwareTrait;
    use NiveauEnveloppeFormAwareTrait;

    public function indexAction() : ViewModel
    {
        $domaine = $this->params()->fromQuery('domaine');
        $domaine_ = null;
        if ($domaine AND $domaine !== ' ') $domaine_ = $this->getDomaineService()->getDomaine($domaine);

        $historise = $this->params()->fromQuery('historise');

        $metiers = $this->getMetierService()->getMetiers();
        $domaines = $this->getDomaineService()->getDomaines();

        if ($domaine_ !== null) $metiers = array_filter($metiers, function (Metier $m) use ($domaine_) { return $m->hasDomaine($domaine_); });
        if ($historise !== null) $metiers = array_filter($metiers, function (Metier $m) use ($historise) { if ($historise === '1') return $m->estHistorise(); else return $m->estNonHistorise(); });

        return new ViewModel([
            'metiers' => $metiers,
            'domaines' => $domaines,

            'domaine' => $domaine,
            'historise' => $historise,
        ]);
    }

    public function afficherAction() : ViewModel
    {
        $metier = $this->getMetierService()->getRequestedMetier($this);
        $fiches = $metier->getFichesMetiers();

        return new ViewModel([
            'title' => "Affichage du métier",
            'metier' => $metier,
            'fiches' => $fiches,
        ]);
    }

    public function ajouterAction() : ViewModel
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
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public function modifierAction() : ViewModel
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
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public function historiserAction() : Response
    {
        $metier = $this->getMetierService()->getRequestedMetier($this);

        if ($metier !== null) {
            $this->getMetierService()->historise($metier);
        }

        return $this->redirect()->toRoute('metier', [], [], true);
    }

    public function restaurerAction() : Response
    {
        $metier = $this->getMetierService()->getRequestedMetier($this);

        if ($metier !== null) {
            $this->getMetierService()->restore($metier);
        }

        return $this->redirect()->toRoute('metier', [], [], true);
    }

    public function supprimerAction() : ViewModel
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
                $vm->setTemplate('default/confirmation');
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

    public function cartographieAction() : ViewModel
    {
        $results = $this->getMetierService()->generateCartographyArray();

        return new ViewModel([
            'results' => $results,
        ]);
    }

    /** NIVEAUX *******************************************************************************************************/


    public function modifierNiveauxAction() : ViewModel
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
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public function initialiserNiveauxAction() : Response
    {
        $metiers = $this->getMetierService()->getMetiers();
        foreach ($metiers as $metier) {
            $niveau = $this->getNiveauService()->getNiveau($metier->getNiveaux());
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

    public function listerAgentsAction() : ViewModel
    {
        $metier = $this->getMetierService()->getRequestedMetier($this);
        $array = $this->getMetierService()->getInfosAgentsByMetier($metier);
        $agentIds = [];
        foreach ($array as $item) {
            $agentIds[$item['c_individu']] = $item['c_individu'];
        }
        $agents = $this->getAgentService()->getAgentsByIds($agentIds);

        $vm =  new ViewModel([
            'title' => 'Liste des agents ayants une fiche de poste avec un lien au métiers ['.$metier->getLibelle().']',
            'metier' => $metier,
            'agents' => $agents,
            'array' => $array,
        ]);
        return $vm;
    }
}
