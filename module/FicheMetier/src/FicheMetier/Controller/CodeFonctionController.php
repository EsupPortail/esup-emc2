<?php

namespace FicheMetier\Controller;

use FicheMetier\Form\CodeFonction\CodeFonctionFormAwareTrait;
use FicheMetier\Service\CodeFonction\CodeFonctionServiceAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class CodeFonctionController extends AbstractActionController
{
    use CodeFonctionServiceAwareTrait;
    use CodeFonctionFormAwareTrait;

    public function indexAction(): ViewModel
    {
        $codesFonctions = $this->getCodeFonctionService()->getCodesFonctions();

        return new ViewModel([
            'codesFonctions' => $codesFonctions
        ]);
    }

}
