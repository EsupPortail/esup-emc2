<?php

namespace Autoform\Controller;

use Autoform\Entity\Db\Validation;
use Autoform\Service\Exporter\Validation\ValidationExporter;
use Autoform\Service\Formulaire\FormulaireInstanceServiceAwareTrait;
use Autoform\Service\Formulaire\FormulaireReponseServiceAwareTrait;
use Autoform\Service\Validation\ValidationReponseServiceAwareTrait;
use Autoform\Service\Validation\ValidationServiceAwareTrait;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ValidationController extends AbstractActionController {
    use FormulaireInstanceServiceAwareTrait;
    use FormulaireReponseServiceAwareTrait;
    use ValidationServiceAwareTrait;
    use ValidationReponseServiceAwareTrait;

    public $renderer;

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

        $validation = $this->getValidationService()->getValidationByTypeAndInstance($type, $instance);
        if ($validation === null) {
            $validation = new Validation();
            $validation->setFormulaireInstance($instance);
            $validation->setType($type);
            $this->getValidationService()->create($validation);
        }

        return $this->redirect()->toRoute('autoform/validation/afficher-validation', ['validation' => $validation->getId()], [], true);
    }

    public function afficherValidationAction()
    {
        $type     = $this->params()->fromRoute('type');
        $instance = $this->getFormulaireInstanceService()->getRequestedFormulaireInstance($this, 'instance');
        $retour = $this->params()->fromQuery('retour');

        $validation = $this->getValidationService()->getValidationByTypeAndInstance($type, $instance);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $this->getValidationReponseService()->updateValidationReponse($validation,$data);
            $this->getValidationService()->update($validation);
            if ($retour) {
                return $this->redirect()->toUrl($retour);
            } else {
                return $this->redirect()->toRoute('autoform/validation/afficher-validation', ['instance' => $instance->getId(), 'type' => $type], [], true);
            }
        }

        $formulaire = $instance->getFormulaire();
        $freponses = $this->getFormulaireReponseService()->getFormulaireResponsesByFormulaireInstance($instance);
        $vreponses = ($validation)?$this->getValidationReponseService()->getValidationResponsesByValidation($validation):[];
        $url = '';

        return new ViewModel([
            'formulaire'    => $formulaire,
            'instance'      => $instance,
            'fReponses'      => $freponses,
            'vReponses'      => $vreponses,
            'url'           => $url,
        ]);
    }

    public function afficherResultatAction()
    {
        $validation = $this->getValidationService()->getRequestedValidation($this, 'validation');
        $instance = $validation->getFormulaireInstance();
        $formulaire = $instance->getFormulaire();

        $fReponses = $this->getFormulaireReponseService()->getFormulaireResponsesByFormulaireInstance($instance);
        $vReponses = $this->getValidationReponseService()->getValidationResponsesByValidation($validation);


        return new ViewModel([
            'title' => 'Validation',
            'formulaire' => $formulaire,
            'validation' => $validation,
            'fReponses' => $fReponses,
            'vReponses' => $vReponses,
        ]);
    }

    public function exportPdfAction()
    {
        $validation = $this->getValidationService()->getRequestedValidation($this, 'validation');
        $instance = $validation->getFormulaireInstance();
        $formulaire = $instance->getFormulaire();

        $fReponses = $this->getFormulaireReponseService()->getFormulaireResponsesByFormulaireInstance($instance);
        $vReponses = $this->getValidationReponseService()->getValidationResponsesByValidation($validation);

        $exporter = new ValidationExporter($this->renderer, 'A4');
        $exporter->setVars([
            'formulaire' => $formulaire,
            'fReponses' => $fReponses,
            'vReponses' => $vReponses,
        ]);
        $exporter->export('export.pdf');
        exit;
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