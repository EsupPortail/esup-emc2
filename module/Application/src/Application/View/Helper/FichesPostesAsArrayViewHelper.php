<?php

namespace Application\View\Helper;

use Application\Constant\RoleConstant;
use Application\Entity\Db\FichePoste;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Helper\Partial;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Resolver\TemplatePathStack;

//TODO parametrer ALLOWED

class FichesPostesAsArrayViewHelper extends AbstractHelper
{
    use UserServiceAwareTrait;

    /**
     * @param FichePoste[] $fiches
     * @param array $options
     * @return string|Partial
     */
    public function __invoke($fiches, $structure = null, $options = [])
    {
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        $displays = [
            'id' => false,
            'agent' => true,
            'structure' => true,
            'poste' => false,
            'etat' => true,
            'validite' => true,
            'fiche-principale' => true,
            'en-cours' => true,
            'modification' => false,
            'action' => true,
            'isObservateur' => ($this->getUserService()->getConnectedRole()->getRoleId() === RoleConstant::OBSERVATEUR),
        ];
        if (isset($options['displays']) AND isset($options['displays']['id'])) $displays['id'] = ($options['displays']['id'] === true);
        if (isset($options['displays']) AND isset($options['displays']['agent'])) $displays['agent'] = ($options['displays']['agent'] !== false);
        if (isset($options['displays']) AND isset($options['displays']['structure'])) $displays['structure'] = ($options['displays']['structure'] !== false);
        if (isset($options['displays']) AND isset($options['displays']['etat'])) $displays['etat'] = ($options['displays']['etat'] !== false);
        if (isset($options['displays']) AND isset($options['displays']['validite'])) $displays['validite'] = ($options['displays']['validite'] !== false);

        return $view->partial('fiches-postes-as-table', ['fiches' => $fiches, 'structure' => $structure, 'displays' => $displays, 'options' => $options]);
    }
}