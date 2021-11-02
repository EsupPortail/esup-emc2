<?php

namespace Application\Service\ApplicationElement;

use Application\Entity\Db\Agent;
use Application\Entity\Db\ApplicationElement;
use Application\Service\GestionEntiteHistorisationTrait;
use Doctrine\DBAL\Driver\Exception as DRV_Exception;
use Doctrine\DBAL\Exception as DBA_Exception;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use Zend\Mvc\Controller\AbstractActionController;

class ApplicationElementService {
    use GestionEntiteHistorisationTrait;
    
    /** Gestion des entites ***************************************************************************************/

    /**
     * @param ApplicationElement $element
     * @return ApplicationElement
     */
    public function create(ApplicationElement $element)
    {
        $this->createFromTrait($element);
        return $element;
    }

    /**
     * @param ApplicationElement $element
     * @return ApplicationElement
     */
    public function update(ApplicationElement $element)
    {
        $this->updateFromTrait($element);
        return $element;
    }

    /**
     * @param ApplicationElement $element
     * @return ApplicationElement
     */
    public function restore(ApplicationElement $element)
    {
        $this->restoreFromTrait($element);
        return $element;
    }

    /**
     * @param ApplicationElement $element
     * @return ApplicationElement
     */
    public function historise(ApplicationElement $element)
    {
        $this->historiserFromTrait($element);
        return $element;
    }

    /**
     * @param ApplicationElement $element
     * @return ApplicationElement
     */
    public function delete(ApplicationElement $element)
    {
        $this->deleteFromTrait($element);
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
join agent_application aa on a.c_individu = aa.agent_id
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