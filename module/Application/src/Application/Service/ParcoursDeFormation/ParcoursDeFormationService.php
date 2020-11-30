<?php

namespace Application\Service\ParcoursDeFormation;

use Application\Entity\Db\Categorie;
use Application\Entity\Db\FicheMetier;
use Application\Entity\Db\FichePoste;
use Application\Entity\Db\Metier;
use Application\Entity\Db\ParcoursDeFormation;
use Application\Entity\Db\ParcoursDeFormationFormation;
use Application\Service\Categorie\CategorieServiceAwareTrait;
use Application\Service\GestionEntiteHistorisationTrait;
use Application\Service\Metier\MetierServiceAwareTrait;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use Formation\Entity\Db\Formation;
use UnicaenApp\Exception\RuntimeException;
use Zend\Mvc\Controller\AbstractActionController;

class ParcoursDeFormationService {
//    use EntityManagerAwareTrait;
//    use UserServiceAwareTrait;
    use CategorieServiceAwareTrait;
    use MetierServiceAwareTrait;
    use GestionEntiteHistorisationTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param ParcoursDeFormation $parcours
     * @return ParcoursDeFormation
     */
    public function create(ParcoursDeFormation $parcours)
    {
        $this->createFromTrait($parcours);
        return $parcours;
    }

    /**
     * @param ParcoursDeFormation $parcours
     * @return ParcoursDeFormation
     */
    public function update(ParcoursDeFormation $parcours)
    {
        $this->updateFromTrait($parcours);
        return $parcours;
    }

    /**
     * @param ParcoursDeFormation $parcours
     * @return ParcoursDeFormation
     */
    public function historise(ParcoursDeFormation $parcours)
    {
        $this->historiserFromTrait($parcours);
        return $parcours;
    }

    /**
     * @param ParcoursDeFormation $parcours
     * @return ParcoursDeFormation
     */
    public function restore(ParcoursDeFormation $parcours)
    {
        $this->restoreFromTrait($parcours);
        return $parcours;
    }

    /**
     * @param ParcoursDeFormation $parcours
     * @return ParcoursDeFormation
     */
    public function delete(ParcoursDeFormation $parcours)
    {
        $this->deleteFromTrait($parcours);
        return $parcours;
    }

    /** REQUETAGE *****************************************************************************************************/
    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder()
    {
        $qb = $this->getEntityManager()->getRepository(ParcoursDeFormation::class)->createQueryBuilder('parcours')
            ->addSelect('formation')->leftJoin('parcours.formations', 'formation')
            ->addSelect('formationf')->leftJoin('formation.formation', 'formationf')
            ->addSelect('groupe')->leftJoin('formationf.groupe', 'groupe')
        ;
            ;
        return $qb;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @return ParcoursDeFormation[]
     */
    public function getParcoursDeFormations($champ = 'id', $ordre = 'ASC')
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('parcours.' . $champ, $ordre);
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param string $type
     * @return ParcoursDeFormation[]
     */
    public function getParcoursDeFormationsByType($type)
    {
        $qb = $this->createQueryBuilder();
            if ($type !== null) {
                $qb = $qb ->andWhere('parcours.type = :type')
                          ->setParameter('type', $type);
            } else {
                $qb = $qb ->andWhere('parcours.type IS NULL');
            }
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param integer $id
     * @return ParcoursDeFormation
     */
    public function getParcoursDeFormation($id)
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('parcours.id = :id')
            ->setParameter('id', $id)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs ParcoursDeFormation partagent le même id [".$id."]", 0 , $e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $param
     * @return ParcoursDeFormation
     */
    public function getRequestedParcoursDeFormation($controller, $param = 'parcours-de-formation')
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getParcoursDeFormation($id);
        return $result;
    }

    /**
     * @param ParcoursDeFormation $parcours
     * @return Categorie|Metier
     */
    public function getReference(ParcoursDeFormation $parcours)
    {
        if ($parcours->getType() === ParcoursDeFormation::TYPE_CATEGORIE) {
            $categorie = $this->getCategorieService()->getCategorie($parcours->getReference());
            return $categorie;
        }
        if ($parcours->getType() === ParcoursDeFormation::TYPE_METIER) {
            $metier = $this->getMetierService()->getMetier($parcours->getReference());
            return $metier;
        }
        return null;
    }

    /**
     * @param string $type
     * @param int $reference
     * @return ParcoursDeFormation
     */
    public function getParcoursDeFormationByTypeAndReference($type, $reference)
    {
        $qb = $this->createQueryBuilder()
            ->andWhere("parcours.type = :type")
            ->andWhere("parcours.reference = :reference")
            ->setParameter('type', $type)
            ->setParameter('reference', $reference)
            ->andWhere('parcours.histoDestruction IS NULL')
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs ParcoursDeFormation partagent le même type et référence [".$type."|".$reference."]", 0 , $e);
        }
        return $result;
    }

