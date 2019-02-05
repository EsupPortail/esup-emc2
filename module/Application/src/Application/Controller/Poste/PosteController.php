<?php

namespace Application\Controller\Poste;

use Application\Entity\Db\Poste;
use Application\Form\Poste\PosteForm;
use Application\Service\Poste\PosteServiceAwareTrait;
use Octopus\Entity\Db\ImmobilierBatiment;
use Octopus\Service\Immobilier\ImmobilierServiceAwareTrait;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class PosteController extends AbstractActionController {
    use PosteServiceAwareTrait;
    use ImmobilierServiceAwareTrait;

    public function indexAction()
    {
        $postes = $this->getPosteService()->getPostes();
        
        return new ViewModel([
            'postes' => $postes,
            'immobilierService' => $this->getImmobiliserService(),
        ]);
    }

    public function afficherAction()
    {
        /** @var Poste $poste */
        $posteId = $this->params()->fromRoute('poste');
        $poste = $this->getPosteService()->getPoste($posteId);

        return new ViewModel([
            'title' => 'Affichage du poste',
            'poste' => $poste,
        ]);
    }

    public function ajouterAction()
    {
        /** @var Poste $poste */
        $poste = new Poste();

        /** @var PosteForm $form */
        $form = $this->getServiceLocator()->get('FormElementManager')->get(PosteForm::class);
        $form->setAttribute('action', $this->url()->fromRoute('poste/ajouter', [], [], true));
        $form->bind($poste);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getPosteService()->create($poste);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Ajouter un poste",
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierAction()
    {
        /** @var Poste $poste */
        $posteId = $this->params()->fromRoute('poste');
        $poste = $this->getPosteService()->getPoste($posteId);

        /** @var PosteForm $form */
        $form = $this->getServiceLocator()->get('FormElementManager')->get(PosteForm::class);
        $form->setAttribute('action', $this->url()->fromRoute('poste/modifier', ['poste' => $poste->getId()], [], true));
        $form->bind($poste);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getPosteService()->create($poste);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Modifier un poste",
            'form' => $form,
        ]);
        return $vm;
    }

    public function supprimerAction()
    {
        $posteId = $this->params()->fromRoute('poste');
        $poste = $this->getPosteService()->getPoste($posteId);

        if ($poste) {
            $this->getPosteService()->delete($poste);
        }

        $this->redirect()->toRoute('poste', [], [], true);
    }

    /**
     * @return JsonModel
     */
    public function rechercherBatimentAction() {
        if (($term = $this->params()->fromQuery('term'))) {
            $batiments = $this->getImmobiliserService()->getImmobilierBatimentsByTerm($term);
            $result = [];
            /** @var ImmobilierBatiment[] $batiments */
            foreach ($batiments as $batiment) {
                $result[] = array(
                    'id'    => $batiment->getId(),
                    'label' => $batiment->getLibelle(),
                    'extra' => "<span class='badge' style='background-color: slategray;'>".$batiment->getSite()->getLibelle()."</span>",
                );
            }
            usort($result, function($a, $b) {
                return strcmp($a['label'], $b['label']);
            });

            return new JsonModel($result);
        }
        exit;
    }
}