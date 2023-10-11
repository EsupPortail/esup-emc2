<?php

namespace Element\Service\CompetenceReferentiel;

use Doctrine\ORM\Exception\NotSupported;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Element\Entity\Db\CompetenceReferentiel;
use Laminas\Mvc\Controller\AbstractActionController;
use RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;

class CompetenceReferentielService
{
    use EntityManagerAwareTrait;

    /** ENTITY MANAGMENT **********************************************************************************************/

    public function create(CompetenceReferentiel $referentiel): CompetenceReferentiel
    {
        try {
            $this->getEntityManager()->persist($referentiel);
            $this->getEntityManager()->flush($referentiel);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $referentiel;
    }

    public function update(CompetenceReferentiel $referentiel): CompetenceReferentiel
    {
        try {
            $this->getEntityManager()->flush($referentiel);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $referentiel;
    }

    public function historise(CompetenceReferentiel $referentiel): CompetenceReferentiel
    {
        try {
            $referentiel->historiser();
            $this->getEntityManager()->flush($referentiel);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $referentiel;
    }

    public function restore(CompetenceReferentiel $referentiel): CompetenceReferentiel
    {
        try {
            $referentiel->dehistoriser();
            $this->getEntityManager()->flush($referentiel);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $referentiel;
    }

    public function delete(CompetenceReferentiel $referentiel): CompetenceReferentiel
    {
        try {
            $this->getEntityManager()->remove($referentiel);
            $this->getEntityManager()->flush($referentiel);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $referentiel;
    }

    /** REQUETE *******************************************************************************************************/

    public function createQueryBuilder(): QueryBuilder
    {
        try {
            $qb = $this->getEntityManager()->getRepository(CompetenceReferentiel::class)->createQueryBuilder('referentiel')
                ->addSelect('competence')->leftJoin('referentiel.competences', 'competence');
        } catch (NotSupported $e) {
            throw new RuntimeException("Un problème est survenu lors de la création du QueryBuilder [" . CompetenceReferentiel::class . "]", 0, $e);
        }
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

    public function getRequestedCompetenceReferentiel(AbstractActionController $controller, string $paramName = 'competence-referentiel'): ?CompetenceReferentiel
    {
        $id = $controller->params()->fromRoute($paramName);
        $referentiel = $this->getCompetenceReferentiel($id);
        return $referentiel;
    }

    /** FACADE ************************************************************************ */

    public function optionify(CompetenceReferentiel $referentiel) : array
    {
        $this_option = [
            'value' =>  $referentiel->getId(),
            'attributes' => [
                'data-content' =>
                    "<span class='badge' style='background:".$referentiel->getCouleur().";'>".$referentiel->getLibelleCourt()."</span> ".$referentiel->getLibelleLong(),
            ],
            'label' => $referentiel->getLibelleCourt(),
        ];
        return $this_option;
    }

}
