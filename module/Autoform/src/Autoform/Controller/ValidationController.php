<?php

namespace Autoform\Controller;

use Autoform\Entity\Db\Validation;
use Autoform\Service\Formulaire\FormulaireInstanceServiceAwareTrait;
use Autoform\Service\Formulaire\FormulaireReponseServiceAwareTrait;
use Autoform\Service\Formulaire\FormulaireServiceAwareTrait;
use Autoform\Service\Validation\ValidationReponseServiceAwareTrait;
use Autoform\Service\Validation\ValidationServiceAwareTrait;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ValidationController extends AbstractActionController {
    use FormulaireServiceAwareTrait;
    use FormulaireInstanceServiceAwareTrait;
    use FormulaireReponseServiceAwareTrait;
    use ValidationServiceAwareTrait;
    use ValidationReponseServiceAwareTrait;

    public function indexAction()
    {
        $validations = $this->getValidationService()->getValidations();

        return new ViewModel([
            'validations' => $validations,
        ]);
    }

    public function creerAction()
    {
        $instance = $this->getFormulaireInstanceService()->getRequestedFormulaireInstance($this, 'instance');
        $type = $this->params()->fromRoute('type');

        $validation = new Validation();
        $validation->setFormulaireInstance($instance);
        $validation->setType($type);

        $this->getValidationService()->create($validation);
        return $this->redirect()->toRoute('autoform/validation/afficher-validation', ['validation' => $validation->getId(), 'instance' => $instance->getId()], [], true);
    }

    public function afficherValidationAction()
    {
        //todo inserer cela dans la table validation ?
        $__FORMULAIRE_ID__ = 1; // ENTRETIEN PRO

        $instance = $this->getFormulaireInstanceService()->getRequestedFormulaireInstance($this, 'instance');
        $formulaire = $this->getFormulaireService()->getFormulaire($__FORMULAIRE_ID__);
        $validation = $this->getValidationService()->getRequestedValidation($this, 'validation');
        $fReponses = $this->getFormulaireReponseService()->getFormulaireResponsesByFormulaireInstance($instance);
        $vReponses = $this->getValidationReponseService()->getValidationResponsesByValidation($validation);


        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();

            $this->getValidationReponseService()->updateValidationReponse($instance, $validation, $data);
            return $this->redirect()->toRoute('autoform/validation/afficher-validation', ['validation' => $validation->getId(), 'instance' => $instance->getId()], [], true);
        }

        return new ViewModel([
            'instance'      => $instance,
            'formulaire'    => $formulaire,
            'validation'    => $validation,
            'fReponses'     => $fReponses,
            'vReponses'     => $vReponses,
        ]);
    }

    public function afficherResultatAction()
    {
        //todo inserer cela dans la table validation ?
        $__FORMULAIRE_ID__ = 1;

        $instance = $this->getFormulaireInstanceService()->getRequestedFormulaireInstance($this, 'instance');
        $formulaire = $this->getFormulaireService()->getFormulaire($__FORMULAIRE_ID__);
        $validation = $this->getValidationService()->getRequestedValidation($this, 'validation');
        $fReponses = $this->getFormulaireReponseService()->getFormulaireResponsesByFormulaireInstance($instance);
        $vReponses = $this->getValidationReponseService()->getValidationResponsesByValidation($validation);


        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();

            $this->getValidationReponseService()->updateValidationReponse($instance, $validation, $data);
            return $this->redirect()->toRoute('autoform/validation/afficher-validation', ['validation' => $validation->getId(), 'instance' => $instance->getId()], [], true);
        }

        return new ViewModel([
            'title' => 'Validation <strong>'.$validation->getType().'</strong> de la demande de ',
            '$instance' => $instance,
            'formulaire' => $formulaire,
            'validation' => $validation,
            'fReponses' => $fReponses,
            'vReponses' => $vReponses,
        ]);
    }

    public function historiserAction()
    {
        $validation = $this->getValidationService()->getRequestedValidation($this, 'validation');
        $this->getValidationService()->historise($validation);
        return $this->redirect()->toRoute('autoform/validations', [], [], true);
    }

    public function restaurerAction()
    {
        $validation = $this->getValidationService()->getRequestedValidation($this, 'validation');
        $this->getValidationService()->restaure($validation);
        return $this->redirect()->toRoute('autoform/validations', [], [], true);
    }

    public function detruireAction()
    {
        $validation = $this->getValidationService()->getRequestedValidation($this, 'validation');
        $this->getValidationService()->delete($validation);
        return $this->redirect()->toRoute('autoform/validations', [], [], true);
    }
}