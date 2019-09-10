<?php

namespace Autoform\Controller;

use Autoform\Form\Categorie\CategorieForm;
use Autoform\Form\Champ\ChampForm;
use Autoform\Form\Formulaire\FormulaireForm;
use Autoform\Service\Categorie\CategorieService;
use Autoform\Service\Champ\ChampService;
use Autoform\Service\Formulaire\FormulaireInstanceService;
use Autoform\Service\Formulaire\FormulaireService;
use Autoform\Service\Formulaire\FormulaireReponseService;
use Interop\Container\ContainerInterface;

class FormulaireControllerFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var CategorieService $categorieService
         * @var ChampService $champService
         * @var FormulaireService $formulaireService
         * @var FormulaireReponseService $formulaireReponseService
         * @var FormulaireInstanceService $formulaireInstanceService
         */
        $categorieService           = $container->get(CategorieService::class);
        $champService               = $container->get(ChampService::class);
        $formulaireService          = $container->get(FormulaireService::class);
        $formulaireReponseService   = $container->get(FormulaireReponseService::class);
        $formulaireInstanceService   = $container->get(FormulaireInstanceService::class);

        /**
         * @var CategorieForm $categorieForm
         * @var ChampForm $champForm
         * @var FormulaireForm $formulaireForm
         */
        $categorieForm          = $container->get('FormElementManager')->get(CategorieForm::class);
        $champForm              = $container->get('FormElementManager')->get(ChampForm::class);
        $formulaireForm         = $container->get('FormElementManager')->get(FormulaireForm::class);

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