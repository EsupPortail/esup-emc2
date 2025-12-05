<?php

namespace FicheMetier\Service\FicheMetier;

use Application\Provider\Etat\FicheMetierEtats;
use Application\Provider\Template\PdfTemplate;
use Application\Service\Configuration\ConfigurationServiceAwareTrait;
use Application\Service\Macro\MacroServiceAwareTrait;
use Carriere\Service\Niveau\NiveauService;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Element\Entity\Db\ApplicationElement;
use Element\Entity\Db\Competence;
use Element\Entity\Db\CompetenceDiscipline;
use Element\Entity\Db\CompetenceElement;
use Element\Entity\Db\CompetenceType;
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
use FicheMetier\Provider\Parametre\FicheMetierParametres;
use FicheMetier\Service\FicheMetierMission\FicheMetierMissionServiceAwareTrait;
use FicheMetier\Service\MissionActivite\MissionActiviteServiceAwareTrait;
use FicheMetier\Service\MissionPrincipale\MissionPrincipaleServiceAwareTrait;
use Laminas\Mvc\Controller\AbstractController;
use Metier\Entity\Db\FamilleProfessionnelle;
use Metier\Entity\Db\Metier;
use Metier\Service\FamilleProfessionnelle\FamilleProfessionnelleServiceAwareTrait;
use Metier\Service\Metier\MetierServiceAwareTrait;
use Mpdf\MpdfException;
use Referentiel\Entity\Db\Referentiel;
use Referentiel\Service\Referentiel\ReferentielServiceAwareTrait;
use RuntimeException;
use UnicaenEtat\Service\EtatInstance\EtatInstanceServiceAwareTrait;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;
use UnicaenPdf\Exporter\PdfExporter;
use UnicaenRenderer\Service\Rendu\RenduServiceAwareTrait;

class FicheMetierService
{
    use ApplicationServiceAwareTrait;
    use ApplicationElementServiceAwareTrait;
    use CompetenceServiceAwareTrait;
    use CompetenceElementServiceAwareTrait;
    use ReferentielServiceAwareTrait;
    use ConfigurationServiceAwareTrait;
    use ProvidesObjectManager;
    use EtatInstanceServiceAwareTrait;
    use FamilleProfessionnelleServiceAwareTrait;
    use FicheMetierMissionServiceAwareTrait;
    use MacroServiceAwareTrait;
    use MissionActiviteServiceAwareTrait;
    use MissionPrincipaleServiceAwareTrait;
    use ParametreServiceAwareTrait;
    use RenduServiceAwareTrait;

    use HasApplicationCollectionServiceAwareTrait;
    use HasCompetenceCollectionServiceAwareTrait;
    use MetierServiceAwareTrait;

    use SelectionApplicationHydratorAwareTrait;
    use SelectionCompetenceHydratorAwareTrait;

    const REFERENS_SEP = "|";

    /** GESTION DES ENTITES *******************************************************************************************/

    public function create(FicheMetier $fiche): FicheMetier
    {
        $this->getObjectManager()->persist($fiche);
        $this->getObjectManager()->flush($fiche);
        return $fiche;
    }

    public function update(FicheMetier $fiche): FicheMetier
    {
        $this->getObjectManager()->flush($fiche);
        return $fiche;
    }

    public function historise(FicheMetier $fiche): FicheMetier
    {
        $fiche->historiser();
        $this->getObjectManager()->flush($fiche);
        return $fiche;
    }

    public function restore(FicheMetier $fiche): FicheMetier
    {
        $fiche->dehistoriser();
        $this->getObjectManager()->flush($fiche);
        return $fiche;
    }

    /**
     * @param FicheMetier $fiche
     * @return FicheMetier
     */
    public function delete(FicheMetier $fiche): FicheMetier
    {
        $this->getObjectManager()->remove($fiche);
        $this->getObjectManager()->flush($fiche);
        return $fiche;
    }

    /** REQUETAGE *****************************************************************************************************/

