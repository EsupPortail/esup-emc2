<?php

namespace Application\View\Helper;

use Application\View\Renderer\PhpRenderer;
use Laminas\View\Helper\AbstractHelper;
use Laminas\View\Helper\Partial;
use Laminas\View\Resolver\TemplatePathStack;

/**
 * la classe ActionIconViewHelper permet d'afficher une icone cliquable (comme les icones de la colonne 'Action' des
 * tableaux listant des entités).
 * Pour invoquer l'aide de vue il faut faire "echo $this->actionIcon($options)" avec $options un tableau contenant les
 * clefs correspondant options de l'ActionIcon (par exemple url, titre, ...
 *
 * N.B.: l'alias de l'aide de vue est dans Application/config/modudule.config.php:97
 */
class ActionIconViewHelper extends AbstractHelper
{
    /**
     * @param array $options with the following
     * $options['icone']      : the icon to be displayed (mandatory)
     * $options['url']        : the url of the associated action (mandatory)
     * $options['titre']      : the title displayed when the icon is hovered (default: '')
     * $options['isAllowed']  : a boolean to control the right to use this action (default: true)
     * $options['displayOff'] : a boolean to control the 'ghost' version of the icon when not allowed (default: true)
     * $options['class']      : a set of class to decorated the ActionIcon (default: '')
     * $options['event']      : an event name feed to the data-event attribute (default: '')
     *
     * @return string|Partial
     */
    public function __invoke($options = [])
    {
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('action-icon', ['options' => $options]);
    }
}