    /** ARRAY GENERATION **********************************************************************************************/

    /**
     * @param FicheMetier $ficheMetier
     * @param array $array
     * @return array
     */
    public function generateParcoursArrayFromFicheMetier(FicheMetier $ficheMetier, &$array = null)
    {
        if ($array === null) $array = [];
        $metier = $ficheMetier->getMetier();
        $categorie = ($metier)?$metier->getCategorie():null;

        if ($metier !== null) {
            $parcours = $this->getParcoursDeFormationByTypeAndReference(ParcoursDeFormation::TYPE_METIER, $metier->getId());
            if ($parcours !== null) $array[ParcoursDeFormation::TYPE_METIER][$metier->getId()] = $parcours;
        }
        if ($categorie !== null) {
            $parcours = $this->getParcoursDeFormationByTypeAndReference(ParcoursDeFormation::TYPE_CATEGORIE, $categorie->getId());
            if ($parcours !== null) $array[ParcoursDeFormation::TYPE_CATEGORIE][$categorie->getId()] = $parcours;
        }

        return $array;
    }

    /**
     * @param FichePoste $fichePoste
     * @return array
     */
    public function generateParcoursArrayFromFichePoste(FichePoste $fichePoste)
    {
        $array = [];
        foreach ($fichePoste->getFichesMetiers() as $ficheTypeExterne) {
            $ficheMetier = $ficheTypeExterne->getFicheType();
            $this->generateParcoursArrayFromFicheMetier($ficheMetier, $array);
        }
        return $array;
    }

    /** PARCOURS DE FORMATION FORMATION ********************************************************************/

    /**
     * @param AbstractActionController $controller
     * @param string $param
     * @return ParcoursDeFormationFormation
     */
    public function getRequestedParcoursDeFormationFormation(AbstractActionController $controller, $param = 'parcours-de-formation-formation')
    {
        $id = $controller->params()->fromRoute($param);

        $qb = $this->getEntityManager()->getRepository(ParcoursDeFormationFormation::class)->createQueryBuilder('pdff')
            ->addSelect('parcours')->join('pdff.parcours', 'parcours')
            ->addSelect('formation')->join('pdff.formation', 'formation')
            ->addSelect('groupe')->join('formation.groupe', 'groupe')
            ->andWhere('pdff.id = :id')
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs ParcoursDeFormationFormation partagent le même id [".$id."]");
        }

        return $result;
    }

    /**
     * @param ParcoursDeFormation $parcours
     * @param Formation $formation
     * @return ParcoursDeFormationFormation
     */
    public function addFormation(ParcoursDeFormation $parcours, Formation $formation)
    {
        foreach ($parcours->getFormations() as $pdff) {
            if ($pdff->getFormation() === $formation) return $pdff;
        }

        $date = $this->getDateTime();
        $user = $this->getUserService()->getConnectedUser();
        $pdff = new ParcoursDeFormationFormation();
        $pdff->setParcours($parcours);
        $pdff->setFormation($formation);
        $pdff->setHistoCreation($date);
        $pdff->setHistoCreateur($user);
        $pdff->setOrdre(ParcoursDeFormationFormation::DEFAULT_POSITION);

        try {
            $this->getEntityManager()->persist($pdff);
            $this->getEntityManager()->flush($pdff);
        } catch(ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement du ParcoursDeFormationFormation", 0, $e);
        }

        return $pdff;
    }

    public function removeFormation(ParcoursDeFormationFormation $pdff)
    {
        try {
            $this->getEntityManager()->remove($pdff);
            $this->getEntityManager()->flush($pdff);
        } catch(ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement du ParcoursDeFormationFormation", 0, $e);
        }
        return $pdff;
    }

    public function updateParcoursDeFormationFormation(ParcoursDeFormationFormation $pdff)
    {
        try {
            $this->getEntityManager()->flush($pdff);
        } catch(ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement du ParcoursDeFormationFormation", 0, $e);
        }
        return $pdff;
    }
}