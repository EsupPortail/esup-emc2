<?php

namespace Application\Controller\RessourceRh;

use Application\Entity\Db\Corps;
use Application\Entity\Db\Correspondance;
use Application\Entity\Db\Domaine;
use Application\Entity\Db\Grade;
use Application\Entity\Db\Metier;
use Application\Entity\Db\FamilleProfessionnelle;
use Application\Entity\Db\MissionSpecifique;
use Application\Form\MissionSpecifique\MissionSpecifiqueFormAwareTrait;
use Application\Form\RessourceRh\CorpsFormAwareTrait;
use Application\Form\RessourceRh\CorrespondanceFormAwareTrait;
use Application\Form\RessourceRh\DomaineForm;
use Application\Form\RessourceRh\DomaineFormAwareTrait;
use Application\Form\RessourceRh\FonctionFormAwareTrait;
use Application\Form\RessourceRh\GradeForm;
use Application\Form\RessourceRh\GradeFormAwareTrait;
use Application\Form\RessourceRh\FamilleProfessionnelleForm;
use Application\Form\RessourceRh\FamilleProfessionnelleFormAwareTrait;
use Application\Form\RessourceRh\MetierForm;
use Application\Form\RessourceRh\MetierFormAwareTrait;
use Application\Form\RessourceRh\CorpsForm;
use Application\Form\RessourceRh\CorrespondanceForm;
use Application\Service\Domaine\DomaineServiceAwareTrait;
use Application\Service\FamilleProfessionnelle\FamilleProfessionnelleServiceAwareTrait;
use Application\Service\Fonction\FonctionServiceAwareTrait;
use Application\Service\Metier\MetierServiceAwareTrait;
use Application\Service\RessourceRh\RessourceRhServiceAwareTrait;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class RessourceRhController extends AbstractActionController {
    /** Trait utilisés pour les services */
    use RessourceRhServiceAwareTrait;

    use DomaineServiceAwareTrait;
    use FamilleProfessionnelleServiceAwareTrait;
    use FonctionServiceAwareTrait;
    use MetierServiceAwareTrait;

    /** Trait utilisés pour les formulaires */
    use CorpsFormAwareTrait;
    use CorrespondanceFormAwareTrait;
    use DomaineFormAwareTrait;
    use GradeFormAwareTrait;
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
        $correspondances = $this->getRessourceRhService()->getCorrespondances('libelle');

        return new ViewModel([
            'correspondances' => $correspondances,
        ]);
    }

    /** Sub part */
    public function indexCorpsGradeAction()
    {
        $corps = $this->getRessourceRhService()->getCorpsListe('libelle');

        return new ViewModel([
            'corps'  => $corps,
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

    /** CORRESPONDANCE ************************************************************************************************/

    public function creerCorrespondanceAction()
    {
        $correspondance = new Correspondance();

        /** @var CorrespondanceForm $form */
        $form = $this->getCorrespondanceForm();
        $form->setAttribute('action', $this->url()->fromRoute('ressource-rh/correspondance/creer', [], [], true));
        $form->bind($correspondance);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getRessourceRhService()->createCorrespondance($correspondance);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Ajouter une nouvelle catégorie',
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierCorrespondanceAction()
    {
        $correspondanceId = $this->params()->fromRoute('id');
        $correspondance = $this->getRessourceRhService()->getCorrespondance($correspondanceId);

        /** @var CorrespondanceForm $form */
        $form = $this->getCorrespondanceForm();
        $form->setAttribute('action', $this->url()->fromRoute('ressource-rh/correspondance/modifier', [], [], true));
        $form->bind($correspondance);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getRessourceRhService()->updateCorrespondance($correspondance);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Éditer une catégorie',
            'form' => $form,
        ]);
        return $vm;
    }

    public function effacerCorrespondanceAction()
    {
        $correspondanceId = $this->params()->fromRoute('id');
        $correspondance = $this->getRessourceRhService()->getCorrespondance($correspondanceId);

        if ($correspondance !== null) {
            $this->getRessourceRhService()->deleteCorrespondance($correspondance);
        }

        return $this->redirect()->toRoute('ressource-rh', [], [], true);
    }

    /** CORPS *********************************************************************************************************/

    public function creerCorpsAction()
    {
        $corps = new Corps();

        /** @var CorpsForm $form */
        $form = $this->getCorpsForm();
        $form->setAttribute('action', $this->url()->fromRoute('ressource-rh/corps/creer', [], [], true));
        $form->bind($corps);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getRessourceRhService()->createCorps($corps);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Ajouter un nouveau corps',
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierCorpsAction()
    {
        $corpsId = $this->params()->fromRoute('id');
        $corps = $this->getRessourceRhService()->getCorps($corpsId);

        /** @var CorpsForm $form */
        $form = $this->getCorpsForm();
        $form->setAttribute('action', $this->url()->fromRoute('ressource-rh/corps/modifier', [], [], true));
        $form->bind($corps);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getRessourceRhService()->updateCorps($corps);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Éditer un corps',
            'form' => $form,
        ]);
        return $vm;
    }

    public function effacerCorpsAction()
    {
        $corpsId = $this->params()->fromRoute('id');
        $corps = $this->getRessourceRhService()->getCorps($corpsId);

        if ($corps !== null) {
            $this->getRessourceRhService()->deleteCorps($corps);
        }

        return $this->redirect()->toRoute('ressource-rh/index-corps-grade', [], [], true);
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
    /** Grade ******************************************************************************************************/

    public function ajouterGradeAction()
    {
        /** @var Grade $grade */
        $grade = new Grade();

        /** @var GradeForm $form */
        $form = $this->getGradeForm();
        $form->setAttribute('action', $this->url()->fromRoute('ressource-rh/grade/ajouter', [], [], true));
        $form->bind($grade);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getRessourceRhService()->createGrade($grade);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Ajouter un grade',
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierGradeAction()
    {
        /** @var Grade $grade */
        $gradeId = $this->params()->fromRoute('grade');
        $grade = $this->getRessourceRhService()->getGrade($gradeId);

        /** @var GradeForm $form */
        $form = $this->getGradeForm();
        $form->setAttribute('action', $this->url()->fromRoute('ressource-rh/grade/modifier', ['grade' => $grade->getId()], [], true));
        $form->bind($grade);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getRessourceRhService()->updateGrade($grade);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Modifier un grade',
            'form' => $form,
        ]);
        return $vm;
    }

    public function supprimerGradeAction()
    {
        /** @var Grade $grade */
        $gradeId = $this->params()->fromRoute('grade');
        $grade = $this->getRessourceRhService()->getGrade($gradeId);

        if ($grade !== null) {
            $this->getRessourceRhService()->deleteGrade($grade);
        }

        return $this->redirect()->toRoute('ressource-rh/index-corps-grade', [], [], true);
    }

    public function getGradesJsonAction()
    {
        $grades = $this->getRessourceRhService()->getGrades();

        $result = [];
        foreach ($grades as $grade) {
            $result[$grade->getId()] = [
                'id' => $grade->getId(),
                'corps' => $grade->getCorps()->getId(),
                'libelle' => $grade->getLibelle(),
            ];
        }
        $jm = new JsonModel(
            $result
        );
        return $jm;
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
            $domaine = ($fonction)?$fonction->getDomaine():null;
            $famille = ($domaine)?$domaine->getFamille():null;
            $entry = [
                'famille'  => ($famille)?$famille->__toString():"---",
                'domaine'  => ($domaine)?$domaine->__toString():"---",
                'fonction' => ($fonction)?$fonction->__toString():"---",
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

        return new ViewModel([
            'results' => $results,
        ]);
    }



}