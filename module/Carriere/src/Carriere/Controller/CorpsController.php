<?php

namespace Carriere\Controller;

use Application\Form\ModifierNiveau\ModifierNiveauFormAwareTrait;
use Application\Service\Niveau\NiveauServiceAwareTrait;
use Carriere\Service\Categorie\CategorieServiceAwareTrait;
use Carriere\Service\Corps\CorpsServiceAwareTrait;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class CorpsController extends AbstractActionController {
    use CategorieServiceAwareTrait;
    use CorpsServiceAwareTrait;
    use NiveauServiceAwareTrait;
    use ModifierNiveauFormAwareTrait;

    public function indexAction() : ViewModel
    {
        $corps = $this->getCorpsService()->getCorps();

        return new ViewModel([
            "corps" => $corps,
        ]);
    }

    public function modifierNiveauAction() : ViewModel
    {
        $corps = $this->getCorpsService()->getRequestedCorps($this);

        $form = $this->getModifierNiveauForm();
        $form->setAttribute('action', $this->url()->fromRoute('corps/modifier-niveau', ['corps' => $corps->getId()], ['fragment' => 'corps'], true));
        $form->bind($corps);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getCorpsService()->update($corps);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Modification du niveau du corps ".$corps->getLibelleLong(),
            'form' => $form,
        ]);
        return $vm;
    }

    public function afficherAgentsAction() : ViewModel
    {
        $corps = $this->getCorpsService()->getRequestedCorps($this);

        return new ViewModel([
            'title' => 'Agents ayant le corps ['. $corps->getLibelleCourt().']',
            'corps' => $corps,
        ]);
    }
}