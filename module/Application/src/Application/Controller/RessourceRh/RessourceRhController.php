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
use Application\Form\RessourceRh\GradeForm;
use Application\Form\RessourceRh\GradeFormAwareTrait;
use Application\Form\RessourceRh\FamilleProfessionnelleForm;
use Application\Form\RessourceRh\FamilleProfessionnelleFormAwareTrait;
use Application\Form\RessourceRh\MetierForm;
use Application\Form\RessourceRh\MetierFormAwareTrait;
use Application\Form\RessourceRh\CorpsForm;
use Application\Form\RessourceRh\CorrespondanceForm;
use Application\Service\FamilleProfessionnelle\FamilleProfessionnelleServiceAwareTrait;
use Application\Service\RessourceRh\RessourceRhServiceAwareTrait;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class RessourceRhController extends AbstractActionController {
    /** Trait utilisés pour les services */
    use RessourceRhServiceAwareTrait;
    use FamilleProfessionnelleServiceAwareTrait;

    /** Trait utilisés pour les formulaires */
    use CorpsFormAwareTrait;
    use CorrespondanceFormAwareTrait;
    use DomaineFormAwareTrait;
    use GradeFormAwareTrait;
    use FamilleProfessionnelleFormAwareTrait;
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
        $metiers = $this->getRessourceRhService()->getMetiers('libelle');
        $familles = $this->getFamilleProfessionnelleService()->getFamillesProfessionnelles();
        $domaines = $this->getRessourceRhService()->getDomaines('libelle');

        return new ViewModel([
            'metiers' => $metiers,
            'familles' => $familles,
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
                $this->getRessourceRhService()->createMetier($metier);
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
        $metierId = $this->params()->fromRoute('id');
        $metier = $this->getRessourceRhService()->getMetier($metierId);

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
                $this->getRessourceRhService()->updateMetier($metier);
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
        $metierId = $this->params()->fromRoute('id');
        $metier = $this->getRessourceRhService()->getMetier($metierId);

        if ($metier !== null) {
            $this->getRessourceRhService()->deleteMetier($metier);
        }

        return $this->redirect()->toRoute('ressource-rh', [], [], true);
    }

    /** FAMILLE METIER ************************************************************************************************/

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
                $this->getRessourceRhService()->createDomaine($domaine);
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
        /** @var Domaine $domaine */
        $domaineId = $this->params()->fromRoute('domaine');
        $domaine = $this->getRessourceRhService()->getDomaine($domaineId);

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
                $this->getRessourceRhService()->updateDomaine($domaine);
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
        /** @var Domaine $domaine */
        $domaineId = $this->params()->fromRoute('domaine');
        $domaine = $this->getRessourceRhService()->getDomaine($domaineId);

        if ($domaine !== null) {
            $this->getRessourceRhService()->deleteDomaine($domaine);
        }

        return $this->redirect()->toRoute('ressource-rh/index-metier-fmaille-domaine', [], [], true);
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
        $metiers = $this->getRessourceRhService()->getCartographie();
        return new ViewModel([
            'metiers' => $metiers,
        ]);
    }

}