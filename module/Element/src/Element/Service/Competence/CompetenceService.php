<?php

namespace Element\Service\Competence;

use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Element\Entity\Db\Competence;
use Element\Entity\Db\CompetenceReferentiel;
use Element\Entity\Db\CompetenceTheme;
use Element\Entity\Db\CompetenceType;
use Element\Service\CompetenceTheme\CompetenceThemeServiceAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;
use RuntimeException;

class CompetenceService
{
    use ProvidesObjectManager;
    use CompetenceThemeServiceAwareTrait;

    /** COMPETENCES : ENTITY ******************************************************************************************/

    public function create(Competence $competence): Competence
    {
        $this->getObjectManager()->persist($competence);
        $this->getObjectManager()->flush($competence);
        return $competence;
    }

    public function update(Competence $competence): Competence
    {
        $this->getObjectManager()->flush($competence);
        return $competence;
    }

    public function historise(Competence $competence): Competence
    {
        $competence->historiser();
        $this->getObjectManager()->flush($competence);
        return $competence;
    }

    public function restore(Competence $competence): Competence
    {
        $competence->dehistoriser();
        $this->getObjectManager()->flush($competence);
        return $competence;
    }

    public function delete(Competence $competence): Competence
    {
        $this->getObjectManager()->remove($competence);
        $this->getObjectManager()->flush($competence);
        return $competence;
    }

    /** COMPETENCES : REQUETAGE ***************************************************************************************/

