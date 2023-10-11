<?php

namespace FicheMetier\Service\FicheMetier;

use Application\Provider\Etat\FicheMetierEtats;
use Application\Provider\Template\PdfTemplate;
use Application\Service\Configuration\ConfigurationServiceAwareTrait;
use Carriere\Service\Niveau\NiveauService;
use Doctrine\ORM\Exception\NotSupported;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Element\Entity\Db\Application;
use Element\Entity\Db\ApplicationElement;
use Element\Entity\Db\Competence;
use Element\Entity\Db\CompetenceElement;
use Element\Form\SelectionApplication\SelectionApplicationHydratorAwareTrait;
use Element\Form\SelectionCompetence\SelectionCompetenceHydratorAwareTrait;
use Element\Service\Application\ApplicationServiceAwareTrait;
use Element\Service\ApplicationElement\ApplicationElementServiceAwareTrait;
use Element\Service\Competence\CompetenceServiceAwareTrait;
use Element\Service\CompetenceElement\CompetenceElementServiceAwareTrait;
use Element\Service\HasApplicationCollection\HasApplicationCollectionServiceAwareTrait;
use Element\Service\HasCompetenceCollection\HasCompetenceCollectionServiceAwareTrait;
use FicheMetier\Entity\Db\FicheMetier;
use FicheMetier\Entity\Db\FicheMetierMission;
use FicheMetier\Entity\Db\Mission;
use FicheMetier\Entity\Db\MissionActivite;
use FicheMetier\Service\FicheMetierMission\FicheMetierMissionServiceAwareTrait;
use FicheMetier\Service\MissionActivite\MissionActiviteServiceAwareTrait;
use FicheMetier\Service\MissionPrincipale\MissionPrincipaleServiceAwareTrait;
use Laminas\Mvc\Controller\AbstractController;
use Metier\Entity\Db\Domaine;
use Metier\Service\Domaine\DomaineServiceAwareTrait;
use Metier\Service\Metier\MetierServiceAwareTrait;
use Mpdf\MpdfException;
use RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenEtat\Service\EtatInstance\EtatInstanceServiceAwareTrait;
use UnicaenPdf\Exporter\PdfExporter;
use UnicaenRenderer\Service\Rendu\RenduServiceAwareTrait;

class FicheMetierService
{
    use ApplicationServiceAwareTrait;
    use ApplicationElementServiceAwareTrait;
    use CompetenceServiceAwareTrait;
    use CompetenceElementServiceAwareTrait;
    use ConfigurationServiceAwareTrait;
    use DomaineServiceAwareTrait;
    use EntityManagerAwareTrait;
    use EtatInstanceServiceAwareTrait;
    use FicheMetierMissionServiceAwareTrait;
    use MissionActiviteServiceAwareTrait;
    use MissionPrincipaleServiceAwareTrait;
    use RenduServiceAwareTrait;

    use HasApplicationCollectionServiceAwareTrait;
    use HasCompetenceCollectionServiceAwareTrait;
    use MetierServiceAwareTrait;

    use SelectionApplicationHydratorAwareTrait;
    use SelectionCompetenceHydratorAwareTrait;

    const REFERENS_SEP = "|";

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param FicheMetier $fiche
     * @return FicheMetier
     */
    public function create(FicheMetier $fiche): FicheMetier
    {
        try {
            $this->getEntityManager()->persist($fiche);
            $this->getEntityManager()->flush($fiche);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'enregistrement en BD.", $e);
        }
        return $fiche;
    }

    /**
     * @param FicheMetier $fiche
     * @return FicheMetier
     */
    public function update(FicheMetier $fiche): FicheMetier
    {
        try {
            $this->getEntityManager()->flush($fiche);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'enregistrement en BD.", $e);
        }
        return $fiche;
    }

    /**
     * @param FicheMetier $fiche
     * @return FicheMetier
     */
    public function historise(FicheMetier $fiche): FicheMetier
    {
        try {
            $fiche->historiser();
            $this->getEntityManager()->flush($fiche);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'enregistrement en BD.", $e);
        }
        return $fiche;
    }

