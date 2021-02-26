<?php

namespace UnicaenParametre\Controller;

use UnicaenParametre\Form\Categorie\CategorieFormAwareTrait;
use UnicaenParametre\Service\Categorie\CategorieServiceAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class CategorieController extends AbstractActionController {
    use CategorieServiceAwareTrait;
    use CategorieFormAwareTrait;

    public function indexAction()
    {
        $categories = $this->getCategorieService()->getCategories();
        $selection = $this->getCategorieService()->getRequestedCategorie($this);

        return new ViewModel([
            'categories' => $categories,
            'selection' => $selection,
        ]);
    }
}