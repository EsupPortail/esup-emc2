<?php

namespace Application\Service\FicheMetier;

use Application\Entity\Db\ApplicationElement;
use Application\Entity\Db\Competence;
use Application\Entity\Db\CompetenceElement;
use Application\Entity\Db\FicheMetier;
use Application\Entity\Db\FicheMetierTypeActivite;
use Application\Form\EntityFormManagmentTrait;
use Application\Service\Application\ApplicationServiceAwareTrait;
use Application\Service\ApplicationElement\ApplicationElementServiceAwareTrait;
use Application\Service\Competence\CompetenceServiceAwareTrait;
use Application\Service\CompetenceElement\CompetenceElementServiceAwareTrait;
use Application\Service\GestionEntiteHistorisationTrait;
use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use Formation\Service\Formation\FormationServiceAwareTrait;
use Metier\Entity\Db\Domaine;
use UnicaenApp\Exception\RuntimeException;
use UnicaenEtat\Service\Etat\EtatServiceAwareTrait;
use Zend\Mvc\Controller\AbstractController;

class FicheMetierService {
    use ApplicationServiceAwareTrait;
    use ApplicationElementServiceAwareTrait;
    use CompetenceServiceAwareTrait;
    use CompetenceElementServiceAwareTrait;
    use EtatServiceAwareTrait;
    use FormationServiceAwareTrait;
    use GestionEntiteHistorisationTrait;

    use EntityFormManagmentTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param FicheMetier $fiche
     * @return FicheMetier
     */
    public function create(FicheMetier $fiche) : FicheMetier
    {
        $this->createFromTrait($fiche);
        return $fiche;
    }

    /**
     * @param FicheMetier $fiche
     * @return FicheMetier
     */
    public function update(FicheMetier $fiche) : FicheMetier
    {
        $this->updateFromTrait($fiche);
        return $fiche;
    }

    /**
     * @param FicheMetier $fiche
     * @return FicheMetier
     */
    public function historise(FicheMetier $fiche) : FicheMetier
    {
        $this->historiserFromTrait($fiche);
        return $fiche;
    }

    /**
     * @param FicheMetier $fiche
     * @return FicheMetier
     */
    public function restore(FicheMetier $fiche) : FicheMetier
    {
        $this->restoreFromTrait($fiche);
        return $fiche;
    }

    /**
     * @param FicheMetier $fiche
     * @return FicheMetier
     */
    public function delete(FicheMetier $fiche) : FicheMetier
    {
        $this->deleteFromTrait($fiche);
        return $fiche;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->getEntityManager()->getRepository(FicheMetier::class)->createQueryBuilder('ficheMetier')
            ->addSelect('metier')->join('ficheMetier.metier', 'metier')
            ->addSelect('domaine')->join('metier.domaines', 'domaine')
            ->addSelect('famille')->join('domaine.famille', 'famille')
            ->addSelect('etat')->join('ficheMetier.etat', 'etat')
            ->addSelect('etype')->join('etat.type', 'etype')
            ->addSelect('niveaux')->leftJoin('metier.niveaux', 'niveaux')
            ->addSelect('reference')->leftJoin('metier.references', 'reference')
            ->addSelect('referentiel')->leftJoin('reference.referentiel', 'referentiel')
            ;
        return $qb;
    }

