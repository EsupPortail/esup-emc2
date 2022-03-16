<?php

namespace Element\Service\ApplicationElement;

use Application\Entity\Db\Agent;
use Element\Entity\Db\ApplicationElement;
use Doctrine\DBAL\Driver\Exception as DRV_Exception;
use Doctrine\DBAL\Exception as DBA_Exception;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;

class ApplicationElementService {
    use EntityManagerAwareTrait;
    
    /** Gestion des entites ***************************************************************************************/

    /**
     * @param ApplicationElement $element
     * @return ApplicationElement
     */
    public function create(ApplicationElement $element) : ApplicationElement
    {
        try {
            $this->getEntityManager()->persist($element);
            $this->getEntityManager()->flush($element);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $element;
    }

    /**
     * @param ApplicationElement $element
     * @return ApplicationElement
     */
    public function update(ApplicationElement $element) : ApplicationElement
    {
        try {
            $this->getEntityManager()->flush($element);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $element;
    }

    /**
     * @param ApplicationElement $element
     * @return ApplicationElement
     */
    public function historise(ApplicationElement $element) : ApplicationElement
    {
        try {
            $element->historiser();
            $this->getEntityManager()->flush($element);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $element;
    }

    /**
     * @param ApplicationElement $element
     * @return ApplicationElement
     */
    public function restore(ApplicationElement $element) : ApplicationElement
    {
        try {
            $element->dehistoriser();
            $this->getEntityManager()->flush($element);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $element;
    }

    /**
     * @param ApplicationElement $element
     * @return ApplicationElement
     */
    public function delete(ApplicationElement $element) : ApplicationElement
    {
        try {
            $this->getEntityManager()->remove($element);
            $this->getEntityManager()->flush($element);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $element;
    }

    /** REQUETAGE  ****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->getEntityManager()->getRepository(ApplicationElement::class)->createQueryBuilder('applicationelement')
            ->addSelect('application')->join('applicationelement.application', 'application')
;
        return $qb;
    }

    /**
     * @param int $id
     * @return ApplicationElement
     */
    public function getApplicationElement(int $id) : ApplicationElement
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('applicationelement.id = :id')
            ->setParameter('id', $id)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs ApplicationElement partagent le même id [".$id."]");
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $param
     * @return ApplicationElement
     */
    public function getRequestedApplicationElement(AbstractActionController $controller, string $param = "application-element")
    {
        $id = $controller->params()->fromRoute($param);
        return $this->getApplicationElement($id);
    }

    public function getApplicationElementsByAgent(Agent $agent) : array
    {

        $params = ['id' => $agent->getId(),];

        $sql = <<<EOS
select a.c_individu as agent_id, ai.id as application_id, ai.libelle, ae.commentaire as commantaire, ae.histo_destruction as historisation
from agent a
join agent_element_application aa on a.c_individu = aa.agent_id
join application_element ae on aa.application_element_id = ae.id
join application_informations ai on ae.application_id = ai.id
where a.c_individu=:id
EOS;

        $tmp = null;
        try {
            $res = $this->getEntityManager()->getConnection()->executeQuery($sql, $params);
            try {
                $tmp = $res->fetchAllAssociative();
            } catch (DRV_Exception $e) {
                throw new RuntimeException("Un problème est survenue lors de la récupération des applications liées à un individu", 0, $e);
            }
        } catch (DBA_Exception $e) {
            throw new RuntimeException("Un problème est survenue lors de la récupération des applications liées à un individu", 0, $e);
        }
        return $tmp;
    }

}