<?php

namespace Application\Controller\RessourceRh;

use Application\Entity\Db\Domaine;
use Application\Entity\Db\Metier;
use Application\Entity\Db\FamilleProfessionnelle;
use Application\Entity\Db\MissionSpecifique;
use Application\Form\MissionSpecifique\MissionSpecifiqueFormAwareTrait;
use Application\Form\RessourceRh\DomaineForm;
use Application\Form\RessourceRh\DomaineFormAwareTrait;
use Application\Form\RessourceRh\FonctionFormAwareTrait;
use Application\Form\RessourceRh\FamilleProfessionnelleForm;
use Application\Form\RessourceRh\FamilleProfessionnelleFormAwareTrait;
use Application\Form\RessourceRh\MetierForm;
use Application\Form\RessourceRh\MetierFormAwareTrait;
use Application\Service\Domaine\DomaineServiceAwareTrait;
use Application\Service\FamilleProfessionnelle\FamilleProfessionnelleServiceAwareTrait;
use Application\Service\Fonction\FonctionServiceAwareTrait;
use Application\Service\Metier\MetierServiceAwareTrait;
use Application\Service\RessourceRh\RessourceRhServiceAwareTrait;
use DateTime;
use UnicaenApp\View\Model\CsvModel;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class RessourceRhController extends AbstractActionController {
    /** Trait utilisés pour les services */
    use RessourceRhServiceAwareTrait;

    use DomaineServiceAwareTrait;
    use FamilleProfessionnelleServiceAwareTrait;
    use FonctionServiceAwareTrait;
    use MetierServiceAwareTrait;

    /** Trait utilisés pour les formulaires */
    use DomaineFormAwareTrait;
    use FamilleProfessionnelleFormAwareTrait;
    use FonctionFormAwareTrait;
    use MetierFormAwareTrait;
    use MissionSpecifiqueFormAwareTrait;


    public function indexAction()
    {
        return new ViewModel([]);
    }

    public function indexCorrespondanceAction()
    {
        $correspondances_on  = $this->getRessourceRhService()->getCorrespondances(true);
        $correspondances_off = $this->getRessourceRhService()->getCorrespondances(false);

        return new ViewModel([
            'correspondances_actives'       => $correspondances_on,
            'correspondances_historisees'   => $correspondances_off,
        ]);
    }

    public function indexGradeAction()
    {
        $grades_on  = $this->getRessourceRhService()->getGrades(true);
        $grades_off = $this->getRessourceRhService()->getGrades(false);

        return new ViewModel([
            'grades_actifs'       => $grades_on,
            'grades_historises'   => $grades_off,
        ]);
    }

    public function indexCorpsAction()
    {
        $corps_on  = $this->getRessourceRhService()->getCorps(true);
        $corps_off = $this->getRessourceRhService()->getCorps(false);

        return new ViewModel([
            'corps_actifs'       => $corps_on,
            'corps_historises'   => $corps_off,
        ]);
    }

    public function indexMetierFamilleDomaineAction()
    {
        $familles = $this->getFamilleProfessionnelleService()->getFamillesProfessionnelles();
        $domaines = $this->getDomaineService()->getDomaines();
        $fonctions = $this->getFonctionService()->getFonctions();
        $metiers = $this->getMetierService()->getMetiers();

        return new ViewModel([
            'metiers' => $metiers,
            'familles' => $familles,
            'fonctions' => $fonctions,
            'domaines' => $domaines,
        ]);
    }

    /** METIER ********************************************************************************************************/

    public function creerMetierAction()
    {
        $metier = new Metier();

        /** @var MetierForm $form */
        $form = $this->getMetierForm();
        $form->setAttribute('action', $this->url()->fromRoute('ressource-rh/metier/creer', [], [], true));
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
        $metier = $this->getMetierService()->getRequestedMetier($this, 'id');

        /** @var MetierForm $form */
        $form = $this->getMetierForm();
        $form->setAttribute('action', $this->url()->fromRoute('ressource-rh/metier/modifier', [], [], true));
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
            'title' => 'Éditer un métier',
            'form' => $form,
        ]);
        return $vm;
    }

    public function effacerMetierAction()
    {
        $metier = $this->getMetierService()->getRequestedMetier($this, 'id');

        if ($metier !== null) {
            $this->getMetierService()->delete($metier);
        }

        return $this->redirect()->toRoute('ressource-rh/index-metier-famille-domaine', [], [], true);
    }

    /** FAMILLE PROFESSIONNELLE ***************************************************************************************/

    public function creerFamilleAction()
    {
        $famille = new FamilleProfessionnelle();

        /** @var FamilleProfessionnelleForm $form */
        $form = $this->getFamilleProfessionnelleForm();
        $form->setAttribute('action', $this->url()->fromRoute('ressource-rh/famille/creer', [], [], true));
        $form->bind($famille);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFamilleProfessionnelleService()->create($famille);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Ajouter une nouvelle famille de métiers',
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierFamilleAction()
    {
        $famille = $this->getFamilleProfessionnelleService()->getRequestedFamilleProfessionnelle($this, 'id');

        /** @var FamilleProfessionnelleForm $form */
        $form = $this->getFamilleProfessionnelleForm();
        $form->setAttribute('action', $this->url()->fromRoute('ressource-rh/famille/modifier', [], [], true));
        $form->bind($famille);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFamilleProfessionnelleService()->update($famille);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Éditer une famille de métiers',
            'form' => $form,
        ]);
        return $vm;
    }

    public function effacerFamilleAction()
    {
        $famille = $this->getFamilleProfessionnelleService()->getRequestedFamilleProfessionnelle($this, 'id');

        if ($famille !== null) {
            $this->getFamilleProfessionnelleService()->delete($famille);
        }

        return $this->redirect()->toRoute('ressource-rh/index-metier-famille-domaine', [], [], true);
    }

    /** DOMAINE *******************************************************************************************************/

    public function ajouterDomaineAction()
    {
        /** @var Domaine $domaine */
        $domaine = new Domaine();

        /** @var DomaineForm $form */
        $form = $this->getDomaineForm();
        $form->setAttribute('action', $this->url()->fromRoute('ressource-rh/domaine/ajouter', [], [], true));
        $form->bind($domaine);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getDomaineService()->create($domaine);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Ajouter un domaine',
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierDomaineAction()
    {
        $domaine = $this->getDomaineService()->getRequestedDomaine($this, 'domaine');

        /** @var DomaineForm $form */
        $form = $this->getDomaineForm();
        $form->setAttribute('action', $this->url()->fromRoute('ressource-rh/domaine/modifier', ['domaine' => $domaine->getId()], [], true));
        $form->bind($domaine);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getDomaineService()->update($domaine);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Modifier un domaine',
            'form' => $form,
        ]);
        return $vm;
    }

    public function supprimerDomaineAction()
    {
        $domaine = $this->getDomaineService()->getRequestedDomaine($this, 'domaine');

        if ($domaine !== null) {
            $this->getDomaineService()->delete($domaine);
        }

        return $this->redirect()->toRoute('ressource-rh/index-metier-famille-domaine', [], [], true);
    }

    /** Fonction ***************************************************************************************************/

    public function modifierFonctionAction()
    {
        $fonction = $this->getFonctionService()->getRequestedFonction($this, 'fonction');

        /** @var DomaineForm $form */
        $form = $this->getFonctionForm();
        $form->setAttribute('action', $this->url()->fromRoute('ressource-rh/fonction/modifier', ['fonction' => $fonction->getId()], [], true));
        $form->bind($fonction);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFonctionService()->update($fonction);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Modifier un domaine',
            'form' => $form,
        ]);
        return $vm;
    }

    /** MISSION SPECIFIQUE ********************************************************************************************/

    public function indexMissionSpecifiqueAction()
    {
        $missions = $this->getRessourceRhService()->getMissionsSpecifiques();

        return new ViewModel([
            'missions' => $missions,
        ]);
    }

    public function ajouterMissionSpecifiqueAction()
    {
        $mission = new MissionSpecifique();
        $form = $this->getMissionSpecifiqueForm();
        $form->setAttribute('action', $this->url()->fromRoute('ressource-rh/mission-specifique/ajouter', [], [], true));
        $form->bind($mission);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getRessourceRhService()->createMissionSpecifique($mission);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
           'title' => 'Ajouter une mission spécifique',
           'form' => $form,
        ]);

        return $vm;
    }

    public function modifierMissionSpecifiqueAction()
    {
        $mission = $this->getRessourceRhService()->getRequestedMissionSpecifique($this, 'mission');
        $form = $this->getMissionSpecifiqueForm();
        $form->setAttribute('action', $this->url()->fromRoute('ressource-rh/mission-specifique/modifier', ['mission' => $mission->getId()], [], true));
        $form->bind($mission);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getRessourceRhService()->updateMissionSpecifique($mission);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Modifier une mission spécifique',
            'form' => $form,
        ]);

        return $vm;
    }

    public function historiserMissionSpecifiqueAction()
    {
        $mission = $this->getRessourceRhService()->getRequestedMissionSpecifique($this, 'mission');
        $this->getRessourceRhService()->historiseMissionSpecifique($mission);
        return $this->redirect()->toRoute('ressource-rh/index-mission-specifique', [], [], true);
    }

    public function restaurerMissionSpecifiqueAction() {
        $mission = $this->getRessourceRhService()->getRequestedMissionSpecifique($this, 'mission');
        $this->getRessourceRhService()->restoreMissionSpecifique($mission);
        return $this->redirect()->toRoute('ressource-rh/index-mission-specifique', [], [], true);
    }

    public function supprimerMissionSpecifiqueAction() {
        $mission = $this->getRessourceRhService()->getRequestedMissionSpecifique($this, 'mission');
        $this->getRessourceRhService()->deleteMissionSpecifique($mission);
        return $this->redirect()->toRoute('ressource-rh/index-mission-specifique', [], [], true);
    }


    public function cartographieAction() {
        $metiers = $this->getMetierService()->getMetiers();

        $results = [];
        foreach($metiers as $metier) {
            $fonction = $metier->getFonction();
            $domaine =  $metier->getDomaine();
            $famille = ($domaine)?$domaine->getFamille():null;
            $entry = [
                'famille'  => ($famille)?$famille->__toString():"---",
                'fonction' => ($fonction)?$fonction:"---",
                'domaine'  => ($domaine)?$domaine->__toString():"---",
                'metier'   => ($metier)?$metier->__toString():"---",
                'nbFiche'   => count($metier->getFichesMetiers()),
            ];
            $results[] = $entry;
        }

        usort($results, function($a, $b) {
            if ($a['famille'] !== $b['famille'])     return $a['famille'] < $b['famille'];
            if ($a['fonction'] !== $b['fonction'])   return $a['fonction'] < $b['fonction'];
            if ($a['domaine'] !== $b['domaine'])     return $a['domaine'] < $b['domaine'];
            return $a['metier'] < $b['metier'];
        });

        return new ViewModel([
            'results' => $results,
        ]);
    }

    public function exportCartographieAction() {
        $metiers = $this->getMetierService()->getMetiers();

        $results = [];
        foreach($metiers as $metier) {
            $fonction = $metier->getFonction();
            $domaine = ($metier)?$metier->getDomaine():null;
            $famille = ($domaine)?$domaine->getFamille():null;
            $entry = [
                'famille'  => ($famille)?$famille->__toString():"---",
                'domaine'  => ($domaine)?$domaine->__toString():"---",
                'fonction' => ($fonction)?:"---",
                'metier'   => ($metier)?$metier->__toString():"---",
                'nbFiche'   => count($metier->getFichesMetiers()),
            ];
            $results[] = $entry;
        }

        usort($results, function($a, $b) {
            if ($a['famille'] !== $b['famille'])     return $a['famille'] < $b['famille'];
            if ($a['domaine'] !== $b['domaine'])     return $a['domaine'] < $b['domaine'];
            if ($a['fonction'] !== $b['fonction'])   return $a['fonction'] < $b['fonction'];
            return $a['metier'] < $b['metier'];
        });

        $headers = ['Famille', 'Domaine', 'Fonction', 'Metier', '#Fiche'];

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