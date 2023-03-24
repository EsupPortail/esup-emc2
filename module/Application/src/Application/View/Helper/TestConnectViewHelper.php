<?php

namespace Application\View\Helper;

use Laminas\View\Renderer\PhpRenderer;
use UnicaenAuthentification\View\Helper\AbstractConnectViewHelper;

/**
 * Aide de vue dessinant le bouton de connexion via Shibboleth,
 * si l'authentification Shibboleth est activée.
 *
 * @method PhpRenderer getView()
 * @author Unicaen
 */
class TestConnectViewHelper extends AbstractConnectViewHelper
{
    const TYPE = 'test';
    const TITLE = "Mon onglet de test";

    public function __construct()
    {
        $this->setType(self::TYPE);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return "test";
    }
}