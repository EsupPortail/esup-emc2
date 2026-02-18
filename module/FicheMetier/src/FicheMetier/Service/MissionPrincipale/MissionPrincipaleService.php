<?php

namespace FicheMetier\Service\MissionPrincipale;

use Carriere\Entity\Db\FamilleProfessionnelle;
use Carriere\Entity\Db\NiveauEnveloppe;
use Carriere\Service\FamilleProfessionnelle\FamilleProfessionnelleServiceAwareTrait;
use Carriere\Service\Niveau\NiveauServiceAwareTrait;
use Doctrine\DBAL\Driver\Exception as DRV_Exception;
use Doctrine\DBAL\Exception as DBA_Exception;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use FicheMetier\Entity\Db\Mission;
use Laminas\Mvc\Controller\AbstractActionController;
use Referentiel\Entity\Db\Referentiel;
use RuntimeException;

class MissionPrincipaleService
{
    use ProvidesObjectManager;
    use FamilleProfessionnelleServiceAwareTrait;
    use NiveauServiceAwareTrait;

    /** GESTION DES ENTITES  ******************************************************************************************/

    public function create(Mission $mission): Mission
    {
        $this->getObjectManager()->persist($mission);
        $this->getObjectManager()->flush($mission);
        return $mission;
    }

    public function update(Mission $mission): Mission
    {
        $this->getObjectManager()->flush($mission);
        return $mission;
    }

    public function historise(Mission $mission): Mission
    {
        $mission->historiser();
        $this->getObjectManager()->flush($mission);
        return $mission;
    }

    public function restore(Mission $mission): Mission
    {
        $mission->dehistoriser();
        $this->getObjectManager()->flush($mission);
        return $mission;
    }

    public function delete(Mission $mission): Mission
    {
        $this->getObjectManager()->remove($mission);
        $this->getObjectManager()->flush($mission);
        return $mission;
    }

    /** QUERRYING *****************************************************************************************************/

