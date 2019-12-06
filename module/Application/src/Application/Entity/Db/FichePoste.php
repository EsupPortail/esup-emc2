<?php

namespace Application\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;
use UnicaenApp\Entity\HistoriqueAwareTrait;
use UnicaenApp\Exception\RuntimeException;

class FichePoste
{
    use HistoriqueAwareTrait;

    /** @var int */
    private $id;
    /** @var string */
    private $libelle;
    /** @var Agent */
    private $agent;

    /** @var SpecificitePoste */
    private $specificite;
    /** @var Poste */
    private $poste;

    /** @var ArrayCollection */
    private $fichesMetiers;
    /** @var ArrayCollection (FicheposteApplicationRetiree) */
    private $applicationsRetirees;
    /** @var ArrayCollection (FicheposteCompetenceConservee) */
    private $competencesConservees;
    /** @var ArrayCollection (FicheposteFormationConservee) */
    private $formationsConservees;

    public function __invoke()
    {
        $this->fichesMetiers = new ArrayCollection();
        $this->applicationsRetirees = new ArrayCollection();
        $this->competencesConservees = new ArrayCollection();
        $this->formationsConservees = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * @param string $libelle
     * @return FichePoste
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
        return $this;
    }

    /**
     * @return Agent
     */
    public function getAgent()
    {
        return $this->agent;
    }

    /**
     * @param Agent $agent
     * @return FichePoste
     */
    public function setAgent($agent)
    {
        $this->agent = $agent;
        return $this;
    }

    /**
     * @return SpecificitePoste
     */
    public function getSpecificite()
    {
        return $this->specificite;
    }

    /**
     * @param SpecificitePoste $specificite
     * @return FichePoste
     */
    public function setSpecificite($specificite)
    {
        $this->specificite = $specificite;
        return $this;
    }

    /**
     * @return Poste
     */
    public function getPoste()
    {
        return $this->poste;
    }

    /**
     * @param Poste $poste
     * @return FichePoste
     */
    public function setPoste($poste)
    {
        $this->poste = $poste;
        return $this;
    }

    /**
     * @return FicheTypeExterne[]
     */
    public function getFichesMetiers()
    {
        return $this->fichesMetiers->toArray();
    }

    /**
     * @var FicheTypeExterne $type
     * @return FichePoste
     */
    public function addFicheTypeExterne($type)
    {
        $this->fichesMetiers->add($type);
        return $this;
    }

    /**
     * @var FicheTypeExterne $type
     * @return FichePoste
     */
    public function removeFicheTypeExterne($type)
    {
        $this->fichesMetiers->removeElement($type);
        return $this;
    }

    /**
     * @return FicheTypeExterne
     */
    public function getFicheTypeExternePrincipale() {
        $res = [];
        /** @var FicheTypeExterne $ficheMetier */
        foreach ($this->fichesMetiers as $ficheMetier) {
            if ($ficheMetier->getPrincipale()) {
                $res[] = $ficheMetier;
            }
        }

        $nb = count($res);
        if ( $nb > 1) {
            throw new RuntimeException("Plusieurs fiches metiers sont déclarées comme principale");
        }
        if ($nb === 1) return current($res);
        return null;
    }

    public function getQuotiteTravaillee()
    {
        $somme = 0;
        /** @var FicheTypeExterne $ficheMetier */
        foreach ($this->fichesMetiers as $ficheMetier) {
            $somme += $ficheMetier->getQuotite();
        }
        return $somme;
    }

    /** Competences Conservées ****************************************************************************************/

    /** @return ArrayCollection */
    public function getCompetencesConservees() {
        return $this->competencesConservees;
    }

    /** @param FicheposteCompetenceConservee $competence */
    public function addCompetenceConservee(FicheposteCompetenceConservee $competence) {
        $this->competencesConservees->add($competence);
    }

    /** @param FicheposteCompetenceConservee $competence */
    public function removeCompetenceConservee(FicheposteCompetenceConservee $competence) {
        $this->competencesConservees->removeElement($competence);
    }

    public function clearCompetencesConservees() {
        $this->competencesConservees->clear();
    }

    /** Formations Conservées ****************************************************************************************/

    /** @return ArrayCollection */
    public function getFormationsConservees() {
        return $this->formationsConservees;
    }

    /** @param FicheposteFormationConservee $formation */
    public function addFormationConservee(FicheposteFormationConservee $formation) {
        $this->formationsConservees->add($formation);
    }

    /** @param FicheposteFormationConservee $formation */
    public function removeFormationConservee(FicheposteFormationConservee $formation) {
        $this->formationsConservees->removeElement($formation);
    }

    public function clearFormationsConservees() {
        $this->formationsConservees->clear();
    }

    /** Applications Conservées ***************************************************************************************/

    /** @return ArrayCollection */
    public function getApplicationsRetirees() {
        return $this->applicationsRetirees;
    }

    /** @param FicheposteApplicationRetiree $application */
    public function addApplicationRetiree(FicheposteApplicationRetiree $application) {
        $this->applicationsRetirees->add($application);
    }

    /** @param FicheposteApplicationRetiree $application */
    public function removeApplicationRetiree(FicheposteApplicationRetiree $application) {
        $this->applicationsRetirees->removeElement($application);
    }

    public function clearApplicationsRetirees() {
        $this->applicationsRetirees->clear();
    }


    /**
     * //TODO finir cela
     * [
     *      [
     *          'famille' => 'Informatique,
     *          [
     *              'id' => 123,
     *              'metier' => 'Ingenieur en logiciel',
     *              'quotite' => '50',
     *              'principal' => true,
     *          ],
     *          [
     *              'id' => 43,
     *              'metier' => 'Gestionnaire de base de données',
     *              'quotite' => '20',
     *          ],
     *      ],
     *      [
     *          'famille' => 'Assistance technique et administration de la recherche ,
     *          [
     *              'id' => 665,
     *              'metier' => 'Responsable assistance support',
     *              'quotite' => '20',
     *          ],
     *      ],
     * ]
     *
     * @return array
     */
    public function getFamillesAsJson() {
        $result = [];
        foreach ($this->getFichesMetiers() as $fiche) {
            $metier  = $fiche->getFicheType()->getMetier()->getLibelle();
            $famille = $fiche->getFicheType()->getMetier()->getFonction()->getDomaine()->getFamille()->getLibelle();
            $quotite = $fiche->getQuotite();

            if (!isset($result[$famille])) {
                $result[$famille] = [];
            }
            $result[$famille][] = [
                'id' => $fiche->getId(),
                'metier' => $metier,
                'quotite' => $quotite
            ];
        }
        return $result;
    }

}