    /**
     * @param FicheMetier $fiche
     * @return FicheMetier
     */
    public function restore(FicheMetier $fiche): FicheMetier
    {
        try {
            $fiche->dehistoriser();
            $this->getEntityManager()->flush($fiche);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'enregistrement en BD.", $e);
        }
        return $fiche;
    }

    /**
     * @param FicheMetier $fiche
     * @return FicheMetier
     */
    public function delete(FicheMetier $fiche): FicheMetier
    {
        try {
            $this->getEntityManager()->remove($fiche);
            $this->getEntityManager()->flush($fiche);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'enregistrement en BD.", $e);
        }
        return $fiche;
    }

    /** REQUETAGE *****************************************************************************************************/

    public function createQueryBuilder(): QueryBuilder
    {
        try {
            $qb = $this->getEntityManager()->getRepository(FicheMetier::class)->createQueryBuilder('ficheMetier')
                ->addSelect('metier')->join('ficheMetier.metier', 'metier')
                ->addSelect('domaine')->join('metier.domaines', 'domaine')
                ->addSelect('famille')->join('domaine.familles', 'famille')
                ->addSelect('etat')->leftjoin('ficheMetier.etats', 'etat')
                ->addSelect('etype')->leftjoin('etat.type', 'etype')
                ->addSelect('reference')->leftJoin('metier.references', 'reference')
                ->addSelect('referentiel')->leftJoin('reference.referentiel', 'referentiel');
        } catch (NotSupported $e) {
            throw new RuntimeException("Un problème est survenu lors de la création du QueryBuilder de [".FicheMetier::class."]",0,$e);
        }
        $qb = NiveauService::decorateWithNiveau($qb, 'metier');
        return $qb;
    }

    /** @return FicheMetier[] */
    public function getFichesMetiers(string $order = 'id'): array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('ficheMetier.', $order);

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param array $filtre
     * @param string $champ
     * @param string $ordre
     * @return FicheMetier[]
     */
    public function getFichesMetiersWithFiltre(array $filtre, string $champ = 'id', string $ordre = 'DESC'): array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('ficheMetier.' . $champ, $ordre);

