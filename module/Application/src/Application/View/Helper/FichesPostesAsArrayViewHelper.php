<?php

namespace Application\View\Helper;

use Application\Provider\Role\RoleProvider as AppRoleProvider;
use Application\Entity\Db\FichePoste;

use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;
use Laminas\View\Helper\AbstractHelper;
use Laminas\View\Helper\Partial;
use Laminas\View\Renderer\PhpRenderer;
use Laminas\View\Resolver\TemplatePathStack;

//TODO parametrer ALLOWED

class FichesPostesAsArrayViewHelper extends AbstractHelper
{
    use UserServiceAwareTrait;

    /**
     * @param FichePoste[] $fiches
     * @param array $options
     * @return string|Partial
     */
    public function __invoke($fiches, $structure = null, array $options = [])
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
            'isObservateur' => ($this->getUserService()->getConnectedRole()->getRoleId() === AppRoleProvider::OBSERVATEUR),
        ];
        if (isset($options['displays']) AND isset($options['displays']['id'])) $displays['id'] = ($options['displays']['id'] === true);
        if (isset($options['displays']) AND isset($options['displays']['agent'])) $displays['agent'] = ($options['displays']['agent'] !== false);
        if (isset($options['displays']) AND isset($options['displays']['structure'])) $displays['structure'] = ($options['displays']['structure'] !== false);
        if (isset($options['displays']) AND isset($options['displays']['etat'])) $displays['etat'] = ($options['displays']['etat'] !== false);
        if (isset($options['displays']) AND isset($options['displays']['validite'])) $displays['validite'] = ($options['displays']['validite'] !== false);

        return $view->partial('fiches-postes-as-table', ['fiches' => $fiches, 'structure' => $structure, 'displays' => $displays, 'options' => $options]);
    }
}