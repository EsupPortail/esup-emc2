<?php

namespace Application\Service\FicheMetier;

use Application\Entity\Db\Competence;
use Metier\Entity\Db\Domaine;
use Metier\Entity\Db\FamilleProfessionnelle;
use Application\Entity\Db\FicheMetier;
use Application\Form\EntityFormManagmentTrait;
use Formation\Entity\Db\Formation;
use Application\Service\Application\ApplicationServiceAwareTrait;
use Application\Service\Competence\CompetenceServiceAwareTrait;
use Application\Service\GestionEntiteHistorisationTrait;
use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use Formation\Service\Formation\FormationServiceAwareTrait;
use UnicaenApp\Exception\RuntimeException;
use Zend\Mvc\Controller\AbstractController;

class FicheMetierService {
    use ApplicationServiceAwareTrait;
    use CompetenceServiceAwareTrait;
    use FormationServiceAwareTrait;
    use GestionEntiteHistorisationTrait;

    use EntityFormManagmentTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param FicheMetier $fiche
     * @return FicheMetier
     */
    public function create(FicheMetier $fiche)
    {
        $this->createFromTrait($fiche);
        return $fiche;
    }

    /**
     * @param FicheMetier $fiche
     * @return FicheMetier
     */
    public function update(FicheMetier $fiche)
    {
        $this->updateFromTrait($fiche);
        return $fiche;
    }

    /**
     * @param FicheMetier $fiche
     * @return FicheMetier
     */
    public function historise(FicheMetier $fiche)
    {
        $this->historiserFromTrait($fiche);
        return $fiche;
    }

    /**
     * @param FicheMetier $fiche
     * @return FicheMetier
     */
    public function restore(FicheMetier $fiche)
    {
        $this->restoreFromTrait($fiche);
        return $fiche;
    }

