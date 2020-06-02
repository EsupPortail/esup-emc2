<?php

namespace Application\Service\EntretienProfessionnel;

use Application\Entity\Db\Agent;
use Application\Entity\Db\EntretienProfessionnel;
use Application\Service\GestionEntiteHistorisationTrait;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use Zend\Mvc\Controller\AbstractActionController;

class EntretienProfessionnelService {
//    use DateTimeAwareTrait;
//    use EntityManagerAwareTrait;
//    use UserServiceAwareTrait;
    use GestionEntiteHistorisationTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @return EntretienProfessionnel
     * @var EntretienProfessionnel $entretien
     */
    public function create($entretien)
    {
        $this->createFromTrait($entretien);
        return $entretien;
    }

    /**
     * @return EntretienProfessionnel
     * @var EntretienProfessionnel $entretien
     */
    public function update($entretien)
    {
        $this->updateFromTrait($entretien);
        return $entretien;
    }

    /**
     * @return EntretienProfessionnel
     * @var EntretienProfessionnel $entretien
     */
    public function historise($entretien)
    {
        $this->historiserFromTrait($entretien);
        return $entretien;
    }

    /**
     * @return EntretienProfessionnel
     * @var EntretienProfessionnel $entretien
     */
    public function restore($entretien)
    {
        $this->restoreFromTrait($entretien);
        return $entretien;
    }

    /**
     * @return EntretienProfessionnel
     * @var EntretienProfessionnel $entretien
     */
    public function delete($entretien)
    {
        $this->deleteFromTrait($entretien);
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
            ->addSelect('campagne')->join('entretien.campagne', 'campagne')
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
        $qb = $this->createQueryBuilder()
            ->orderBy('campagne.annee, agent.nomUsuel, agent.prenom');
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