    /**
     * @return FicheMetier[]
     */
    public function getFichesMetiersForIndex() : array
    {
        $qb = $this->getEntityManager()->getRepository(FicheMetier::class)->createQueryBuilder('ficheMetier')
            ->addSelect('metier')->join('ficheMetier.metier', 'metier')
            ->addSelect('domaine')->join('metier.domaines', 'domaine')
            ->addSelect('etat')->join('ficheMetier.etat', 'etat')
            ->addSelect('etype')->join('etat.type', 'etype')
        ;
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param string $order an attribute use to sort
     * @return FicheMetier[]
     */
    public function getFichesMetiers(string $order = 'id') : array
    {
       $qb = $this->createQueryBuilder()
//            ->addSelect('application')->leftJoin('ficheMetier.applications', 'application')
//            ->addSelect('formation')->leftJoin('ficheMetier.formations', 'formation')
//            ->addSelect('competence')->leftJoin('ficheMetier.competences', 'competence')
            ->orderBy('ficheMetier.', $order)
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param array $filtre
     * @param string $champ
     * @param string $ordre
     * @return FicheMetier[]
     */
    public function getFichesMetiersWithFiltre(array $filtre, string $champ = 'id', string $ordre = 'DESC') : array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('ficheMetier.' . $champ, $ordre)
        ;

        if (isset($filtre['expertise']) AND $filtre['expertise'] != '') {
            $expertise = null;
            if ($filtre['expertise'] == "1") $expertise = true;
            if ($filtre['expertise'] == "0") $expertise = false;
            if ($expertise !== null) $qb = $qb->andWhere('ficheMetier.hasExpertise = :expertise')->setParameter('expertise', $expertise);
        }
        if (isset($filtre['etat']) AND $filtre['etat'] != '') {
            $qb = $qb->andWhere('etat.id = :etat')->setParameter('etat', $filtre['etat']);
        }
        if (isset($filtre['domaine']) AND $filtre['domaine'] != '') {
            $qb = $qb->andWhere('domaine.id = :domaine')->setParameter('domaine', $filtre['domaine']);
        }

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param int $niveau
     * @return FicheMetier[]
     */
    public function getFichesMetiersWithNiveau(int $niveau) : array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('niveaux.borneInferieure >= :niveau')
            ->andWhere('niveaux.borneSuperieure <= :niveau')
            ->setParameter('niveau', $niveau)
            ->andWhere('ficheMetier.histoDestruction IS NULL')
            ->andWhere('etat.code = :ok')
            ->setParameter('ok', FicheMetier::ETAT_VALIDE)
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param string $order an attribute use to sort
     * @return FicheMetier[]
     */
    public function getFichesMetiersValides(string $order = 'id') : array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('etat.code = :ucode')
            ->setParameter('ucode', FicheMetier::ETAT_VALIDE)
            ->orderBy('ficheMetier.', $order)
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param int|null $id
     * @return FicheMetier
     */
    public function getFicheMetier(?int $id) : ?FicheMetier
    {
        $qb = $this->createQueryBuilder()
            ->addSelect('fmactivite')->leftJoin('ficheMetier.activites', 'fmactivite')
            ->addSelect('activite')->leftJoin('fmactivite.activite', 'activite')
            ->addSelect('activite_libelle')->leftJoin('activite.libelles', 'activite_libelle')
            ->addSelect('activite_dscription')->leftJoin('activite.descriptions', 'activite_dscription')
            ->addSelect('aformation')->leftJoin('activite.formations', 'aformation')

            //APPLICATIONS - fiche et activités associées
            ->addSelect('activite_applicationelement')->leftJoin('activite.applications', 'activite_applicationelement')
            ->addSelect('activite_application')->leftJoin('activite_applicationelement.application', 'activite_application')
            ->addSelect('activite_application_groupe')->leftJoin('activite_application.groupe', 'activite_application_groupe')
            ->addSelect('fiche_applicationelement')->leftJoin('ficheMetier.applications', 'fiche_applicationelement')
            ->addSelect('fiche_application')->leftJoin('fiche_applicationelement.application', 'fiche_application')
            ->addSelect('fiche_application_niveau')->leftJoin('fiche_applicationelement.niveau', 'fiche_application_niveau')
            ->addSelect('fiche_application_groupe')->leftJoin('fiche_application.groupe', 'fiche_application_groupe')

            //COMPETENCE - fiche et activités associées
            ->addSelect('activite_competenceelement')->leftJoin('activite.competences', 'activite_competenceelement')
            ->addSelect('activite_competence')->leftJoin('activite_competenceelement.competence', 'activite_competence')
            ->addSelect('activite_competence_theme')->leftJoin('activite_competence.theme', 'activite_competence_theme')
            ->addSelect('activite_competence_type')->leftJoin('activite_competence.type', 'activite_competence_type')
            ->addSelect('fiche_competenceelement')->leftJoin('ficheMetier.competences', 'fiche_competenceelement')
            ->addSelect('fiche_competence')->leftJoin('fiche_competenceelement.competence', 'fiche_competence')
            ->addSelect('fiche_competence_niveau')->leftJoin('fiche_competenceelement.niveau', 'fiche_competence_niveau')
            ->addSelect('fiche_competence_theme')->leftJoin('fiche_competence.theme', 'fiche_competence_theme')
            ->addSelect('fiche_competence_type')->leftJoin('fiche_competence.type', 'fiche_competence_type')

            ->addSelect('categorie')->leftJoin('metier.categorie', 'categorie')
            ->andWhere('ficheMetier.id = :id')
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs fiches métiers portent le même identifiant [".$id."].");
        }
        return $result;
    }

    /**
     * @param AbstractController $controller
     * @param string $name
     * @param bool $notNull
     * @return FicheMetier|null
     */
    public function getRequestedFicheMetier(AbstractController $controller, string $name = 'fiche', bool $notNull = false) : ?FicheMetier
    {
        $ficheId = $controller->params()->fromRoute($name);
        $fiche = $this->getFicheMetier($ficheId);
        if($notNull && !$fiche) throw new RuntimeException("Aucune fiche de trouvée avec l'identifiant [".$ficheId."]");

        return $fiche;
    }

    /**
     * @param string $order
     * @return FicheMetier
     */
    public function getLastFicheMetier(string $order = 'id') : FicheMetier
    {
        $fiches = $this->getFichesMetiers($order);
        return end($fiches);
    }

    /**
     * @param Domaine $domaine
     * @return FicheMetier[]
     */
    public function getFicheByDomaine(Domaine $domaine) : array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('domaine = :domaine')
            ->setParameter('domaine', $domaine)
            ->orderBy('metier.libelle')
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param int $niveau
     * @return array
     */
    public function getFichesMetiersAsOptions(int $niveau =0) : array
    {
        $fiches = $this->getFichesMetiersWithNiveau($niveau);
        $array = [];
        foreach ($fiches as $fiche) {
            $array[$fiche->getId()] = $fiche->getMetier()->getLibelle();
        }
        return $array;
    }

    /**
     * @param FicheMetier $fiche
     * @param bool $asElement
     * @param DateTime|null $date
     * @return array
     */
    public function getApplicationsDictionnaires(FicheMetier $fiche, bool $asElement = false, ?DateTime $date = null) : array
    {
        $dictionnaire = [];

        foreach ($fiche->getApplicationListe() as $applicationElement) {
            if ($asElement) {
                $application = $applicationElement;
            } else {
                $application = $applicationElement->getApplication();
            }
            $dictionnaire[$application->getId()]["entite"] = $application;
            $dictionnaire[$application->getId()]["raison"][] = $fiche;
            $dictionnaire[$application->getId()]["conserve"] = true;
        }

        foreach ($fiche->getActivites() as $activite) {
            foreach ($activite->getActivite()->getApplicationListe() as $applicationElement) {
                if ($asElement) {
                    $application = $applicationElement;
                } else {
                    $application = $applicationElement->getApplication();
                }
                $dictionnaire[$application->getId()]["entite"] = $application;
                $dictionnaire[$application->getId()]["raison"][] = $activite;
                $dictionnaire[$application->getId()]["conserve"] = true;
            }
        }

        return $dictionnaire;
    }

    /**
     * @param FicheMetier $fiche
     * @param bool $asElement
     * @param DateTime|null $date
     * @return array
     */
    public function getCompetencesDictionnaires(FicheMetier $fiche, bool $asElement = false, ?DateTime $date = null) : array
    {
        $dictionnaire = [];

        foreach ($fiche->getCompetenceListe() as $competenceElement) {
            if ($asElement) {
                $competence = $competenceElement;
            } else {
                $competence = $competenceElement->getCompetence();
            }
            $dictionnaire[$competence->getId()]["entite"] = $competence;
            $dictionnaire[$competence->getId()]["raison"][] = $fiche;
            $dictionnaire[$competence->getId()]["conserve"] = true;
        }

        foreach ($fiche->getActivites() as $activite) {
            foreach ($activite->getActivite()->getCompetenceListe() as $competenceElement) {
                if ($asElement) {
                    $competence = $competenceElement;
                } else {
                    $competence = $competenceElement->getCompetence();
                }
                $dictionnaire[$competence->getId()]["entite"] = $competence;
                $dictionnaire[$competence->getId()]["raison"][] = $activite;
                $dictionnaire[$competence->getId()]["conserve"] = true;
            }
        }
        return $dictionnaire;
    }

    /**
     * @param Competence $competence
     * @return FicheMetier[]
     */
    public function getFichesMetiersByCompetence(Competence $competence) : array
    {
        $qb = $this->createQueryBuilder()
            ->addSelect('fiche_competenceelement')->leftJoin('ficheMetier.competences', 'fiche_competenceelement')
            ->addSelect('fiche_competence')->leftJoin('fiche_competenceelement.competence', 'fiche_competence')
            ->andWhere('fiche_competenceelement.competence = :competence')
            ->setParameter('competence', $competence)
            ->orderBy('metier.libelle', 'ASC')
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param FicheMetier $fiche
     * @return FicheMetier
     */
    public function dupliquerFicheMetier(FicheMetier $fiche) : FicheMetier
    {
        $duplicata = new FicheMetier();
        //base
        $duplicata->setMetier($fiche->getMetier());
        $duplicata->setExpertise($fiche->hasExpertise());
        $this->create($duplicata);

        //missions principales
        /** @var FicheMetierTypeActivite $activite */
        foreach ($fiche->getActivites() as $activite) {
            $activiteDuplicata = new FicheMetierTypeActivite();
            $activiteDuplicata->setActivite($activite->getActivite());
            $activiteDuplicata->setPosition($activite->getPosition());
            $activiteDuplicata->setFiche($duplicata);
            try {
                $this->getEntityManager()->persist($activiteDuplicata);
                $this->getEntityManager()->flush($activiteDuplicata);
            } catch (ORMException $e) {
                throw new RuntimeException("Un problème est survenu lors de la duplication d'un activité");
            }
        }

        //applications
        /** @var ApplicationElement $application */
        foreach ($fiche->getApplicationCollection() as $application) {
            $element = new ApplicationElement();
            $element->setApplication($application->getApplication());
            $element->setCommentaire($application->getCommentaire());
            $element->setClef($application->isClef());
            $this->getApplicationElementService()->create($element);
            if ($application->estHistorise()) {
                $this->getApplicationElementService()->historise($element);
                $this->getApplicationElementService()->update($element);
            }
            $duplicata->addApplicationElement($element);
        }

        //compétences
        /** @var CompetenceElement $competence */
        foreach ($fiche->getCompetenceCollection() as $competence) {
            $element = new CompetenceElement();
            $element->setCompetence($competence->getCompetence());
            $element->setCommentaire($competence->getCommentaire());
            $element->setClef($competence->isClef());
            $this->getCompetenceElementService()->create($element);
            if ($competence->estHistorise()) {
                $this->getCompetenceElementService()->historise($element);
                $this->getCompetenceElementService()->update($element);
            }
            $duplicata->addCompetenceElement($element);
        }

        //etat
        $duplicata->setEtat($this->getEtatService()->getEtatByCode(FicheMetier::ETAT_REDACTION));
        $this->update($duplicata);

        return $duplicata;
    }

}