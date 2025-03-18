<?php

namespace FichePoste\Service\Expertise;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use FichePoste\Entity\Db\Expertise;
use Laminas\Mvc\Controller\AbstractActionController;
use RuntimeException;

class ExpertiseService
{
    use ProvidesObjectManager;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param Expertise $expertise
     * @return Expertise
     */
    public function create(Expertise $expertise): Expertise
    {
        $this->getObjectManager()->persist($expertise);
        $this->getObjectManager()->flush($expertise);
        return $expertise;
    }

    /**
     * @param Expertise $expertise
     * @return Expertise
     */
    public function update(Expertise $expertise): Expertise
    {
        $this->getObjectManager()->flush($expertise);
        return $expertise;
    }

    /**
     * @param Expertise $expertise
     * @return Expertise
     */
    public function historise(Expertise $expertise): Expertise
    {
        $expertise->historiser();
        $this->getObjectManager()->flush($expertise);
        return $expertise;
    }

    /**
     * @param Expertise $expertise
     * @return Expertise
     */
    public function restore(Expertise $expertise): Expertise
    {
        $expertise->dehistoriser();
        $this->getObjectManager()->flush($expertise);
        return $expertise;
    }

    /**
     * @param Expertise $expertise
     * @return Expertise
     */
    public function delete(Expertise $expertise): Expertise
    {
        $this->getObjectManager()->remove($expertise);
        $this->getObjectManager()->flush($expertise);
        return $expertise;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(Expertise::class)->createQueryBuilder('expertise')
            ->addSelect('createur')->join('expertise.histoCreateur', 'createur')
            ->addSelect('modificateur')->join('expertise.histoModificateur', 'modificateur')
            ->addSelect('destructeur')->leftJoin('expertise.histoDestructeur', 'destructeur')
            ->addSelect('ficheposte')->leftJoin('expertise.ficheposte', 'ficheposte');
        return $qb;
    }

    /**
     * @param $id
     * @return Expertise
     */
    public function getExpertise($id): ?Expertise
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('expertise.id = :id')
            ->setParameter('id', $id);

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Expertise partagent le même id [" . $id . "]", 0, $e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $param
     * @return Expertise
     */
    public function getRequestedExpertise($controller, $param = 'expertise'): ?Expertise
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getExpertise($id);
        return $result;
    }
}