        if (isset($filtre['expertise']) and $filtre['expertise'] != '') {
            $expertise = null;
            if ($filtre['expertise'] == "1") $expertise = true;
            if ($filtre['expertise'] == "0") $expertise = false;
            if ($expertise !== null) $qb = $qb->andWhere('ficheMetier.hasExpertise = :expertise')->setParameter('expertise', $expertise);
        }
        if (isset($filtre['etat']) and $filtre['etat'] != '') {
            $qb = $qb->andWhere('etype.id = :etat')->setParameter('etat', $filtre['etat']);
        }
        if (isset($filtre['domaine']) and $filtre['domaine'] != '') {
            $qb = $qb->andWhere('domaine.id = :domaine')->setParameter('domaine', $filtre['domaine']);
        }

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param int $niveau
     * @return FicheMetier[]
     */
    public function getFichesMetiersWithNiveau(int $niveau): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('niveauxbas.niveau >= :niveau')
            ->andWhere('niveauxhaut.niveau <= :niveau')
            ->setParameter('niveau', $niveau)
            ->andWhere('ficheMetier.histoDestruction IS NULL')
            ->andWhere('etat.code = :ok')
            ->setParameter('ok', FicheMetierEtats::ETAT_VALIDE);

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param string $order an attribute use to sort
     * @return FicheMetier[]
     */
    public function getFichesMetiersValides(string $order = 'id'): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('etype.code = :ucode')
            ->setParameter('ucode', FicheMetierEtats::ETAT_VALIDE)
            ->orderBy('ficheMetier.', $order);

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getFicheMetier(?int $id): ?FicheMetier
    {
        $qb = $this->createQueryBuilder()
//            ->addSelect('fmactivite')->leftJoin('ficheMetier.activites', 'fmactivite')
//            ->addSelect('activite')->leftJoin('fmactivite.activite', 'activite')
//            ->addSelect('activite_libelle')->leftJoin('activite.libelles', 'activite_libelle')
//            ->addSelect('activite_dscription')->leftJoin('activite.descriptions', 'activite_dscription')
//            ->addSelect('aformation')->leftJoin('activite.formations', 'aformation')

            //APPLICATIONS - fiche et activités associées
//            ->addSelect('activite_applicationelement')->leftJoin('activite.applications', 'activite_applicationelement')
//            ->addSelect('activite_application')->leftJoin('activite_applicationelement.application', 'activite_application')
//            ->addSelect('activite_application_groupe')->leftJoin('activite_application.groupe', 'activite_application_groupe')
//            ->addSelect('fiche_applicationelement')->leftJoin('ficheMetier.applications', 'fiche_applicationelement')
//            ->addSelect('fiche_application')->leftJoin('fiche_applicationelement.application', 'fiche_application')
//            ->addSelect('fiche_application_niveau')->leftJoin('fiche_applicationelement.niveau', 'fiche_application_niveau')
//            ->addSelect('fiche_application_groupe')->leftJoin('fiche_application.groupe', 'fiche_application_groupe')

            //COMPETENCE - fiche et activités associées
//            ->addSelect('activite_competenceelement')->leftJoin('activite.competences', 'activite_competenceelement')
//            ->addSelect('activite_competence')->leftJoin('activite_competenceelement.competence', 'activite_competence')
//            ->addSelect('activite_competence_theme')->leftJoin('activite_competence.theme', 'activite_competence_theme')
//            ->addSelect('activite_competence_type')->leftJoin('activite_competence.type', 'activite_competence_type')
//            ->addSelect('fiche_competenceelement')->leftJoin('ficheMetier.competences', 'fiche_competenceelement')
//            ->addSelect('fiche_competence')->leftJoin('fiche_competenceelement.competence', 'fiche_competence')
//            ->addSelect('fiche_competence_niveau')->leftJoin('fiche_competenceelement.niveau', 'fiche_competence_niveau')
//            ->addSelect('fiche_competence_theme')->leftJoin('fiche_competence.theme', 'fiche_competence_theme')
//            ->addSelect('fiche_competence_type')->leftJoin('fiche_competence.type', 'fiche_competence_type')

            ->addSelect('categorie')->leftJoin('metier.categorie', 'categorie')
            ->andWhere('ficheMetier.id = :id')
            ->setParameter('id', $id);

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs fiches métiers portent le même identifiant [" . $id . "].",0,$e);
        }
        return $result;
    }

    public function getRequestedFicheMetier(AbstractController $controller, string $name = 'fiche', bool $notNull = false): ?FicheMetier
    {
        $ficheId = $controller->params()->fromRoute($name);
        $fiche = $this->getFicheMetier($ficheId);
        if ($notNull && !$fiche) throw new RuntimeException("Aucune fiche de trouvée avec l'identifiant [" . $ficheId . "]");

        return $fiche;
    }

    /** @return FicheMetier[] */
    public function getFicheByDomaine(Domaine $domaine): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('domaine = :domaine')
            ->setParameter('domaine', $domaine)
            ->orderBy('metier.libelle');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getFichesMetiersAsOptionGroup(): array
    {
        $domaines = $this->getDomaineService()->getDomaines();
        $options = [];

        foreach ($domaines as $domaine) {
            $optionsoptions = [];
            foreach ($this->getFicheByDomaine($domaine) as $fiche) {
                if ($fiche->estNonHistorise()) $optionsoptions[$fiche->getId()] = $fiche->getMetier()->getLibelle() . " (dernière modification " . $fiche->getHistoModification()->format("d/m/Y") . ")";
            }
            asort($optionsoptions);
            $array = [
                'label' => $domaine->getLibelle(),
                'options' => $optionsoptions,
            ];
            $options[] = $array;
        }

        return $options;
    }

    /** FACADE ********************************************************************************************************/

    public function addMission(FicheMetier $ficheMetier, Mission $missionPrincipale) : void
    {

        $fichemetierMission = new FicheMetierMission();
        $fichemetierMission->setFicheMetier($ficheMetier);
        $fichemetierMission->setMission($missionPrincipale);
        $fichemetierMission->setOrdre(9999 );
        $this->getFicheMetierMissionService()->create($fichemetierMission);
    }

    public function moveMission(FicheMetier $ficheMetier, Mission $missionPrincipale, string $direction) : void
    {
        $fichemetierMission = $this->getFicheMetierMissionService()->getFicherMetierMissionByFicheMetierAndMission($ficheMetier, $missionPrincipale);
        if ($fichemetierMission === null) return;

        $position = $fichemetierMission->getOrdre();
        $newPosition = -1;
        if ($direction === 'up') $newPosition = $position - 1;
        if ($direction === 'down') $newPosition = $position + 1;

        $fichemetierMissionBis = $this->getFicheMetierMissionService()->getFicherMetierMissionByFicheMetierAndPosition($ficheMetier, $newPosition);
        if ($fichemetierMissionBis === null) return;

        if ($fichemetierMission !== $fichemetierMissionBis) {
            $fichemetierMission->setOrdre($newPosition);
            $this->getFicheMetierMissionService()->update($fichemetierMission);
            $fichemetierMissionBis->setOrdre($position);
            $this->getFicheMetierMissionService()->update($fichemetierMissionBis);
        }
    }

    public function removeMission(FicheMetier $ficheMetier, Mission $missionPrincipale) : void
    {
        $fichemetierMission = $this->getFicheMetierMissionService()->getFicherMetierMissionByFicheMetierAndMission($ficheMetier, $missionPrincipale);
        $this->getFicheMetierMissionService()->delete($fichemetierMission);
    }

    public function compressMission(FicheMetier $ficheMetier) : void
    {
        $missions = $ficheMetier->getMissions();
        usort($missions, function (FicheMetierMission $a, FicheMetierMission $b) { return $a->getOrdre() > $b->getOrdre();});

        $position = 1;
        foreach ($missions as $mission) {
            $mission->setOrdre($position);
            $this->getFicheMetierMissionService()->update($mission);
            $position++;
        }
    }

    public function setDefaultValues(FicheMetier $fiche): FicheMetier
    {
        $this->getEtatInstanceService()->setEtatActif($fiche, FicheMetierEtats::ETAT_REDACTION);
        $this->getConfigurationService()->addDefaultToFicheMetier($fiche);
        return $fiche;
    }

    /**
     * @param FicheMetier $fiche
     * @param bool $asElement
     * @return array
     */
    public function getApplicationsDictionnaires(FicheMetier $fiche, bool $asElement = false): array
    {
        $dictionnaire = [];

        foreach ($fiche->getApplicationListe() as $applicationElement) {
            $application = ($asElement) ? $applicationElement : $applicationElement->getApplication();
            $dictionnaire[$application->getId()]["entite"] = $application;
            $dictionnaire[$application->getId()]["raison"][] = $fiche;
            $dictionnaire[$application->getId()]["conserve"] = true;
        }

//        foreach ($fiche->getMissions() as $mission) {
//            foreach ($mission->getMission()->getApplicationListe() as $applicationElement) {
//                $application = ($asElement)?$applicationElement:$applicationElement->getApplication();
//                $dictionnaire[$application->getId()]["entite"] = $application;
//                $dictionnaire[$application->getId()]["raison"][] = $mission;
//                $dictionnaire[$application->getId()]["conserve"] = true;
//            }
//        }

        return $dictionnaire;
    }

    /**
     * @param FicheMetier $fiche
     * @param bool $asElement
     * @return array
     */
    public function getCompetencesDictionnaires(FicheMetier $fiche, bool $asElement = false): array
    {
        $dictionnaire = [];

        foreach ($fiche->getCompetenceListe() as $competenceElement) {
            $competence = ($asElement) ? $competenceElement : $competenceElement->getCompetence();
            $dictionnaire[$competence->getId()]["entite"] = $competence;
            $dictionnaire[$competence->getId()]["raison"][] = $fiche;
            $dictionnaire[$competence->getId()]["conserve"] = true;
        }

        //todo se rebaser sur mission
//        foreach ($fiche->getActivites() as $activite) {
//            foreach ($activite->getActivite()->getCompetenceListe() as $competenceElement) {
//                $competence = ($asElement)?$competenceElement:$competenceElement->getCompetence();
//                $dictionnaire[$competence->getId()]["entite"] = $competence;
//                $dictionnaire[$competence->getId()]["raison"][] = $activite;
//                $dictionnaire[$competence->getId()]["conserve"] = true;
//            }
//        }
        return $dictionnaire;
    }

    /**
     * @param Competence $competence
     * @return FicheMetier[]
     */
    public function getFichesMetiersByCompetence(Competence $competence): array
    {
        $qb = $this->createQueryBuilder()
            ->addSelect('fiche_competenceelement')->leftJoin('ficheMetier.competences', 'fiche_competenceelement')
            ->addSelect('fiche_competence')->leftJoin('fiche_competenceelement.competence', 'fiche_competence')
            ->andWhere('fiche_competenceelement.competence = :competence')
            ->setParameter('competence', $competence)
            ->orderBy('metier.libelle', 'ASC');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param FicheMetier $fiche
     * @return FicheMetier
     */
    public function dupliquerFicheMetier(FicheMetier $fiche): FicheMetier
    {
        $duplicata = new FicheMetier();
        //base
        $duplicata->setMetier($fiche->getMetier());
        $duplicata->setExpertise($fiche->hasExpertise());
        $this->create($duplicata);

        //missions principales
        foreach ($fiche->getMissions() as $mission) {
            $activiteDuplicata = new FicheMetierMission();
            $activiteDuplicata->setFicheMetier($duplicata);
            $activiteDuplicata->setMission($mission->getMission());
            $activiteDuplicata->setOrdre($mission->getOrdre());
            try {
                $this->getEntityManager()->persist($activiteDuplicata);
                $this->getEntityManager()->flush($activiteDuplicata);
            } catch (ORMException $e) {
                throw new RuntimeException("Un problème est survenu lors de la duplication d'un activité",0,$e);
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
        $this->getEtatInstanceService()->setEtatActif($duplicata, FicheMetierEtats::ETAT_REDACTION);
        $this->update($duplicata);

        return $duplicata;
    }

    public function readFromCSV($fichier_path): array
    {
        $handle = fopen($fichier_path, "r");

        $array = [];
        while ($content = fgetcsv($handle, 0, ";")) {
            $array[] = $content;
        }

        $code_index = array_search('Code emploi type', $array[0]);
        $code_libelle = $array[1][$code_index];
        $metier = $this->getMetierService()->getMetierByReference('REFERENS', $code_libelle);
        $mission_index = array_search('Mission', $array[0]);
        $mission_libelle = $array[1][$mission_index];
        $activites_index = array_search('Activités principales', $array[0]);
        $activites_libelle = explode(FicheMetierService::REFERENS_SEP, $array[1][$activites_index]);

        $competences_index = array_search('COMPETENCES_ID', $array[0]);
        $competences_ids = explode(FicheMetierService::REFERENS_SEP, $array[1][$competences_index]);

        $competences['Connaissances'] = [];
        $competences['Opérationnelles'] = [];
        $competences['Comportementales'] = [];
        $competences['Manquantes'] = [];
        $competencesListe = [];
        foreach ($competences_ids as $competence_id) {
            $competence = $this->getCompetenceService()->getCompetenceByIdSource('REFERENS 3', (int) $competence_id);
            if ($competence !== null) {
                $competences[$competence->getType()->getLibelle()][$competence->getId()] = $competence;
                $competencesListe[$competence->getId()] = $competence;
            } else {
                $competences['Manquantes'][] = $competence_id;
            }
        }
        $applications = [];

        return [
            'code' => $code_libelle,
            'metier' => $metier,
            'mission' => $mission_libelle,
            'activites' => $activites_libelle,
            'competences' => $competences,
            'competencesListe' => $competencesListe,
            'applications' => $applications,

        ];
    }

    public function importFromCsvArray(array $csvInfos): FicheMetier
    {
        //init
        $fiche = new FicheMetier();
        $fiche->setMetier($csvInfos['metier']);
        $this->create($fiche);
        $this->getEtatInstanceService()->setEtatActif($fiche, FicheMetierEtats::ETAT_REDACTION);

        // MISSIONS PRINCIPALES
        $mission = new Mission();
        $mission->setLibelle($csvInfos['mission']);
        $this->getMissionPrincipaleService()->create($mission);
        $this->addMission($fiche, $mission);
        $this->compressMission($fiche);

        $ordre = 1;
        foreach ($csvInfos['activites'] as $libelle) {
            $activite = new MissionActivite();
            $activite->setMission($mission);
            $activite->setLibelle($libelle);
            $activite->setOrdre($ordre);
            $ordre++;
            $this->getMissionActiviteService()->create($activite);
        }

        //APPLICATION (invoker l'hydrator plutôt)
        $this->getHasApplicationCollectionService()->updateApplications($fiche, ['applications' => $csvInfos['applications']]);
//        $this->getSelectionApplicationHydrator()->hydrate(['applications' => $csvInfos['applications']], $fiche);

        //COMPETENCE (invoker l'hydrator plutôt)(invoker l'hydrator plutôt)
        $this->getHasCompetenceCollectionService()->updateCompetences($fiche, ['competences' => $csvInfos['competencesListe']]);
//        $this->getSelectionCompetenceHydrator()->hydrate(['competences' => $csvInfos['competencesListe']], $fiche);
        return $fiche;
    }

    public function exporter(?FicheMetier $fichemetier) : string
    {
        $vars = [
            'fichemetier' => $fichemetier,
            'metier' => $fichemetier->getMetier(),
        ];
        $rendu = $this->getRenduService()->generateRenduByTemplateCode(PdfTemplate::FICHE_METIER, $vars);

        try {
            $exporter = new PdfExporter();
            $exporter->getMpdf()->SetTitle($rendu->getSujet());
            $exporter->setHeaderScript('');
            $exporter->setFooterScript('');
            $exporter->addBodyHtml($rendu->getCorps());
            return $exporter->export($rendu->getSujet());
        } catch (MpdfException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'export en PDF", 0, $e);
        }
    }

    public function genererInfosFromCSV(string $fichier_path): array
    {
        $csvInfos = $this->readFromCSV($fichier_path);

        $ajouts = $this->getConfigurationService()->getConfigurationsFicheMetier();
        foreach ($ajouts as $ajout) {
            if ($ajout->getEntityType() === Application::class) {
                $application = $ajout->getEntity();
                $csvInfos['applications'][$application->getId()] = $application;
            }
            if ($ajout->getEntityType() === Competence::class) {
                $competence = $ajout->getEntity();
                $csvInfos['competencesListe'][$competence->getId()] = $competence;
                $csvInfos['competences'][$competence->getType()->getLibelle()][$competence->getId()] = $competence;
            }
        }

        // tri
        foreach (['Connaissances', 'Opérationnelles', 'Comportementales'] as $type) {
            usort($csvInfos['competences'][$type], function (Competence $a, Competence $b) {
                return $a->getLibelle() > $b->getLibelle();
            });
        }
        usort($csvInfos['applications'], function (Application $a, Application $b) {
            return $a->getLibelle() > $b->getLibelle();
        });

        return $csvInfos;
    }

}