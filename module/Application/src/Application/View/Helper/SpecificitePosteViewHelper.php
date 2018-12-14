<?php

namespace Application\View\Helper;

use Application\Entity\Db\SpecificitePoste;
use Zend\View\Helper\AbstractHelper;

class SpecificitePosteViewHelper extends AbstractHelper
{

    /** @var SpecificitePoste */
    protected $specificite;

    /**
     * @param SpecificitePoste $specificite
     * @return $this
     */
    public function __invoke($specificite = null)
    {
        $this->specificite = $specificite;
        return $this;
    }

    public function __call($name, $arguments)
    {
        $attr = call_user_func_array([$this->specificite, $name], $arguments);
        return $this;
    }

    /**
     * @param  SpecificitePoste $specificite
     * @return string
     */
    public function render($specificite)
    {
        $texte = "";
        if ($specificite->getSpecificite()) {
            $texte .= '<h3> Spécificité du poste </h3>';
            $texte .= $specificite->getSpecificite();
        }
        if ($specificite->getEncadrement()) {
            $texte .= '<h3> Encadrement </h3>';
            $texte .= $specificite->getEncadrement();
        }
        if ($specificite->getRelationsInternes()) {
            $texte .= '<h3> Relations internes à l\'unicaen </h3>';
            $texte .= $specificite->getRelationsInternes();
        }
        if ($specificite->getRelationsExternes()) {
            $texte .= '<h3> Relations externes à l\'unicaen </h3>';
            $texte .= $specificite->getRelationsExternes();
        }
        if ($specificite->getContraintes()) {
            $texte .= '<h3> Contraintes particulières d\'exercice </h3>';
            $texte .= $specificite->getContraintes();
        }
        if ($specificite->getMoyens()) {
            $texte .= '<h3> Moyens et outils mis à disposition </h3>';
            $texte .= $specificite->getMoyens();
        }
        return $texte;
    }
}