    public function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(Mission::class)->createQueryBuilder('mission')
            ->leftJoin('mission.famillesProfessionnelles', 'famille')->addSelect('famille')
            ->leftJoin('mission.referentiel', 'referentiel')->addSelect('referentiel');
        return $qb;
    }

    /** @return Mission[] */
    public function getMissionsPrincipales(bool $withHisto = false, string $champ = 'libelle', string $ordre = 'ASC'): array
    {
        $qb = $this->createQueryBuilder()
            ->orderby('mission.' . $champ, $ordre);
        if (!$withHisto) $qb = $qb->andWhere('mission.histoDestruction IS NULL');
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getMissionPrincipale(?int $id): ?Mission
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('mission.id = :id')->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Mission partagent le même id [" . $id . "]", 0, $e);
        }
        return $result;
    }

    public function getRequestedMissionPrincipale(AbstractActionController $controller, string $param = 'mission-principale'): ?Mission
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getMissionPrincipale($id);
        return $result;
    }


    public function getMissionPrincipaleByLibelle(?string $libelle): ?Mission
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('mission.libelle = :libelle')->setParameter('libelle', $libelle);

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            // todo probablement ajouter la notion de référentiel ...
            throw new RuntimeException("Plusieurs [" . Mission::class . "] partagent le même libellé [" . $libelle . "]", -1, $e);
        }
        return $result;
    }

    public function getMissionPrincipaleByReference(Referentiel $referentiel, string $reference): ?Mission
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('mission.referentiel = :referentiel')->setParameter('referentiel', $referentiel)
            ->andWhere('mission.reference = :reference')->setParameter('reference', $reference);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [" . Mission::class . "] partagent la même référence [" . $referentiel->getLibelleCourt() . "-" . $reference . "]", -1, $e);
        }
        return $result;
    }

    /** @return Mission[] */
    public function getMissionsPrincipalesWithFiltre(array $params = []): array
    {
        $qb = $this->createQueryBuilder();
        if (isset($params['referentiel']) and $params['referentiel'] !== '') {
            $referentielId = $params['referentiel'];
            $qb = $qb->andWhere('referentiel.id = :referentiel')->setParameter('referentiel', $referentielId);
        }
        if (isset($params['famille']) and $params['famille'] !== '') {
            $familleId = $params['famille'];
            $qb = $qb->andWhere('famille.id = :famille')->setParameter('famille', $familleId);
        }
        if (isset($params['histo']) and $params['histo'] !== '') {
            if ($params['histo'] == 0) $qb = $qb->andWhere('mission.histoDestruction IS NULL');
            if ($params['histo'] == 1) $qb = $qb->andWhere('mission.histoDestruction IS NOT NULL');
        }

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** FACADE ********************************************************************************************************/


    /** @return Mission[] */
    public function findMissionsPrincipalesByExtendedTerm(string $texte): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere("LOWER(mission.libelle) like :search")
            ->andWhere('mission.histoDestruction IS NULL')
            ->setParameter('search', '%' . strtolower($texte) . '%');
        $result = $qb->getQuery()->getResult();

        return $result;
    }

    public function formatToJSON(array $missions): array
    {
        $result = [];
        /** @var Mission[] $missions */
        foreach ($missions as $mission) {
            $result[] = array(
                'id' => $mission->getId(),
                'label' => $mission->getLibelle(),
            );
        }
        usort($result, function ($a, $b) {
            return strcmp($a['label'], $b['label']);
        });
        return $result;
    }

    /** FACADE ********************************************************************************************************/

    public function createWith(string $intitule, Referentiel $referentiel, string $reference, bool $persist = true): ?Mission
    {
        $mission = new Mission();
        $mission->setLibelle($intitule);
        $mission->setReferentiel($referentiel);
        $mission->setReference($reference);
        if ($persist) $this->create($mission);

        return $mission;
    }

    public function getMissionsPrincipalesAsOptions(): array
    {
        $missions = $this->getMissionsPrincipales();

        $options = [];
        foreach ($missions as $mission) {
            $options[$mission->getId()] = $this->missionOptionify($mission);
        }
        return $options;
    }

    private function missionOptionify(Mission $mission): array
    {
//        $texte  = $mission->getLibelle();
        $texte = "<span class='libelle_mission shorten'>" . MissionPrincipaleService::tronquerTexte($mission->getLibelle(), 100) . "</span>";
        $texte .= "<span class='libelle_mission full' style='display: none'>" . $mission->getLibelle() . "</span>";
        $description = null;

        $texte = "<span class='mission' title='" . ($description ?? "Aucune description") . "' class='badge btn-danger'>" . $texte;

        if ($mission->getCodesFicheMetier() !== null) {
            $texte .= "&nbsp;" . "<span class='badge'>" .
                $mission->getCodesFicheMetier()
                . "</span>";
        }

        $texte .= "<span class='description' style='display: none' onmouseenter='alert(event.target);'>" . ($description ?? "Aucune description") . "</span>"
            . "</span>";

        $this_option = [
            'value' => $mission->getId(),
            'attributes' => [
                'data-content' => $texte
            ],
            'label' => $texte,
        ];
        return $this_option;
    }

    static public function tronquerTexte(?string $texte, int $limite = 80): string
    {
        if ($texte === null) return "";
        // Si le texte est plus court que la limite, on ne fait rien
        if (mb_strlen($texte) <= $limite) {
            return $texte;
        }

        // On tronque à la limite sans couper un mot
        $texteTronque = mb_substr($texte, 0, $limite);

        // Si on a coupé au milieu d’un mot, on recule jusqu’au dernier espace
        $dernierEspace = mb_strrpos($texteTronque, ' ');
        if ($dernierEspace !== false) {
            $texteTronque = mb_substr($texteTronque, 0, $dernierEspace);
        }

        return rtrim($texteTronque) . ' ...';
    }

    public function generateDictionnaireFicheMetier(): array
    {
        $sql = <<<EOS
select me.mission_id, count (DISTINCT fmm.fichemetier_id) as count
from fichemetier_mission fmm
join missionprincipale_element me on me.id = fmm.mission_element_id
join fichemetier fm on fm.id = fmm.fichemetier_id
where me.histo_destruction IS NULL and fm.histo_destruction IS NULL
group by me.mission_id
EOS;

        try {
            $params = [];
            $res = $this->getObjectManager()->getConnection()->executeQuery($sql, $params);
            try {
                $tmp = $res->fetchAllAssociative();
            } catch (DRV_Exception $e) {
                throw new RuntimeException("[DRV] Un problème est survenu lors du calcul du dictionnaire", 0, $e);
            }
        } catch (DBA_Exception $e) {
            throw new RuntimeException("[DBA] Un problème est survenu lors du calcul du dictionnaire", 0, $e);
        }

        $dictionnaire = [];
        foreach ($tmp as $item) {
            $dictionnaire[$item['mission_id']] = $item['count'];
        }
        return $dictionnaire;
    }
}