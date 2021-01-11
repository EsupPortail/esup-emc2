<?php

namespace Application\Controller;

use Application\Entity\Db\Metier;
use Application\Entity\Db\MetierReference;
use Application\Entity\Db\MetierReferentiel;
use Application\Form\Metier\MetierForm;
use Application\Form\Metier\MetierFormAwareTrait;
use Application\Form\MetierReference\MetierReferenceFormAwareTrait;
use Application\Form\MetierReferentiel\MetierReferentielFormAwareTrait;
use Application\Service\Domaine\DomaineServiceAwareTrait;
use Application\Service\FamilleProfessionnelle\FamilleProfessionnelleServiceAwareTrait;
use Application\Service\Metier\MetierServiceAwareTrait;
use Application\Service\MetierReference\MetierReferenceServiceAwareTrait;
use Application\Service\MetierReferentiel\MetierReferentielServiceAwareTrait;
use DateTime;
use UnicaenApp\View\Model\CsvModel;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class MetierController extends AbstractActionController {
    use DomaineServiceAwareTrait;
    use FamilleProfessionnelleServiceAwareTrait;
    use MetierServiceAwareTrait;
    use MetierReferenceServiceAwareTrait;
    use MetierReferentielServiceAwareTrait;

    use MetierFormAwareTrait;
    use MetierReferentielFormAwareTrait;
    use MetierReferenceFormAwareTrait;

    public function indexAction() {
        $familles = $this->getFamilleProfessionnelleService()->getFamillesProfessionnelles();
        $domaines = $this->getDomaineService()->getDomaines();
        $metiers = $this->getMetierService()->getMetiers();
        $referentiels = $this->getMetierReferentielService()->getMetiersReferentiels();


        return new ViewModel([
            'metiers' => $metiers,
            'familles' => $familles,
            'domaines' => $domaines,
            'referentiels' => $referentiels,
        ]);
    }

    /** METIER ********************************************************************************************************/

    public function ajouterMetierAction()
    {
        $metier = new Metier();

        /** @var MetierForm $form */
        $form = $this->getMetierForm();
        $form->setAttribute('action', $this->url()->fromRoute('metier/ajouter-metier', [], [], true));
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

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Ajouter un nouveau métier',
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierMetierAction()
    {
        $metier = $this->getMetierService()->getRequestedMetier($this);

        /** @var MetierForm $form */
        $form = $this->getMetierForm();
        $form->setAttribute('action', $this->url()->fromRoute('metier/modifier-metier', ['metier' => $metier->getId()], [], true));
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

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Modifier un métier',
            'form' => $form,
        ]);
        return $vm;
    }

    public function historiserMetierAction()
    {
        $metier = $this->getMetierService()->getRequestedMetier($this);

        if ($metier !== null) {
            $this->getMetierService()->historise($metier);
        }

        return $this->redirect()->toRoute('metier', [], ["fragment" => "metier"], true);
    }

    public function restaurerMetierAction()
    {
        $metier = $this->getMetierService()->getRequestedMetier($this);

        if ($metier !== null) {
            $this->getMetierService()->restore($metier);
        }

        return $this->redirect()->toRoute('metier', [], ["fragment" => "metier"], true);
    }

    public function effacerMetierAction()
    {
        $metier = $this->getMetierService()->getRequestedMetier($this);


        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getMetierService()->delete($metier);
            //return $this->redirect()->toRoute('home');
            exit();
        }

        $vm = new ViewModel();
        if ($metier !== null) {
            $fiches = $metier->getFichesMetiers();

            if (count($fiches) === 0) {
                $vm->setTemplate('application/default/confirmation');
                $vm->setVariables([
                    'title' => "Suppression du métier " . $metier->getLibelle(),
                    'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                    'action' => $this->url()->fromRoute('metier/effacer-metier', ["metier" => $metier->getId()], [], true),
                ]);
            } else {
                $vm->setTemplate('application/default/probleme');
                $vm->setVariables([
                    'title' => "Suppression du métier " . $metier->getLibelle() . " impossible",
                    'text' => "La suppresion du métier ". $metier->getLibelle() ." est impossible car celui-ci est associé à ". count($fiches). " fiche(s) métier(s).",
                ]);
            }
        }
        return $vm;
    }

    /** METIER REFERENTIEL  *******************************************************************************************/

    public function ajouterReferentielAction()
    {
        $referentiel = new MetierReferentiel();
        $form = $this->getMetierReferentielForm();
        $form->setAttribute('action', $this->url()->fromRoute('metier/ajouter-referentiel', [], [], true));
        $form->bind($referentiel);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getMetierReferentielService()->create($referentiel);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Ajout d'un référentiel métier",
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierReferentielAction()
    {
        $referentiel = $this->getMetierReferentielService()->getRequestedMetierReferentiel($this);
        $form = $this->getMetierReferentielForm();
        $form->setAttribute('action', $this->url()->fromRoute('metier/modifier-referentiel', ['referentiel' => $referentiel->getId()], [], true));
        $form->bind($referentiel);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getMetierReferentielService()->update($referentiel);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Modification d'un référentiel métier",
            'form' => $form,
        ]);
        return $vm;
    }

    public function historiserReferentielAction()
    {
        $referentiel = $this->getMetierReferentielService()->getRequestedMetierReferentiel($this);
        $this->getMetierReferentielService()->historise($referentiel);
        return $this->redirect()->toRoute('metier', [], ["fragment" => "referentiel"], true);
    }

    public function restaurerReferentielAction()
    {
        $referentiel = $this->getMetierReferentielService()->getRequestedMetierReferentiel($this);
        $this->getMetierReferentielService()->restore($referentiel);
        return $this->redirect()->toRoute('metier', [], ["fragment" => "referentiel"], true);
    }

    public function effacerReferentielAction()
    {
        $referentiel = $this->getMetierReferentielService()->getRequestedMetierReferentiel($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getMetierReferentielService()->delete($referentiel);
            //return $this->redirect()->toRoute('home');
            exit();
        }

        $vm = new ViewModel();
        if ($referentiel !== null) {
            $vm->setTemplate('application/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression du référentiel " . $referentiel->getLibelleCourt(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('metier/effacer-referentiel', ["metier" => $referentiel->getId()], [], true),
            ]);
        }
        return $vm;
    }

    /** METIER REFERENCE **********************************************************************************************/

    public function ajouterReferenceAction()
    {
        $metier = $this->getMetierService()->getRequestedMetier($this);
        $reference = new MetierReference();
        $reference->setMetier($metier);
        $form = $this->getMetierReferenceForm();
        $form->setAttribute('action', $this->url()->fromRoute('metier/ajouter-reference', [], [], true));
        $form->bind($reference);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getMetierReferenceService()->create($reference);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Ajout d'une référence",
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierReferenceAction()
    {
        $reference = $this->getMetierReferenceService()->getRequestedMetierReference($this);
        $form = $this->getMetierReferenceForm();
        $form->setAttribute('action', $this->url()->fromRoute('metier/modifier-reference', ['reference' => $reference->getId()], [], true));
        $form->bind($reference);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getMetierReferenceService()->update($reference);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Modification d'une référence métier",
            'form' => $form,
        ]);
        return $vm;
    }

    public function historiserReferenceAction()
    {
        $reference = $this->getMetierReferenceService()->getRequestedMetierReference($this);
        $this->getMetierReferenceService()->historise($reference);
        return $this->redirect()->toRoute('metier', [], ["fragment" => "metier"], true);
    }

    public function restaurerReferenceAction()
    {
        $reference = $this->getMetierReferenceService()->getRequestedMetierReference($this);
        $this->getMetierReferenceService()->restore($reference);
        return $this->redirect()->toRoute('metier', [], ["fragment" => "metier"], true);
    }

    public function effacerReferenceAction()
    {
        $reference = $this->getMetierReferenceService()->getRequestedMetierReference($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getMetierReferenceService()->delete($reference);
            //return $this->redirect()->toRoute('home');
            exit();
        }

        $vm = new ViewModel();
        if ($reference !== null) {
            $vm->setTemplate('application/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de la référence " . $reference->getTitre(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('metier/effacer-reference', ["reference" => $reference->getId()], [], true),
            ]);
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

    public function exportCartographieAction() {
        $results = $this->getMetierService()->generateCartographyArray();

        $headers = [ 'Metier', 'Niveau', 'Références', 'Domaine', 'Fonction', 'Famille', '#Fiche'];

        $today = new DateTime();
        $result = new CsvModel();
        $result->setDelimiter(';');
        $result->setEnclosure('"');
        $result->setHeader($headers);
        $result->setData($results);
        $result->setFilename('cartographie_metier_'.$today->format('Ymd-His').'.csv');

        return $result;
    }
}
