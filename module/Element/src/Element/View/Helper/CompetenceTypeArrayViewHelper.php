<?php

namespace Element\View\Helper;

use Element\Entity\Db\CompetenceElement;
use Element\Entity\Db\CompetenceType;
use Element\Entity\Db\Interfaces\HasApplicationCollectionInterface;
use Laminas\View\Helper\AbstractHelper;
use Laminas\View\Helper\Partial;
use Laminas\View\Resolver\TemplatePathStack;

class CompetenceTypeArrayViewHelper extends AbstractHelper
{
    /**
     * @param CompetenceElement[] $competences
     * @param HasApplicationCollectionInterface|null $objet
     * @param string $mode
     * @param array $options
     * @return string|Partial
     *
     * Note. Liste des options :
     * - titre qui conditionne l'affichage d'une section
     */
    public function __invoke(array $competences, CompetenceType $type, ?HasApplicationCollectionInterface $objet = null, string $mode = "affichage", array $options = []): string|Partial
    {
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('competence-type-array', ['competences' => $competences, 'type' => $type, 'object' => $objet, 'mode' => $mode, 'options' => $options]);
    }
}