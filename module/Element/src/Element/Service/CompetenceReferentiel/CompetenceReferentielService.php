<?php

namespace Element\Service\CompetenceReferentiel;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Element\Entity\Db\CompetenceReferentiel;
use Laminas\Mvc\Controller\AbstractActionController;
use RuntimeException;

class CompetenceReferentielService
{
    use ProvidesObjectManager;

    /** ENTITY MANAGMENT **********************************************************************************************/

    public function create(CompetenceReferentiel $referentiel): CompetenceReferentiel
    {
        $this->getObjectManager()->persist($referentiel);
        $this->getObjectManager()->flush($referentiel);
        return $referentiel;
    }

    public function update(CompetenceReferentiel $referentiel): CompetenceReferentiel
    {
        $this->getObjectManager()->flush($referentiel);
        return $referentiel;
    }

    public function historise(CompetenceReferentiel $referentiel): CompetenceReferentiel
    {
        $referentiel->historiser();
        $this->getObjectManager()->flush($referentiel);
        return $referentiel;
    }

    public function restore(CompetenceReferentiel $referentiel): CompetenceReferentiel
    {
        $referentiel->dehistoriser();
        $this->getObjectManager()->flush($referentiel);
        return $referentiel;
    }

    public function delete(CompetenceReferentiel $referentiel): CompetenceReferentiel
    {
        $this->getObjectManager()->remove($referentiel);
        $this->getObjectManager()->flush($referentiel);
        return $referentiel;
    }

    /** REQUETE *******************************************************************************************************/

    public function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(CompetenceReferentiel::class)->createQueryBuilder('referentiel')
            ->addSelect('competence')->leftJoin('referentiel.competences', 'competence');
        return $qb;
    }

    /** @return CompetenceReferentiel[] */
    public function getCompetencesReferentiels(string $champ = 'libelleLong', string $order = 'ASC'): array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('referentiel.' . $champ, $order);
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** @return string[] */
    public function getCompetencesReferentielsAsOptions(string $champ = 'libelleLong', string $order = 'ASC'): array
    {
        $referentiels = $this->getCompetencesReferentiels($champ, $order);
        $options = [];
        foreach ($referentiels as $referentiel) {
//            $options[$referentiel->getId()] = $referentiel->getLibelleLong(); //todo optionify
            $options[$referentiel->getId()] = $this->optionify($referentiel);
        }
        return $options;
    }

    public function getCompetenceReferentiel(?int $id): ?CompetenceReferentiel
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('referentiel.id = :id')
            ->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs CompetenceReferentiel partagent le même id [" . $id . "]", $e);
        }
        return $result;
    }

    public function getCompetenceReferentielByCode(?string $code): ?CompetenceReferentiel
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('referentiel.libelleCourt = :code')
            ->setParameter('code', $code);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs CompetenceReferentiel partagent le même code [" . $code . "]", $e);
        }
        return $result;
    }

    public function getRequestedCompetenceReferentiel(AbstractActionController $controller, string $paramName = 'competence-referentiel'): ?CompetenceReferentiel
    {
        $id = $controller->params()->fromRoute($paramName);
        $referentiel = $this->getCompetenceReferentiel($id);
        return $referentiel;
    }

    /** FACADE ************************************************************************ */

    public function optionify(CompetenceReferentiel $referentiel): array
    {
        $this_option = [
            'value' => $referentiel->getId(),
            'attributes' => [
                'data-content' =>
                    "<span class='badge' style='background:" . $referentiel->getCouleur() . ";'>" . $referentiel->getLibelleCourt() . "</span> " . $referentiel->getLibelleLong(),
            ],
            'label' => $referentiel->getLibelleCourt(),
        ];
        return $this_option;
    }

}
