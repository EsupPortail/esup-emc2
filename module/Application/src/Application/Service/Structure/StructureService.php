<?php

namespace Application\Service\Structure;

use Application\Entity\Db\Source;
use Application\Entity\Db\Structure;
use Application\Entity\Db\StructureType;
use Utilisateur\Service\User\UserServiceAwareTrait;
use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Exception;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;

class StructureService
{
    use EntityManagerAwareTrait;
    use UserServiceAwareTrait;
    use \Octopus\Service\Structure\StructureServiceAwareTrait;

    /**
     * @return Structure
     * @var Structure $structure
     */
    public function create($structure)
    {
        try {
            $date = new DateTime();
        } catch(Exception $e) {
            throw new RuntimeException("Un problème est survenu lors de la récupération de la date", $e);
        }
        $user = $this->getUserService()->getConnectedUser();

        $structure->setHistoCreation($date);
        $structure->setHistoCreateur($user);
        $structure->setHistoModification($date);
        $structure->setHistoModificateur($user);

        $date = $structure->getDateOuverture();
        $format = $date->format('d/m/Y');


        try {
            $this->getEntityManager()->persist($structure);
            $this->getEntityManager()->flush($structure);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'enregistrement d'une Structure", $e);
        }
        return $structure;
    }

    /**
     * @return Structure
     * @var Structure $structure
     */
    public function update($structure)
    {
        try {
            $date = new DateTime();
        } catch(Exception $e) {
            throw new RuntimeException("Un problème est survenu lors de la récupération de la date", $e);
        }
        $user = $this->getUserService()->getConnectedUser();

        $structure->setHistoModification($date);
        $structure->setHistoModificateur($user);

        try {
            $this->getEntityManager()->flush($structure);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'enregistrement d'une Structure", $e);
        }
        return $structure;
    }

    /**
     * @return Structure
     * @var Structure $structure
     */
    public function historise($structure)
    {
        try {
            $date = new DateTime();
        } catch(Exception $e) {
            throw new RuntimeException("Un problème est survenu lors de la récupération de la date", $e);
        }
        $user = $this->getUserService()->getConnectedUser();

        $structure->setHistoDestruction($date);
        $structure->setHistoDestructeur($user);

        try {
            $this->getEntityManager()->flush($structure);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'enregistrement d'une Structure", $e);
        }
        return $structure;
    }

    /**
     * @return Structure
     * @var Structure $structure
     */
    public function restore($structure)
    {
        try {
            $date = new DateTime();
        } catch(Exception $e) {
            throw new RuntimeException("Un problème est survenu lors de la récupération de la date", $e);
        }
        $user = $this->getUserService()->getConnectedUser();

        $structure->setHistoModification($date);
        $structure->setHistoModificateur($user);
        $structure->setHistoDestruction(null);
        $structure->setHistoDestructeur(null);

        try {
            $this->getEntityManager()->flush($structure);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'enregistrement d'une Structure", $e);
        }
        return $structure;
    }

    /**
     * @return Structure
     * @var Structure $structure
     */
    public function delete($structure)
    {
        try {
            $this->getEntityManager()->remove($structure);
            $this->getEntityManager()->flush();
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'effacement d'une Structure", $e);
        }
        return $structure;
    }

    /**
     * @return Structure[]
     */
    public function getStructures()
    {
        $qb = $this->getEntityManager()->getRepository(Structure::class)->createQueryBuilder('structure')
            ->orderBy('structure.id');
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param integer $id
     * @return Structure
     */
    public function getStructure($id)
    {
        $qb = $this->getEntityManager()->getRepository(Structure::class)->createQueryBuilder('structure')
            ->andWhere('structure.id = :id')
            ->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Structure partagent le même identifiant [".$id."]", $e);
        }
        return $result;
    }

