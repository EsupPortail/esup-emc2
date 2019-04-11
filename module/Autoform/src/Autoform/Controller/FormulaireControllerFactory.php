<?php

namespace Autoform\Controller;

use Autoform\Form\Categorie\CategorieForm;
use Autoform\Form\Champ\ChampForm;
use Autoform\Form\Formulaire\FormulaireForm;
use Autoform\Service\Categorie\CategorieService;
use Autoform\Service\Champ\ChampService;
use Autoform\Service\Formulaire\FormulaireInstanceService;
use Autoform\Service\Formulaire\FormulaireReponseService;
use Autoform\Service\Formulaire\FormulaireService;
use Zend\Mvc\Controller\ControllerManager;


class FormulaireControllerFactory {

    public function __invoke(ControllerManager $manager)
    {
        /**
         * @var CategorieService $categorieService
         * @var ChampService $champService
         * @var FormulaireService $formulaireService
         * @var FormulaireReponseService $formulaireReponseService
         * @var FormulaireInstanceService $formulaireInstanceService
         */
        $categorieService           = $manager->getServiceLocator()->get(CategorieService::class);
        $champService               = $manager->getServiceLocator()->get(ChampService::class);
        $formulaireService          = $manager->getServiceLocator()->get(FormulaireService::class);
        $formulaireReponseService   = $manager->getServiceLocator()->get(FormulaireReponseService::class);
        $formulaireInstanceService  = $manager->getServiceLocator()->get(FormulaireInstanceService::class);

        /**
         * @var CategorieForm $categorieForm
         * @var ChampForm $champForm
         * @var FormulaireForm $formulaireForm
         */
        $categorieForm          = $manager->getServiceLocator()->get('FormElementManager')->get(CategorieForm::class);
        $champForm              = $manager->getServiceLocator()->get('FormElementManager')->get(ChampForm::class);
        $formulaireForm         = $manager->getServiceLocator()->get('FormElementManager')->get(FormulaireForm::class);

        /** @var FormulaireController $controller */
        $controller = new FormulaireController();
        $controller->setCategorieService($categorieService);
        $controller->setChampService($champService);
        $controller->setFormulaireService($formulaireService);
        $controller->setFormulaireReponseService($formulaireReponseService);
        $controller->setFormulaireInstanceService($formulaireInstanceService);
        $controller->setCategorieForm($categorieForm);
        $controller->setChampForm($champForm);
        $controller->setFormulaireForm($formulaireForm);
        return $controller;
    }
}