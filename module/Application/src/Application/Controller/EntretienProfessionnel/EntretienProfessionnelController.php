<?php

namespace Application\Controller\EntretienProfessionnel;

use Application\Entity\Db\EntretienProfessionnel;
use Application\Form\EntretienProfessionnel\EntretienProfessionnelFormAwareTrait;
use Application\Service\EntretienProfessionnel\EntretienProfessionnelServiceAwareTrait;
use Autoform\Entity\Db\FormulaireInstance;
use Autoform\Service\Formulaire\FormulaireInstanceServiceAwareTrait;
use Autoform\Service\Formulaire\FormulaireServiceAwareTrait;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class EntretienProfessionnelController extends AbstractActionController {
    use EntretienProfessionnelFormAwareTrait;
    use EntretienProfessionnelServiceAwareTrait;
    use FormulaireServiceAwareTrait;
    use FormulaireInstanceServiceAwareTrait;

    public function indexAction()
    {
        $entretiens = $this->getEntretienProfessionnelService()->getEntretiensProfessionnels();

        return new ViewModel([
            'entretiens' => $entretiens,
        ]);
    }

    public function creerAction()
    {
        $entretien = new EntretienProfessionnel();
        $form = $this->getEntretienProfessionnelForm();
        $form->setAttribute('action', $this->url()->fromRoute('entretien-professionnel/creer', [], [], true));
        $form->bind($entretien);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                /** Creation de l'instance de formulaire **/
                $instance = new FormulaireInstance();
                $formulaire = $this->getFormulaireService()->getFormulaire(1);
                $instance->setFormulaire($formulaire);
                $this->getFormulaireInstanceService()->create($instance);
                $entretien->setFormulaireInstance($instance);
                $this->getEntretienProfessionnelService()->create($entretien);
                $this->redirect()->toRoute('entretien-professionnel/modifier', ['entretien' => $entretien->getId()], [], true);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Création d\'un nouvel entretien professionnel',
            'form'  => $form,
        ]);
        return $vm;
    }

    public function modifierAction()
    {
        $entretien = $this->getEntretienProfessionnelService()->getRequestedEntretienProfessionnel($this, 'entretien');

        return new ViewModel([
            'entretien' => $entretien,
        ]);
    }

    public function historiserAction()
    {
        $entretien = $this->getEntretienProfessionnelService()->getRequestedEntretienProfessionnel($this, 'entretien');
        $this->getEntretienProfessionnelService()->historise($entretien);
        $this->redirect()->toRoute('entretien-professionnel', [], [], true);
    }

    public function restaurerAction()
    {
        $entretien = $this->getEntretienProfessionnelService()->getRequestedEntretienProfessionnel($this, 'entretien');
        $this->getEntretienProfessionnelService()->restore($entretien);
        $this->redirect()->toRoute('entretien-professionnel', [], [], true);
    }

    public function detruireAction()
    {
        $entretien = $this->getEntretienProfessionnelService()->getRequestedEntretienProfessionnel($this, 'entretien');
        $this->getEntretienProfessionnelService()->delete($entretien);
        $this->redirect()->toRoute('entretien-professionnel', [], [], true);
    }


}