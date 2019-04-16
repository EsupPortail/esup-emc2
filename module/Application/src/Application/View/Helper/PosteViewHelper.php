<?php

namespace Application\View\Helper;

use Application\Entity\Db\Poste;
use Octopus\Service\Immobilier\ImmobilierServiceAwareTrait;
use Zend\View\Helper\AbstractHelper;

class PosteViewHelper extends AbstractHelper
{
    use ImmobilierServiceAwareTrait;

    /** @var Poste */
    protected $poste;

    /**
     * @param Poste $poste
     * @return $this
     */
    public function __invoke($poste = null)
    {
        $this->poste = $poste;
        return $this;
    }

    public function __call($name, $arguments)
    {
        $attr = call_user_func_array([$this->poste, $name], $arguments);
        return $this;
    }

    /**
     * @param  Poste $poste
     * @return string
     */
    public function render($poste)
    {
        $texte = '';
        $texte .= '<dl class="dl-horizontal">';
        $texte .= '<dt> Numéro poste national </dt>';
        $texte .= '<dd class="siham">'.$poste->getNumeroPoste().'</dd>';
        $texte .= '<dt> Affectation du poste </dt>';
        $texte .= '<dd class="siham">'.$poste->getStructure().'</dd>';
        $texte .= '<dt> Localisation du poste </dt>';
//        $texte .= '<dd class="siham">'.$poste->getLocalisation().'</dd>';
        $texte .= '<dd class="siham">'.$this->getImmobiliserService()->getImmobilierBatiment($poste->getLocalisation()).'</dd>';
        $texte .= '<dt> Rattachement hiérarchique </dt>';
        $texte .= '<dd class="siham">'.(($poste->getRattachementHierarchique())?$poste->getRattachementHierarchique()->getDenomination():"---").'</dd>';
        $texte .= '<dt> Catégorie </dt>';
        $texte .= '<dd class="siham">'.$poste->getCorrespondance().'</dd>';
        $texte .= '<dt> Domaine UNICAEN </dt>';
        $texte .= '<dd class="gpeec">'.(($poste->getDomaine())?$poste->getDomaine()->getLibelle():"---").'</dd>';
        $texte .= '<dt> Fonction </dt>';

        $texte .= '<dd class="gpeec">'.($poste->getFonction())?:"---".'</dd>';
        if ($poste->getLien()) {
            $texte .= '<dt> Lien externe </dt>';
            $texte .= '<dd class="gpeec"><a href="'.$poste->getLien().'">'.$poste->getLien().'</a></dd>';
        }
        $texte .= '</dl>';

        return $texte;
    }
}