    public function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(FicheMetier::class)->createQueryBuilder('ficheMetier')
            ->addSelect('metier')->leftJoin('ficheMetier.metier', 'metier')
            ->addSelect('famille')->leftjoin('metier.famillesProfessionnelles', 'famille')
            ->addSelect('etat')->leftjoin('ficheMetier.etats', 'etat')
            ->addSelect('etype')->leftjoin('etat.type', 'etype')
            ->addSelect('referentiel')->leftJoin('ficheMetier.referentiel', 'referentiel');
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
        if (isset($filtre['referentiel']) and $filtre['referentiel'] != '') {
            $qb = $qb->andWhere('referentiel.id = :referentiel')->setParameter('referentiel', $filtre['referentiel']);
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
            throw new RuntimeException("Plusieurs fiches métiers portent le même identifiant [" . $id . "].", 0, $e);
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
    public function getFicheByFamilleProfessionnelle(FamilleProfessionnelle $famille): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('famille = :famille')->setParameter('famille', $famille)
            ->orderBy('metier.libelle');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getFichesMetiersAsOptionGroup(): array
    {
        $familles = $this->getFamilleProfessionnelleService()->getFamillesProfessionnelles();
        $options = [];

        foreach ($familles as $famille) {
            $optionsoptions = [];
            foreach ($this->getFicheByFamilleProfessionnelle($famille) as $fiche) {
                if ($fiche->estNonHistorise()) $optionsoptions[$fiche->getId()] = $fiche->getMetier()->getLibelle() . " (dernière modification " . $fiche->getHistoModification()->format("d/m/Y") . ")";
            }
            asort($optionsoptions);
            $array = [
                'label' => $famille->getLibelle(),
                'options' => $optionsoptions,
            ];
            $options[] = $array;
        }

        return $options;
    }

    public function getFicheMetierByReferentielAndCode(Referentiel $referentiel, string $code): ?FicheMetier
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('ficheMetier.referentiel = :referentiel')->setParameter('referentiel', $referentiel)
            ->andWhere('ficheMetier.reference = :code')->setParameter('code', $code);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [".FicheMetier::class."] partagent la même identification [".$referentiel->getLibelleCourt()."|".$code."]",-1,$e);
        }
        return $result;
    }

    /** FACADE ********************************************************************************************************/

    public function addMission(FicheMetier $ficheMetier, Mission $missionPrincipale): void
    {

        $fichemetierMission = new FicheMetierMission();
        $fichemetierMission->setFicheMetier($ficheMetier);
        $fichemetierMission->setMission($missionPrincipale);
        $fichemetierMission->setOrdre(9999);
        $this->getFicheMetierMissionService()->create($fichemetierMission);
    }

    public function moveMission(FicheMetier $ficheMetier, Mission $missionPrincipale, string $direction): void
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

    public function removeMission(FicheMetier $ficheMetier, Mission $missionPrincipale): void
    {
        $fichemetierMission = $this->getFicheMetierMissionService()->getFicherMetierMissionByFicheMetierAndMission($ficheMetier, $missionPrincipale);
        $this->getFicheMetierMissionService()->delete($fichemetierMission);
    }

    public function compressMission(FicheMetier $ficheMetier): void
    {
        $missions = $ficheMetier->getMissions();
        usort($missions, function (FicheMetierMission $a, FicheMetierMission $b) {
            return $a->getOrdre() <=> $b->getOrdre();
        });

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
    public function getCompetencesDictionnairesByType(FicheMetier $fiche, CompetenceType $type, bool $asElement = false): array
    {
        //todo faire mieux en requetant correctement
        $listing = $fiche->getCompetenceListe();
        $listing = array_filter($listing, function (CompetenceElement $element) use ($type) {
            $competence = $element->getCompetence();
            return $competence->getType() === $type;
        });

        $dictionnaire = [];

        foreach ($listing as $competenceElement) {
            $competence = ($asElement) ? $competenceElement : $competenceElement->getCompetence();
            $dictionnaire[$competence->getId()]["entite"] = $competence;
            $dictionnaire[$competence->getId()]["raison"][] = $fiche;
            $dictionnaire[$competence->getId()]["conserve"] = true;
        }

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

        return $dictionnaire;
    }

    /**
     * @param FicheMetier $fiche
     * @param bool $asElement
     * @return array
     */
    public function getCompetencesSpecifiquesDictionnaires(FicheMetier $fiche, bool $asElement = false): array
    {
        return [];
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
            $this->getObjectManager()->persist($activiteDuplicata);
            $this->getObjectManager()->flush($activiteDuplicata);
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

        $referens = $this->getReferentielService()->getReferentielByLibelleCourt('REFERENS3');
        if ($referens === null) {
            throw new RuntimeException("<strong>Aucun référentiel de compétence [REFERENS].</strong><br>Celui-ci est nécessaire pour l'import de fiche CSV ReferensIII.");
        }
        $competences = [];
        $competencesListe = [];
        foreach ($competences_ids as $competence_id) {
            $competence = $this->getCompetenceService()->getCompetenceByRefentiel($referens, $competence_id);
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

    public function exporter(?FicheMetier $fichemetier): string
    {
        $displayResume = $this->getParametreService()->getValeurForParametre(FicheMetierParametres::TYPE, FicheMetierParametres::DISPLAY_RESUME);
        $displayCode = $this->getParametreService()->getValeurForParametre(FicheMetierParametres::TYPE, FicheMetierParametres::CODE_FONCTION);
        $displayRaison = $this->getParametreService()->getValeurForParametre(FicheMetierParametres::TYPE, FicheMetierParametres::DISPLAY_RAISON);
        $displayMission = $this->getParametreService()->getValeurForParametre(FicheMetierParametres::TYPE, FicheMetierParametres::DISPLAY_MISSION);
        $displayApplications = $this->getParametreService()->getValeurForParametre(FicheMetierParametres::TYPE, FicheMetierParametres::DISPLAY_APPLICATION);
        $displayCompetences = $this->getParametreService()->getValeurForParametre(FicheMetierParametres::TYPE, FicheMetierParametres::DISPLAY_COMPETENCE);
        $displayCompetencesSpecifiques = $this->getParametreService()->getValeurForParametre(FicheMetierParametres::TYPE, FicheMetierParametres::DISPLAY_COMPETENCE_SPECIFIQUE);
        $displayContexte = $this->getParametreService()->getValeurForParametre(FicheMetierParametres::TYPE, FicheMetierParametres::DISPLAY_CONTEXTE);
        $displayTendance = $this->getParametreService()->getValeurForParametre(FicheMetierParametres::TYPE, FicheMetierParametres::DISPLAY_TENDANCE);

        $vars = [
            'fichemetier' => $fichemetier,
            'metier' => $fichemetier->getMetier(),
            'MacroService' => $this->getMacroService(),
            'DISPLAY_RESUME' => $displayResume,
            'DISPLAY_CODEFONCTION' => $displayCode,
            'DISPLAY_RAISON' => $displayRaison,
            'DISPLAY_MISSION' => $displayMission,
            'DISPLAY_APPLICATION' => $displayApplications,
            'DISPLAY_COMPETENCE' => $displayCompetences,
            'DISPLAY_COMPETENCE_SPECIFIQUE' => $displayCompetencesSpecifiques,
            'DISPLAY_CONTEXTE' => $displayContexte,
            'DISPLAY_TENDANCE' => $displayTendance,
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

    /** @return FicheMetier[] */
    public function getFichesMetiersByMetier(Metier $metier, ?string $raw = null): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('ficheMetier.metier = :metier')->setParameter('metier', $metier);
        if ($raw !== null) $qb->andWhere('ficheMetier.raw = :raw')->setParameter('raw', $raw);
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getFichesMetiersByDiscipline(?CompetenceDiscipline $discipline): array
    {
        $qb = $this->createQueryBuilder()
            ->join('ficheMetier.competences', 'competenceElement')->addSelect('competenceElement')
            ->join('competenceElement.competence', 'competence')->addSelect('competence')
            ->leftjoin('competence.discipline', 'discipline')->addSelect('discipline')
            ->andWhere('ficheMetier.histoDestruction IS NULL')
            ->orderby('metier.libelle');
        $qb = $qb->andWhere('competence.discipline = :discipline')->setParameter('discipline', $discipline);

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getFichesMetiersByDisciplines(array $disciplines): array
    {
        //note : si trop lent faire en dql ...
        $results = [];
        foreach ($disciplines as $discipline) {
            $results[$discipline->getId()] = $this->getFichesMetiersByDiscipline($discipline);
        }
        return $results;
    }

    /** @return FicheMetier[] */
    public function getFichesMetiersByCodeFonction(string $codeFonction): array
    {
        //todo
        // decomposer le code pour pouvoir récupérer le ou les codes associées

        // recuperer les listes ayant un de ces codes
        return [];
    }


}