<?php

namespace UnicaenContact\View\Helper;

use Laminas\View\Helper\AbstractHelper;
use Laminas\View\Helper\Partial;
use Laminas\View\Renderer\PhpRenderer;
use Laminas\View\Resolver\TemplatePathStack;
use UnicaenContact\Entity\Db\Contact;


/**
 * OPTIONS :
 * "display-type" (default false) : affiche le type en-tête
 * "display-action" (default false) : affiche les actions
 * "retour" (default null) : url de retour post action (historiser/restaurer)
 */
class ContactViewHelper extends AbstractHelper
{
    public function __invoke(Contact $contact, array $options = []): string|Partial
    {
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('contact', ['contact' => $contact, 'options' => $options]);
    }
}