    public function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(Competence::class)->createQueryBuilder('competence')
            ->addSelect('type')->leftJoin('competence.type', 'type')
            ->addSelect('theme')->leftJoin('competence.theme', 'theme');
        return $qb;
    }

    /** Competence[] */
    public function getCompetences(string $champ = 'libelle', string $order = 'ASC'): array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('competence.' . $champ, $order);
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getCompetencesByTypes(): array
    {
        $competences = $this->getCompetences();

        $array = [];
        foreach ($competences as $competence) {
            $libelle = $competence->getType() ? $competence->getType()->getLibelle() : "Sans type";
            $array[$libelle][] = $competence;
        }
        return $array;
    }

    public function getCompetence(?int $id): ?Competence
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('competence.id = :id')
            ->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (ORMException $e) {
            throw new RuntimeException("Plusieurs Competence partagent le même id [" . $id . "]", 0, $e);
        }
        return $result;
    }

    public function getRequestedCompetence(AbstractActionController $controller, string $paramName = 'competence'): ?Competence
    {
        $id = $controller->params()->fromRoute($paramName);
        $competence = $this->getCompetence($id);
        return $competence;
    }

    public function getCompetencesAsGroupOptions(): array
    {
        $competences = $this->getCompetences();
        $dictionnaire = [];
        foreach ($competences as $competence) {
            $libelle = ($competence->getTheme()) ? $competence->getTheme()->getLibelle() : "Sans Thèmes";
            $dictionnaire[$libelle][] = $competence;
        }
        ksort($dictionnaire);

        $options = [];
        foreach ($dictionnaire as $clef => $listing) {
            $optionsoptions = [];
            usort($listing, function (Competence $a, Competence $b) {
                return $a->getLibelle() <=> $b->getLibelle();
            });

            foreach ($listing as $competence) {
                $optionsoptions[$competence->getId()] = $this->competenceOptionify($competence);
            }

            $options[] = [
                'label' => $clef,
                'options' => $optionsoptions,
            ];
        }
        return $options;
    }

    private function competenceOptionify(Competence $competence): array
    {
        /** @var CompetenceReferentiel $referentiel */
        $referentiel = $competence->getReferentiel();
        $type = $competence->getType();
        $texte = $competence->getLibelle();
        $this_option = [
            'value' => $competence->getId(),
            'attributes' => [
                'data-content' => "<span class='competence' title='".($competence->getDescription()??"Aucune description")."' class='badge btn-danger'>" . $texte
                    . "&nbsp;" . "<span class='badge'>"
                    . (($type !== null) ? $type->getLibelle() : "Sans type")
                    . "</span>"
                    . "&nbsp;" . "<span class='badge' style='background: " . (($referentiel !== null) ? $referentiel->getCouleur() : "gray") . "'>"
                    . (($referentiel !== null) ? $referentiel->getLibelleCourt() : "Sans référentiel") . " - " . $competence->getIdSource()
                    . "</span>"
                    . "<span class='description' style='display: none' onmouseenter='alert(event.target);'>".($competence->getDescription()??"Aucune description")."</span>"
                    ."</span>",
            ],
            'label' => $texte,
        ];
        return $this_option;
    }

    public function getCompetenceByIdSource(string $source, string $id): ?Competence
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('competence.source = :source')
            ->setParameter('source', $source)
            ->andWhere('competence.idSource = :id')
            ->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs compétences partagent le même id source [" . $source . "-" . $id . "]", 0, $e);
        }
        return $result;
    }

    /** @return Competence[] */
    public function getCompetencesByRefentiel(?CompetenceReferentiel $referentiel): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('competence.referentiel = :referentiel')
            ->setParameter('referentiel', $referentiel);
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getCompetenceByRefentiel(CompetenceReferentiel $referentiel, string $id)
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('competence.referentiel = :referentiel')
            ->setParameter('referentiel', $referentiel)
            ->andWhere('competence.idSource = :id')
            ->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs compétences partagent le même id referentiel [" . $referentiel->getId() . "-" . $id . "]", 0, $e);
        }
        return $result;
    }

    /** @return Competence[] */
    public function getCompetencesByTerm(string $term): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('LOWER(competence.libelle) like :search')
            ->setParameter('search', '%' . strtolower($term) . '%');
        $result = $qb->getQuery()->getResult();

        $competences = [];
        /** @var Competence $item */
        foreach ($result as $item) {
            $competences[$item->getId()] = $item;
        }
        return $competences;
    }

    /** FACADE ****************************************************************************************************/

    public function createWith(string $libelle, ?string $description, ?CompetenceType $type, ?CompetenceTheme $theme, CompetenceReferentiel $referentiel, string $id, bool $persist = true): Competence
    {
        if ($id === -1) {
            $id = $this->getCompetenceMaxIdByRefentiel($referentiel) + 1;
        }

        $competence = new Competence();
        $competence->setLibelle($libelle);
        $competence->setDescription($description);
        $competence->setType($type);
        $competence->setTheme($theme);
        $competence->setReferentiel($referentiel);
        $competence->setIdSource($id);
        if ($persist) $this->create($competence);
        return $competence;
    }

    public function updateWith(Competence $competence, string $libelle, ?string $description, ?CompetenceType $type, ?CompetenceTheme $theme): Competence
    {
        $wasModified = false;
        if ($competence->getLibelle() !== $libelle) {
            $competence->setLibelle($libelle);
            $wasModified = true;
        }
        if ($competence->getDescription() !== $description) {
            $competence->setDescription($description);
            $wasModified = true;
        }
        if ($competence->getType() !== $type) {
            $competence->setType($type);
            $wasModified = true;
        }
        if ($competence->getTheme() !== $theme) {
            $competence->setTheme($theme);
            $wasModified = true;
        }

        if ($wasModified) $this->update($competence);
        return $competence;
    }

    public function getCompetenceByRefentielAndLibelle(?CompetenceReferentiel $referentiel, ?string $libelle): ?Competence
    {
        if ($referentiel === null) return null;
        if ($libelle === null) return null;
        $qb = $this->createQueryBuilder()
            ->andWhere('competence.referentiel = :referentiel')->setParameter('referentiel', $referentiel)
            ->andWhere('competence.libelle = :libelle')->setParameter('libelle', $libelle)
            ->andWhere('competence.histoDestruction IS NULL');
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [" . Competence::class . "] partagent le même référentiel [" . $referentiel->getLibelleCourt() . "] et le même libellé [" . $libelle . "]", 0, $e);
        }
        return $result;
    }

    private function getCompetenceMaxIdByRefentiel(CompetenceReferentiel $referentiel)
    {
        $competences = $this->getCompetencesByRefentiel($referentiel);
        $max = 0;
        foreach ($competences as $competence) {
            $max = max($max, $competence->getId());
        }
        return $max;
    }


    /**
     * @param Competence[] $competences
     * @return array
     */
    public function formatCompetencesJSON(array $competences): array
    {
        $result = [];
        foreach ($competences as $competence) {
            $referentiel = $competence->getReferentiel();
            $result[] = array(
                'id' => $competence->getId(),
                'label' => $competence->getLibelle(),
                'extra' => ($referentiel === null) ? "<span class='badge' style='background-color: slategray;'>Aucun référentiel</span>" : "<span class='badge' style='background-color: " . $referentiel->getCouleur() . ";'>" . $referentiel->getLibelleCourt() . " #" . $competence->getIdSource() . "</span>",
            );
        }
        usort($result, function ($a, $b) {
            return strcmp($a['label'], $b['label']);
        });
        return $result;
    }
}