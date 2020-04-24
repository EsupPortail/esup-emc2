<?php

namespace Application\Service\Expertise;

use Application\Entity\Db\Expertise;
use Application\Service\GestionEntiteHistorisationTrait;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use Zend\Mvc\Controller\AbstractActionController;

class ExpertiseService {
//    use EntityManagerAwareTrait;
//    use UserServiceAwareTrait;
//    use DateTimeAwareTrait;
    use GestionEntiteHistorisationTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param Expertise $expertise
     * @return Expertise
     */
    public function create(Expertise $expertise)
    {
        $this->createFromTrait($expertise);
        return $expertise;
    }

    /**
     * @param Expertise $expertise
     * @return Expertise
     */
    public function update(Expertise $expertise)
    {
        $this->updateFromTrait($expertise);
        return $expertise;
    }

    /**
     * @param Expertise $expertise
     * @return Expertise
     */
    public function historise(Expertise $expertise)
    {
        $this->historiserFromTrait($expertise);
        return $expertise;
    }

    /**
     * @param Expertise $expertise
     * @return Expertise
     */
    public function restore(Expertise $expertise)
    {
        $this->restoreFromTrait($expertise);
        return $expertise;
    }

    /**
     * @param Expertise $expertise
     * @return Expertise
     */
    public function delete(Expertise $expertise)
    {
        $this->deleteFromTrait($expertise);
        return $expertise;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder()
    {
        $qb = $this->getEntityManager()->getRepository(Expertise::class)->createQueryBuilder('expertise')
            ->addSelect('createur')->join('expertise.histoCreateur', 'createur')
            ->addSelect('modificateur')->join('expertise.histoModificateur', 'modificateur')
            ->addSelect('destructeur')->leftJoin('expertise.histoDestructeur', 'destructeur')
            ->addSelect('ficheposte')->leftJoin('expertise.$ficheposte', 'ficheposte')
        ;
        return $qb;
    }

    /**
     * @param $id
     * @return Expertise
     */
    public function getExpertise($id)
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('expertise.id = :id')
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Expertise partagent le même id [".$id."]", 0, $e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $param
     * @return Expertise
     */
    public function getRequestedExpertise($controller, $param = 'expertise')
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getExpertise($id);
        return $result;
    }
}