    /**
     * @param FicheMetier $fiche
     * @return FicheMetier
     */
    public function delete(FicheMetier $fiche)
    {
        $this->deleteFromTrait($fiche);
        return $fiche;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder()
    {
        $qb = $this->getEntityManager()->getRepository(FicheMetier::class)->createQueryBuilder('ficheMetier')
            ->addSelect('metier')->join('ficheMetier.metier', 'metier')
            ->addSelect('domaine')->join('metier.domaines', 'domaine')
            ->addSelect('famille')->join('domaine.famille', 'famille')
            ->addSelect('etat')->join('ficheMetier.etat', 'etat')
            ->addSelect('reference')->leftJoin('metier.references', 'reference')
            ->addSelect('referentiel')->leftJoin('reference.referentiel', 'referentiel')
            ;
        return $qb;
    }

    /**
     * @param string $order an attribute use to sort
     * @return FicheMetier[]
     */
    public function getFichesMetiers($order = 'id')
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
     * @param int $niveau
     * @return FicheMetier[]
     */
    public function getFichesMetiersWithNiveau(int $niveau)
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('metier.niveau IS NULL or metier.niveau >= :niveau')
            ->setParameter('niveau', $niveau)
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param string $order an attribute use to sort
     * @return FicheMetier[]
     */
    public function getFichesMetiersValides(string $order = 'id')
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
     * @param int $id
     * @return FicheMetier
     */
    public function getFicheMetier(int $id)
    {
        $qb = $this->createQueryBuilder()
            ->addSelect('fmactivite')->leftJoin('ficheMetier.activites', 'fmactivite')
            ->addSelect('activite')->leftJoin('fmactivite.activite', 'activite')
            ->addSelect('aformation')->leftJoin('activite.formations', 'aformation')

            //APPLICATIONS - fiche et activités associées
            ->addSelect('activite_applicationelement')->leftJoin('activite.applications', 'activite_applicationelement')
            ->addSelect('activite_application')->leftJoin('activite_applicationelement.application', 'activite_application')
            ->addSelect('activite_application_groupe')->leftJoin('activite_application.groupe', 'activite_application_groupe')
            ->addSelect('fiche_applicationelement')->leftJoin('ficheMetier.applications', 'fiche_applicationelement')
            ->addSelect('fiche_application')->leftJoin('fiche_applicationelement.application', 'fiche_application')
            ->addSelect('fiche_application_groupe')->leftJoin('fiche_application.groupe', 'fiche_application_groupe')

            //APPLICATIONS - fiche et activités associées
            ->addSelect('activite_competenceelement')->leftJoin('activite.competences', 'activite_competenceelement')
            ->addSelect('activite_competence')->leftJoin('activite_competenceelement.competence', 'activite_competence')
            ->addSelect('activite_competence_theme')->leftJoin('activite_competence.theme', 'activite_competence_theme')
            ->addSelect('activite_competence_type')->leftJoin('activite_competence.type', 'activite_competence_type')
            ->addSelect('fiche_competenceelement')->leftJoin('ficheMetier.competences', 'fiche_competenceelement')
            ->addSelect('fiche_competence')->leftJoin('fiche_competenceelement.competence', 'fiche_competence')
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
     * @return FicheMetier
     */
    public function getRequestedFicheMetier(AbstractController $controller, string $name = 'fiche', bool $notNull = false)
    {
        $ficheId = $controller->params()->fromRoute($name);
        $fiche = $this->getFicheMetier($ficheId);
        if($notNull && !$fiche) throw new RuntimeException("Aucune fiche de trouvée avec l'identifiant [".$ficheId."]");

        return $fiche;
    }

    /**
     * @return FicheMetier
     */
    public function getLastFicheMetier()
    {
        $fiches = $this->getFichesMetiers('id');
        return end($fiches);
    }

    /**
     * @param FamilleProfessionnelle $famille
     * @return FicheMetier[]
     */
    public function getFicheByFamille(FamilleProfessionnelle $famille)
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('famille = :famille')
            ->setParameter('famille', $famille)
            ->orderBy('metier.libelle')
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param Domaine $domaine
     * @return FicheMetier[]
     */
    public function getFicheByDomaine(Domaine $domaine)
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
    public function getFichesMetiersAsOptions(int $niveau =0)
    {
        $fiches = $this->getFichesMetiersWithNiveau($niveau);
        $array = [];
        foreach ($fiches as $fiche) {
            $array[$fiche->getId()] = $fiche->getMetier()->getLibelle();
        }
        return $array;
    }

    /**
     * //TODO remove that after implementing FormationElement
     * @param FicheMetier $fiche
     * @param Formation $formation
     * @param DateTime|null $date
     */
    public function addFormation(FicheMetier $fiche, Formation $formation, ?DateTime $date = null)
    {
        $fiche->addFormation($formation);
    }

    /**
     * @param FicheMetier $fiche
     * @param array $data
     * @return FicheMetier
     */
    public function updateFormations(FicheMetier $fiche, array $data)
    {

        $formationIds = [];
        if (isset($data['formations'])) $formationIds = $data['formations'];

        foreach ($formationIds as $formationId) {
            $formation = $this->getFormationService()->getFormation($formationId);
            if (!$fiche->hasFormation($formation)) {
                $fiche->addFormation($formation);
            }
        }

        $formations = $fiche->getFormations();
        /** @var Formation $formation */
        foreach ($formations as $formation) {
            if (array_search($formation->getId(), $formationIds) === false) {
                $fiche->removeFormation($formation);
            }
        }

        try {
            $this->getEntityManager()->flush($fiche);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'enregistrement en base",0 ,$e);
        }

        return $fiche;
    }

    /**
     * @param FicheMetier $fiche
     * @param array $data
     * @return FicheMetier
     */
    public function updateCompetences(FicheMetier $fiche, array $data)
    {

        $competenceIds = [];
        if (isset($data['competences'])) $competenceIds = $data['competences'];

        foreach ($competenceIds as $competenceId) {
            $competence = $this->getCompetenceService()->getCompetence($competenceId);
            if (!$fiche->hasCompetence($competence)) {
                $fiche->addCompetence($competence);
            }
        }

        $competences = $fiche->getCompetences();
        /** @var Competence $competence */
        foreach ($competences as $competence) {
            if (array_search($competence->getId(), $competenceIds) === false) {
                $fiche->removeCompetence($competence);
            }
        }

        try {
            $this->getEntityManager()->flush($fiche);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'enregistrement en base",0 ,$e);
        }

        return $fiche;
    }

    /**
     * @param FicheMetier $fiche
     * @param DateTime|null $date
     * @return array
     */
    public function getApplicationsDictionnaires(FicheMetier $fiche, ?DateTime $date = null)
    {
        $dictionnaire = [];

        foreach ($fiche->getApplicationListe() as $applicationElement) {
                $application = $applicationElement->getApplication();
                $dictionnaire[$application->getId()]["entite"] = $application;
                $dictionnaire[$application->getId()]["raison"][] = $fiche;
                $dictionnaire[$application->getId()]["conserve"] = true;
        }

        foreach ($fiche->getActivites() as $activite) {
            foreach ($activite->getActivite()->getApplicationListe() as $applicationElement) {
                $application = $applicationElement->getApplication();
                $dictionnaire[$application->getId()]["entite"] = $application;
                $dictionnaire[$application->getId()]["raison"][] = $activite;
                $dictionnaire[$application->getId()]["conserve"] = true;
            }
        }

        return $dictionnaire;
    }

    /**
     * @param FicheMetier $fiche
     * @param DateTime|null $date
     * @return array
     */
    public function getCompetencesDictionnaires(FicheMetier $fiche, ?DateTime $date = null)
    {
        $dictionnaire = [];

        foreach ($fiche->getCompetenceListe() as $competenceElement) {
            $competence = $competenceElement->getCompetence();
            $dictionnaire[$competence->getId()]["entite"] = $competence;
            $dictionnaire[$competence->getId()]["raison"][] = $fiche;
            $dictionnaire[$competence->getId()]["conserve"] = true;
        }

        foreach ($fiche->getActivites() as $activite) {
            foreach ($activite->getActivite()->getCompetenceListe() as $competenceElement) {
                $competence = $competenceElement->getCompetence();
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
    public function getFichesMetiersByCompetence(Competence $competence)
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

}