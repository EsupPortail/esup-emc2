<?php

namespace Application\Entity\Db;

use DateTime;
use UnicaenUtilisateur\Entity\DateTimeAwareTrait;
use Doctrine\Common\Collections\ArrayCollection;
use UnicaenApp\Exception\RuntimeException;
use UnicaenUtilisateur\Entity\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\HistoriqueAwareTrait;
use Zend\Permissions\Acl\Resource\ResourceInterface;

class FichePoste implements ResourceInterface, HistoriqueAwareInterface {
    use HistoriqueAwareTrait;
    use DateTimeAwareTrait
        ;
    public function getResourceId()
    {
        return 'FichePoste';
    }

    /** @var int */
    private $id;
    /** @var string */
    private $libelle;
    /** @var Agent */
    private $agent;

    /** @var SpecificitePoste */
    private $specificite;
    /** @var ArrayCollection (Expertise) */
    private $expertises;

    /** @var Poste */
    private $poste;

    /** @var ArrayCollection (FicheTypeExterne)*/
    private $fichesMetiers;
    /** @var ArrayCollection (FicheposteActiviteDescriptionRetiree) */
    private $descriptionsRetirees;
    /** @var ArrayCollection (FicheposteApplicationRetiree) */
    private $applicationsRetirees;
    /** @var ArrayCollection (FicheposteCompetenceRetiree) */
    private $competencesRetirees;
    /** @var ArrayCollection (FicheposteFormationRetiree) */
    private $formationsRetirees;

    public function __invoke()
    {
        $this->fichesMetiers = new ArrayCollection();
        $this->descriptionsRetirees = new ArrayCollection();
        $this->applicationsRetirees = new ArrayCollection();
        $this->competencesRetirees = new ArrayCollection();
        $this->formationsRetirees = new ArrayCollection();
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

    /** Descriptions Retirées ******************************************************************************************/

    /** @return ArrayCollection */
    public function getDescriptionsRetirees() {
        return $this->descriptionsRetirees;
    }

    /** @param FicheposteActiviteDescriptionRetiree $description */
    public function addDescriptionRetiree(FicheposteActiviteDescriptionRetiree $description) {
        $this->descriptionsRetirees->add($description);
    }

    /** @param FicheposteActiviteDescriptionRetiree $description */
    public function removeDescriptionRetiree(FicheposteActiviteDescriptionRetiree $description) {
        $this->descriptionsRetirees->removeElement($description);
    }

    public function clearDescriptionsRetirees() {
        $this->descriptionsRetirees->clear();
    }

    /** Competences Retirées ******************************************************************************************/

    /** @return ArrayCollection */
    public function getCompetencesRetirees() {
        return $this->competencesRetirees;
    }

    /** @param FicheposteCompetenceRetiree $competence */
    public function addCompetenceRetiree(FicheposteCompetenceRetiree $competence) {
        $this->competencesRetirees->add($competence);
    }

    /** @param FicheposteCompetenceRetiree $competence */
    public function removeCompetenceRetiree(FicheposteCompetenceRetiree $competence) {
        $this->competencesRetirees->removeElement($competence);
    }

    public function clearCompetencesRetirees() {
        $this->competencesRetirees->clear();
    }

    /** Formations Retirées *******************************************************************************************/

    /** @return ArrayCollection */
    public function getFormationsRetirees() {
        return $this->formationsRetirees;
    }

    /** @param FicheposteFormationRetiree $formation */
    public function addFormationRetiree(FicheposteFormationRetiree $formation) {
        $this->formationsRetirees->add($formation);
    }

    /** @param FicheposteFormationRetiree $formation */
    public function removeFormationRetiree(FicheposteFormationRetiree $formation) {
        $this->formationsRetirees->removeElement($formation);
    }

    public function clearFormationsRetirees() {
        $this->formationsRetirees->clear();
    }

    /** Applications Retirées *****************************************************************************************/

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

    /** EXPERTISE *****************************************************************************************************/

    /**
     * @return ArrayCollection
     */
    public function getExpertises()
    {
        return $this->expertises;
    }

    /**
     * @param DateTime $date
     * @return Expertise[]
     */
    public function getCurrentExpertises($date = null)
    {
        if ($date === null) $date = $this->getDateTime();

        $expertises = [];
        /** @var Expertise $expertise */
        foreach ($this->expertises as $expertise) {
            if ($expertise->estNonHistorise($date)) {
                $expertises[] = $expertise;
            }
        }
        return $expertises;
    }

    /** Fonctions pour simplifier  */

    /**
     * @param FicheMetierTypeActivite $activite
     * @param DateTime $date
     * @return ActiviteDescription[]
     */
    public function getDescriptions(FicheMetierTypeActivite $activite, DateTime $date)
    {
        $dictionnaire = $activite->getActivite()->getDescriptions($date);
        return $dictionnaire;
    }

    /**
     * @param FicheMetierTypeActivite $FTActivite
     * @param DateTime $date
     * @return ActiviteDescription[]
     */
    public function getDescriptionsConservees(FicheMetierTypeActivite $FTActivite, DateTime $date)
    {
        /** @var ActiviteDescription[] $descriptions */
        $descriptions = $FTActivite->getActivite()->getDescriptions($date);
        $dictionnaire = [];
        foreach ($descriptions as $description) {
            $found = false;
            /** @var FicheposteActiviteDescriptionRetiree $retiree */
            foreach($this->getDescriptionsRetirees() as $retiree) {
                if ($retiree->estNonHistorise() AND $retiree->getDescription() === $description) {
                    $found = true;
                    break;
                }
            }
            if (!$found) $dictionnaire[$description->getId()] = $description;
        }
        return $dictionnaire;
    }

    /**
     * @return string
     */
    public function getLibelleMetierPrincipal()
    {
        if ($this->getFicheTypeExternePrincipale()) {
            return $this->getFicheTypeExternePrincipale()->getFicheType()->getMetier()->getLibelle();
        }
        return "Non défini";
    }

    public function isComplete()
    {
        if (! $this->getAgent()) return false;
        if (! $this->getPoste()) return false;
        if (! $this->getFicheTypeExternePrincipale()) return false;
        return true;
    }

    public function isVide()
    {
        if ( $this->getAgent()) return false;
        if ( $this->getPoste()) return false;
        if ( $this->getFicheTypeExternePrincipale()) return false;
        return true;
    }

    public function hasExpertise() {
        /** @var FicheTypeExterne $fichesMetier */
        foreach ($this->fichesMetiers as $fichesMetier) {
            $metier = $fichesMetier->getFicheType()->getMetier();
            if ($metier AND $metier->hasExpertise()) return true;
        }
        return false;
    }
}