<?php

namespace Element\Service\ApplicationElement;

use Application\Entity\Db\Agent;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Element\Entity\Db\Application;
use Element\Entity\Db\ApplicationElement;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use FicheMetier\Entity\Db\FicheMetier;
use Laminas\Mvc\Controller\AbstractActionController;
use RuntimeException;

class ApplicationElementService {
    use ProvidesObjectManager;
    
    /** Gestion des entites ***************************************************************************************/

    public function create(ApplicationElement $element) : ApplicationElement
    {
        $this->getObjectManager()->persist($element);
        $this->getObjectManager()->flush($element);
        return $element;
    }

    public function update(ApplicationElement $element) : ApplicationElement
    {
        $this->getObjectManager()->flush($element);
        return $element;
    }

    public function historise(ApplicationElement $element) : ApplicationElement
    {
        $element->historiser();
        $this->getObjectManager()->flush($element);
        return $element;
    }

    public function restore(ApplicationElement $element) : ApplicationElement
    {
        $element->dehistoriser();
        $this->getObjectManager()->flush($element);
        return $element;
    }

    public function delete(ApplicationElement $element) : ApplicationElement
    {
        $this->getObjectManager()->remove($element);
        $this->getObjectManager()->flush($element);
        return $element;
    }

    /** REQUETAGE  ****************************************************************************************************/

    public function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(ApplicationElement::class)->createQueryBuilder('applicationelement')
            ->addSelect('application')->join('applicationelement.application', 'application')
;
        return $qb;
    }

    public function getApplicationElement(int $id) : ?ApplicationElement
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('applicationelement.id = :id')
            ->setParameter('id', $id)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs ApplicationElement partagent le même id [".$id."]", 0 , $e);
        }
        return $result;
    }

    public function getRequestedApplicationElement(AbstractActionController $controller, string $param = "application-element") : ?ApplicationElement
    {
        $id = $controller->params()->fromRoute($param);
        return $this->getApplicationElement($id);
    }

    /** FACADE ********************************************************************************************************/

    /** @return Agent[] */
    public function getAgentsHavingApplicationFromAgent(Application $application): array
    {
        $qb = $this->getObjectManager()->getRepository(Agent::class)->createQueryBuilder("agent")
            ->join('agent.applications', 'applicationelement')->addSelect('applicationelement')
            ->andWhere('applicationelement.application = :application')->setParameter('application', $application)
            ->andWhere('applicationelement.histoDestruction IS NULL')
        ;
        return $qb->getQuery()->getResult();
    }

    /** @return FicheMetier[] */
    public function getFicheMetierHavingApplication(Application $application): array
    {
        $qb = $this->getObjectManager()->getRepository(FicheMetier::class)->createQueryBuilder("fichemetier")
            ->join('fichemetier.applications', 'applicationelement')->addSelect('applicationelement')
            ->andWhere('applicationelement.application = :application')->setParameter('application', $application)
            ->andWhere('applicationelement.histoDestruction IS NULL')
        ;
        return $qb->getQuery()->getResult();
    }
}