<?php

namespace Application\View\Helper;

use Laminas\View\Helper\AbstractHelper;
use Laminas\View\Helper\Partial;
use Laminas\View\Renderer\PhpRenderer;
use Laminas\View\Resolver\TemplatePathStack;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenValidation\Entity\Db\ValidationInstance;


//TODO à envoyer dans unicaen/utilisateur
class HistoriqueBlocViewHelper extends AbstractHelper
{
    /** OPTIONS :
     * 'date-format' : format de la date (default: "d/m/Y H:i")
     * 'genre' : genre de l'objet (default: "N")
     *  - valeurs possibles M masculin, F Féminin, N Neutre
     * 'display-utilisateur' : affichage de la dénomination (default:true)
     */
    public function __invoke(HistoriqueAwareInterface $object, array $options = []): string|Partial
    {
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('historique-bloc', ['object' => $object, 'options' => $options]);
    }
}