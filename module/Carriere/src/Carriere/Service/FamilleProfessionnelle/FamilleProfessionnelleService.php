<?php

namespace Carriere\Service\FamilleProfessionnelle;

use Carriere\Entity\Db\Correspondance;
use Carriere\Entity\Db\FamilleProfessionnelle;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Laminas\Mvc\Controller\AbstractActionController;
use RuntimeException;

class FamilleProfessionnelleService
{
    use ProvidesObjectManager;

    /** GESTION DES ENTITES *******************************************************************************************/

    public function create(FamilleProfessionnelle $famille): FamilleProfessionnelle
    {
        $this->getObjectManager()->persist($famille);

        if ($famille->getCorrespondance() and $famille->getPosition() === null) {
            $familles = $this->getFamillesProfessionnellesByCorrespondance($famille->getCorrespondance());
            $maximum = 0;
            foreach ($familles as $famille_) {
                if ($famille_->getPosition() and $famille_->getPosition() > $maximum) $maximum = $famille_->getPosition();
            }
            $famille->setPosition($maximum + 1);

        }
        $this->getObjectManager()->flush($famille);
        return $famille;
    }

    public function update(FamilleProfessionnelle $famille): FamilleProfessionnelle
    {
        $this->getObjectManager()->flush($famille);
        return $famille;
    }

    public function historise(FamilleProfessionnelle $famille): FamilleProfessionnelle
    {
        $famille->historiser();
        $this->getObjectManager()->flush($famille);
        return $famille;
    }

    public function restore(FamilleProfessionnelle $famille): FamilleProfessionnelle
    {
        $famille->dehistoriser();
        $this->getObjectManager()->flush($famille);
        return $famille;
    }

    public function delete(FamilleProfessionnelle $famille): FamilleProfessionnelle
    {
        $this->getObjectManager()->remove($famille);
        $this->getObjectManager()->flush($famille);
        return $famille;
    }

    /** REQUETAGE *****************************************************************************************************/

