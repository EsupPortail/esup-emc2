<?php

namespace UnicaenGlossaire\Service\Definition;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use UnicaenGlossaire\Entity\Db\Definition;
use UnicaenUtilisateur\Service\GestionEntiteHistorisationTrait;
use Zend\Mvc\Controller\AbstractActionController;

class DefinitionService {
    use GestionEntiteHistorisationTrait;

    /** Gestion des entités *******************************************************************************************/

    /**
     * @param Definition $definition
     * @return Definition
     */
    public function create(Definition $definition) : Definition
    {
        $this->createFromTrait($definition);
        return $definition;
    }

    /**
     * @param Definition $definition
     * @return Definition
     */
    public function update(Definition $definition) : Definition
    {
        $this->updateFromTrait($definition);
        return $definition;
    }

    /**
     * @param Definition $definition
     * @return Definition
     */
    public function historise(Definition $definition) : Definition
    {
        $this->historiserFromTrait($definition);
        return $definition;
    }

    /**
     * @param Definition $definition
     * @return Definition
     */
    public function restore(Definition $definition) : Definition
    {
        $this->restoreFromTrait($definition);
        return $definition;
    }

    /**
     * @param Definition $definition
     * @return Definition
     */
    public function delete(Definition $definition) : Definition
    {
        $this->deleteFromTrait($definition);
        return $definition;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->getEntityManager()->getRepository(Definition::class)->createQueryBuilder('definition');
        return $qb;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @return Definition[]
     */
    public function getDefinitions(string $champ = 'terme', string $ordre = 'ASC') : array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('definition.' . $champ, $ordre);
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param integer $id
     * @return Definition|null
     */
    public function getDefinition(int $id) : ?Definition
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('definition.id = :id')
            ->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Definition partagent le même id [".$id."]",0,$e);
        }
        return $result;
    }

    /**
     * @param string $terme
     * @return Definition|null
     */
    public function getDefinitionByTerme(string $terme) : ?Definition
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('definition.terme = :terme')
            ->setParameter('terme', $terme);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Definition partagent le même terme [".$terme."]",0,$e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $param
     * @return Definition|null
     */
    public function getRequestedDefinition(AbstractActionController $controller, string $param='definition') : ?Definition
    {
        $id = $controller->params()->fromRoute($param);
        return $this->getDefinition($id);
    }

}