    /**
     * @return Structure[]
     */
    public function getStructuresOuvertes()
    {
        $qb = $this->getEntityManager()->getRepository(Structure::class)->createQueryBuilder('structure')
            ->andWhere('structure.dateFermeture IS NULL')
            ->orderBy('structure.id');
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @return StructureType[]
     */
    public function getStructureTypeAsOptions()
    {
        $qb = $this->getEntityManager()->getRepository(StructureType::class)->createQueryBuilder('type')
            ->orderBy('type.libelle');
        $result = $qb->getQuery()->getResult();

        $options = [];
        $options[null] = 'Sélectionnez un type de structure ... ';
        /** @var StructureType $item */
        foreach ($result as $item) {
            $options[$item->getCode()] = $item->getLibelle();
        }
        return $options;
    }

    /**
     * @param string $typeCode
     * @return StructureType
     */
    public function getStructureTypeByCode($typeCode)
    {
        $qb = $this->getEntityManager()->getRepository(StructureType::class)->createQueryBuilder('type')
            ->andWhere('type.code = :type')
            ->setParameter('type', $typeCode);

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs StructureType partagent le même code [".$typeCode."].", $e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $paramName
     * @return Structure
     */
    public function getRequestedStructure($controller, $paramName)
    {
        $id = $controller->params()->fromRoute($paramName);
        $structure = $this->getStructure($id);
        return $structure;
    }

    public function synchroniseFromOctopus()
    {
        $structures_OCTOPUS = $this->getStructureService()->getStructures();
        $structures_PREECOG = $this->getStructures();

        $nouvelles = [];
        $modifiees = [];
        $supprimees = [];
        foreach ($structures_OCTOPUS as $structure) {
            $res = array_filter($structures_PREECOG,
                function(Structure $s) use ($structure) { return ($s->getSource() === 'OCTOPUS' && $s->getIdSource() == $structure->getId()); });
            if (! empty($res)) {
                $modification = $this->updateFromOctopus(current($res), $structure);
                if ($modification) $modifiees[] = current($res);
            } else {
                //nouvelle structure
                $nouvelle = $this->createFromOctopus($structure);
                $nouvelles[] = $nouvelle;
            }
        }
        foreach ($structures_PREECOG as $structure) {
            if ($structure->getSource() === Source::Octopus) {
                $res = array_filter($structures_OCTOPUS,
                    function (\Octopus\Entity\Db\Structure $s) use ($structure) {
                        return ($structure->getSource() === 'OCTOPUS' && $s->getId() == $structure->getIdSource());
                    });
                if (empty($res)) {
                    $this->delete($structure);
                    $supprimees[] = $structure;
                }
            }
        }
        return [
            'nouvelles' => $nouvelles,
            'modifiees' => $modifiees,
            'supprimees' => $supprimees,
        ];
    }

    /**
     * @param \Octopus\Entity\Db\Structure $structure
     * @return Structure
     */
    private function createFromOctopus(\Octopus\Entity\Db\Structure $structure)
    {
        $type = $this->getStructureTypeByCode($structure->getType()->getCode());

        $nouvelle = new Structure();
        $nouvelle->setLibelleCourt($structure->getLibelleCourt());
        $nouvelle->setLibelleLong($structure->getLibelleLong());
        $nouvelle->setSigle($structure->getSigle());
        $nouvelle->setType($type);
        $nouvelle->setDateOuverture($structure->getDateOuverture());
        $nouvelle->setDateFermeture($structure->getDateFermeture());
        $nouvelle->setSource('OCTOPUS');
        $nouvelle->setIdSource($structure->getId());
        $this->create($nouvelle);
        return $nouvelle;
    }

    /**
     * @param Structure $current
     * @param \Octopus\Entity\Db\Structure $structure
     * @return bool
     */
    private function updateFromOctopus($current, \Octopus\Entity\Db\Structure $structure)
    {
        $modification = false;
        $typeC = $this->getStructureTypeByCode($current->getType()->getCode());
        $typeO = $this->getStructureTypeByCode($structure->getType()->getCode());

        if ($current->getLibelleCourt() !== $structure->getLibelleCourt()) {
            $current->setLibelleCourt($structure->getLibelleCourt());
            $modification = true;
        }
        if ($current->getLibelleLong() !== $structure->getLibelleLong()) {
            $current->setLibelleLong($structure->getLibelleLong());
            $modification = true;
        }
        if ($current->getSigle() !== $structure->getSigle()) {
            $current->setSigle($structure->getSigle());
            $modification = true;
        }
        if ($typeC->getId() !== $typeO->getId()) {
            $current->setType($typeO);
            $modification = true;
        }
        if ($current->getDateOuverture() != $structure->getDateOuverture()) {
            $current->setDateOuverture($structure->getDateOuverture());
            $modification = true;
        }
        if ($current->getDateFermeture() != $structure->getDateFermeture()) {
            $current->setDateFermeture($structure->getDateFermeture());
            $modification = true;
        }
        if ($modification) $this->update($current);
        return $modification;
    }

}