    public function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(FamilleProfessionnelle::class)->createQueryBuilder('famille')
            ->addSelect('correspondance')->leftJoin('famille.correspondance', 'correspondance');
        return $qb;
    }

    /** @return FamilleProfessionnelle[] */
    public function getFamillesProfessionnelles(): array
    {
        $qb = $this->createQueryBuilder()
            ->addOrderBy('famille.libelle');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** @return FamilleProfessionnelle[] */
    public function getFamillesProfessionnellesAsOptions(bool $historiser = false): array
    {
        $familles = $this->getFamillesProfessionnelles();
        $options = [];

        $sans = new Correspondance();
        $sans->setId(PHP_INT_MAX);
        $sans->setLibelleCourt("Sans");
        $sans->setLibelleLong("Sans correspondance");
        $sans->setCategorie("");

        $correspondances = [];
        $dictionnaires = [];
        foreach ($familles as $famille) {
            if ($historiser or $famille->estNonHistorise()) {
                $correspondance = $famille->getCorrespondance();
                if ($correspondance) {
                    $correspondances[$correspondance->getId()] = $correspondance;
                    $dictionnaires[$correspondance->getId()][] = $famille;
                } else {
                    $correspondances[$sans->getId()] = $sans;
                    $dictionnaires[$sans->getId()][] = $famille;
                }
            }
        }
        ksort($dictionnaires);

        foreach ($dictionnaires as $correspondanceId => $familles) {
            $optionsoptions = [];
            usort($familles, function (FamilleProfessionnelle $a, FamilleProfessionnelle $b) {
                return $a->getPosition() <=> $b->getPosition();
            });
            foreach ($familles as $famille) {
                $optionsoptions[$famille->getId()] = $this->optionify($famille);
            }
            asort($optionsoptions);
            $array = [
                'label' => $correspondances[$correspondanceId]->getLibelleLong(),
                'options' => $optionsoptions,
            ];
            $options[] = $array;
        }

        return $options;
    }

    public function getFamilleProfessionnelle(?int $id): ?FamilleProfessionnelle
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('famille.id = :id')
            ->setParameter('id', $id);

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [" . FamilleProfessionnelle::class . "] partagent le même identifiant [" . $id . "]", 0, $e);
        }
        return $result;
    }

    public function getRequestedFamilleProfessionnelle(AbstractActionController $controller, string $paramName = 'famille-professionnelle'): ?FamilleProfessionnelle
    {
        $id = $controller->params()->fromRoute($paramName);
        $famille = $this->getFamilleProfessionnelle($id);

        return $famille;
    }

    public function getFamilleProfessionnelleByLibelle(?string $libelle): ?FamilleProfessionnelle
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('famille.libelle = :libelle')->setParameter('libelle', $libelle)
            ->andWhere('famille.histoDestruction IS NULL');

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [" . FamilleProfessionnelle::class . "] partagent le même libellé [" . $libelle . "]", 0, $e);
        }
        return $result;
    }

    public function getFamilleProfessionnelleByPositionnement(?Correspondance $correspondance, $position): ?FamilleProfessionnelle
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('famille.correspondance = :correspondance')->setParameter('correspondance', $correspondance)
            ->andWhere('famille.position = :position')->setParameter('position', $position);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [" . FamilleProfessionnelle::class . "] famille professionnelle partagent le même positionnement [" . $correspondance?->getLibelleCourt() . "|" . $position . "]", -1, $e);
        }
        return $result;
    }

    /** @return FamilleProfessionnelle[] */
    public function getFamillesProfessionnellesWithFilter(array $params): array
    {
        $qb = $this->createQueryBuilder();
        if (isset($params['historisation'])) {
            if ($params['historisation'] === "1") {
                $qb = $qb->andWhere('famille.histoDestruction IS NOT NULL');
            }
            if ($params['historisation'] === "0") {
                $qb = $qb->andWhere('famille.histoDestruction IS NULL');
            }
        }
        if (isset($params['correspondance']) and $params['correspondance'] !== "") {
            if ($params['correspondance'] === "-1") {
                $qb = $qb->andWhere('famille.correspondance IS NULL');
            } else {
                $qb = $qb
                    ->andWhere('correspondance = :correspondance')->setParameter('correspondance', $params['correspondance']);
            }
        }

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** @return FamilleProfessionnelle[] */
    public function getFamillesProfessionnellesByCorrespondance(Correspondance $correspondance): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('famille.correspondance = :correspondance')->setParameter('correspondance', $correspondance);
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getFamilleProfessionnelleByCode(string $strFamilleProfessionnelle): ?FamilleProfessionnelle
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('concat(correspondance.categorie, famille.position) = :code')->setParameter('code', $strFamilleProfessionnelle);

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [" . FamilleProfessionnelle::class . "] partagent le même code (calculé) [" . $strFamilleProfessionnelle . "]", -1, $e);
        }
        return $result;
    }

    /** FACADE ********************************************************************************************************/

    public function optionify(FamilleProfessionnelle $familleProfessionnelle): array
    {
        $label = $familleProfessionnelle->getLibelle();
        if ($familleProfessionnelle->getCorrespondance()) {
            $label .= " <code>" . $familleProfessionnelle->getCorrespondance()->getCategorie() . "</code>";
        } else {
            $label .= "  <em style='color:grey'>Aucune spécialité</em> ";
        }
        if ($familleProfessionnelle->getPosition()) {
            $label .= "<code>" . $familleProfessionnelle->getPosition() . "</code>";
        } else {
            $label .= " <em style='color:grey'>Aucune position</em> ";
        }

        $this_option = [
            'value' => $familleProfessionnelle->getId(),
            'attributes' => [
                'data-content' =>
                    $label
            ],
            'label' => $familleProfessionnelle->getLibelle(),
        ];
        return $this_option;
    }


    public function createWith(string $familleLibelle, bool $persist = true): ?FamilleProfessionnelle
    {
        $famille = new FamilleProfessionnelle();
        $famille->setLibelle($familleLibelle);
        if ($persist) $this->create($famille);
        return $famille;
    }

    public function generateDictionnaire(string $discriminant): array
    {
        $familles = $this->getFamillesProfessionnelles();

        $dictionnaire = [];
        foreach ($familles as $famille) {
            $tabId = match ($discriminant) {
                'libelle' => $famille->getLibelle(),
                default => $famille->getId(),
            };
            $dictionnaire[$tabId] = $famille;
        }
        return  $dictionnaire;
    }


}
