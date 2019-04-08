<?php

namespace Autoform\Service\Validation;

use Autoform\Entity\Db\FormulaireInstance;
use Autoform\Entity\Db\FormulaireReponse;
use Autoform\Entity\Db\Validation;
use Autoform\Entity\Db\ValidationReponse;
use Autoform\Service\Formulaire\FormulaireReponseServiceAwareTrait;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;

class ValidationReponseService {
    use EntityManagerAwareTrait;
    use FormulaireReponseServiceAwareTrait;

    /**
     * @param AbstractActionController $controller
     * @param string $label
     * @return ValidationReponse
     */
    public function getValidationRequestedReponse($controller, $label)
    {
        $id = $controller->params()->fromRoute($label);
        $reponse = $this->getValidationReponse($id);
        return $reponse;
    }

    /**
     * @return ValidationReponse[]
     */
    public function getValidationReponses()
    {
        $qb = $this->getEntityManager()->getRepository(ValidationReponse::class)->createQueryBuilder('reponse');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param Validation $validation
     * @return ValidationReponse[]
     */
    public function getValidationResponsesByValidation($validation)
    {
        $qb = $this->getEntityManager()->getRepository(ValidationReponse::class)->createQueryBuilder('reponse')
            ->andWhere('reponse.validation = :validation')
            ->setParameter('validation', $validation)
        ;

        /** @var ValidationReponse[] $result */
        $result = $qb->getQuery()->getResult();

        $reponses = [];
        foreach ($result as $item) {
            $reponses[$item->getReponse()->getChamp()->getId()] = $item;
        }
        return $reponses;
    }

    /**
     * @param integer $id
     * @return ValidationReponse
     */
    public function getValidationReponse($id)
    {
        $qb = $this->getEntityManager()->getRepository(ValidationReponse::class)->createQueryBuilder('reponse')
            ->andWhere('reponse.id = :id')
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Reponse partagent le même identifiant [".$id."].", $e);
        }
        return $result;
    }

    /**
     * @param ValidationReponse $reponse
     * @return ValidationReponse
     */
    public function create($reponse)
    {
        $this->getEntityManager()->persist($reponse);
        try {
            $this->getEntityManager()->flush($reponse);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Un problème s'est produit lors de la création d'un Reponse.", $e);
        }
        return $reponse;
    }

    /**
     * @param ValidationReponse $reponse
     * @return ValidationReponse
     */
    public function update($reponse)
    {
        try {
            $this->getEntityManager()->flush($reponse);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Un problème s'est produit lors de la mise à jour d'un Reponse.", $e);
        }
        return $reponse;
    }

    /**
     * @param ValidationReponse $reponse
     * @return ValidationReponse
     */
    public function delete($reponse)
    {
        $this->getEntityManager()->remove($reponse);
        try {
            $this->getEntityManager()->flush();
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Un problème s'est produit lors de la suppression d'un Reponse.", $e);
        }
        return $reponse;
    }

    /**
     * @param FormulaireInstance $instance
     * @param Validation $validation
     * @param array $data
     */
    public function updateValidationReponse($instance, $validation, $data)
    {
        /** @var FormulaireReponse[] $fReponses */
        $result = $this->getFormulaireReponseService()->getFormulaireResponsesByFormulaireInstance($instance);
        $fReponses = [];
        foreach ($result as $item) {
            $fReponses[$item->getChamp()->getId()] = $item;
        }

        /** @var ValidationReponse[] $vReponses */
        $result = $this->getValidationResponsesByValidation($validation);
        $vReponses = [];
        foreach ($result as $item) {
            $vReponses[$item->getReponse()->getChamp()->getId()] = $item;
        }

        foreach ($fReponses as $key => $fReponse) {
            if ($data["reponse_".$key] && $data["reponse_".$key] === 'on') {
                if ($vReponses[$key]) {
                    $vReponses[$key]->setValue(true);
                    $this->update($vReponses[$key]);
                } else {
                    $vReponse = new ValidationReponse();
                    $vReponse->setReponse($fReponse);
                    $vReponse->setValidation($validation);
                    $vReponse->setValue(true);
                    $this->create($vReponse);
                }
            } else {
                if ($vReponses[$key]) {
                    $this->delete($vReponses[$key]);
                }
            }
        }
    }

}