<?php

namespace Application\Service\EntretienProfessionnel;

use Application\Entity\Db\Agent;
use Application\Entity\Db\EntretienProfessionnel;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenUtilisateur\Entity\DateTimeAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;

class EntretienProfessionnelService {
    use DateTimeAwareTrait;
    use EntityManagerAwareTrait;
    use UserServiceAwareTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @return EntretienProfessionnel
     * @var EntretienProfessionnel $entretien
     */
    public function create($entretien)
    {
        $date = $this->getDateTime();
        $user = $this->getUserService()->getConnectedUser();

        $entretien->setHistoCreation($date);
        $entretien->setHistoCreateur($user);
        $entretien->setHistoModification($date);
        $entretien->setHistoModificateur($user);

        try {
            $this->getEntityManager()->persist($entretien);
            $this->getEntityManager()->flush($entretien);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'enregistrement d'une EntretienProfessionnel", $e);
        }
        return $entretien;
    }

    /**
     * @return EntretienProfessionnel
     * @var EntretienProfessionnel $entretien
     */
    public function update($entretien)
    {
        $date = $this->getDateTime();
        $user = $this->getUserService()->getConnectedUser();

        $entretien->setHistoModification($date);
        $entretien->setHistoModificateur($user);

        try {
            $this->getEntityManager()->flush($entretien);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'enregistrement d'une EntretienProfessionnel", $e);
        }
        return $entretien;
    }

    /**
     * @return EntretienProfessionnel
     * @var EntretienProfessionnel $entretien
     */
    public function historise($entretien)
    {
        $date = $this->getDateTime();
        $user = $this->getUserService()->getConnectedUser();

        $entretien->setHistoDestruction($date);
        $entretien->setHistoDestructeur($user);

        try {
            $this->getEntityManager()->flush($entretien);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'enregistrement d'une EntretienProfessionnel", $e);
        }
        return $entretien;
    }

    /**
     * @return EntretienProfessionnel
     * @var EntretienProfessionnel $entretien
     */
    public function restore($entretien)
    {
        $date = $this->getDateTime();
        $user = $this->getUserService()->getConnectedUser();

        $entretien->setHistoModification($date);
        $entretien->setHistoModificateur($user);
        $entretien->setHistoDestruction(null);
        $entretien->setHistoDestructeur(null);

        try {
            $this->getEntityManager()->flush($entretien);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'enregistrement d'une EntretienProfessionnel", $e);
        }
        return $entretien;
    }

    /**
     * @return EntretienProfessionnel
     * @var EntretienProfessionnel $entretien
     */
    public function delete($entretien)
    {
        try {
            $this->getEntityManager()->remove($entretien);
            $this->getEntityManager()->flush();
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'effacement d'une EntretienProfessionnel", $e);
        }
        return $entretien;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder()
    {
        $qb = $this->getEntityManager()->getRepository(EntretienProfessionnel::class)->createQueryBuilder('entretien')
            ->addSelect('agent')->join('entretien.agent', 'agent')
            ->addSelect('responsable')->join('entretien.responsable', 'responsable')
            ->addSelect('formulaireInstance')->join('entretien.formulaireInstance', 'formulaireInstance')
            ->addSelect('reponse')->leftJoin('formulaireInstance.reponses', 'reponse')
            ->addSelect('formulaire')->join('formulaireInstance.formulaire', 'formulaire')
            ->addSelect('categorie')->join('formulaire.categories', 'categorie')
            ->addSelect('champ')->join('categorie.champs', 'champ')
        ;
        return $qb;
    }

    /**
     * @return EntretienProfessionnel[]
     */
    public function getEntretiensProfessionnels()
    {
        $qb = $this->getEntityManager()->getRepository(EntretienProfessionnel::class)->createQueryBuilder('entretien')
            ->orderBy('entretien.id');
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param integer $id
     * @return EntretienProfessionnel
     */
    public function getEntretienProfessionnel($id)
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('entretien.id = :id')
            ->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs EntretienProfessionnel partagent le même identifiant [".$id."]", $e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $paramName
     * @return EntretienProfessionnel
     */
    public function getRequestedEntretienProfessionnel($controller, $paramName)
    {
        $id = $controller->params()->fromRoute($paramName);
        $entretien = $this->getEntretienProfessionnel($id);
        return $entretien;
    }

    /**
     * @param Agent $agent
     * @return EntretienProfessionnel[]
     */
    public function getEntretiensProfessionnelsParAgent($agent)
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('entretien.agent = :agent')
            ->setParameter('agent', $agent)
            ->orderBy('entretien.annee, entretien.id', 'ASC')
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param EntretienProfessionnel $entretien
     * @return EntretienProfessionnel
     */
    public function getPreviousEntretienProfessionnel(EntretienProfessionnel $entretien)
    {
        $agent = $entretien->getAgent();
        $date = $entretien->getDateEntretien();

        $qb = $this->createQueryBuilder()
            ->andWhere('entretien.agent = :agent')
            ->andWhere('entretien.dateEntretien < :date')
            ->setParameter('agent', $agent)
            ->setParameter('date', $date)
            ->orderBy('entretien.dateEntretien', 'DESC');
        $result = $qb->getQuery()->getResult();

        if ($result === null) return null;
        return $result[0];
    }
}
