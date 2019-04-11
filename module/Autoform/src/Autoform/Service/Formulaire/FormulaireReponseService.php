<?php

namespace Autoform\Service\Formulaire;

use Application\Service\User\UserServiceAwareTrait;
use Autoform\Entity\Db\FormulaireInstance;
use Autoform\Entity\Db\Champ;
use Autoform\Entity\Db\Formulaire;
use Autoform\Entity\Db\FormulaireReponse;
use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Exception;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenAuth\Entity\Db\User;
use Zend\Mvc\Controller\AbstractActionController;

class FormulaireReponseService {
    use EntityManagerAwareTrait;
    use UserServiceAwareTrait;

    /**
     * @param AbstractActionController $controller
     * @param string $label
     * @return FormulaireReponse
     */
    public function getFormulaireRequestedReponse($controller, $label)
    {
        $id = $controller->params()->fromRoute($label);
        $reponse = $this->getFormulaireReponse($id);
        return $reponse;
    }

    /**
     * @return FormulaireReponse[]
     */
    public function getFormulaireReponses()
    {
        $qb = $this->getEntityManager()->getRepository(FormulaireReponse::class)->createQueryBuilder('reponse');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param integer $id
     * @return FormulaireReponse
     */
    public function getFormulaireReponse($id)
    {
        $qb = $this->getEntityManager()->getRepository(FormulaireReponse::class)->createQueryBuilder('reponse')
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
     * @param FormulaireReponse $reponse
     * @return FormulaireReponse
     */
    public function create($reponse)
    {
        /** @var User $user */
        $user = $this->getUserService()->getConnectedUser();
        /** @var DateTime $date */
        try {
            $date = new DateTime();
        } catch (Exception $e) {
            throw new RuntimeException("Problème lors de la récupération de la date", $e);
        }
        $reponse->setHistoCreateur($user);
        $reponse->setHistoCreation($date);
        $reponse->setHistoModificateur($user);
        $reponse->setHistoModification($date);

        $this->getEntityManager()->persist($reponse);
        try {
            $this->getEntityManager()->flush($reponse);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Un problème s'est produit lors de la création d'un Reponse.", $e);
        }
        return $reponse;
    }

    /**
     * @param FormulaireReponse $reponse
     * @return FormulaireReponse
     */
    public function update($reponse)
    {
        /** @var User $user */
        $user = $this->getUserService()->getConnectedUser();
        /** @var DateTime $date */
        try {
            $date = new DateTime();
        } catch (Exception $e) {
            throw new RuntimeException("Problème lors de la récupération de la date", $e);
        }
        $reponse->setHistoModificateur($user);
        $reponse->setHistoModification($date);

        try {
            $this->getEntityManager()->flush($reponse);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Un problème s'est produit lors de la mise à jour d'un Reponse.", $e);
        }
        return $reponse;
    }

    /**
     * @param FormulaireReponse $reponse
     * @return FormulaireReponse
     */
    public function historise($reponse)
    {
        /** @var User $user */
        $user = $this->getUserService()->getConnectedUser();
        /** @var DateTime $date */
        try {
            $date = new DateTime();
        } catch (Exception $e) {
            throw new RuntimeException("Problème lors de la récupération de la date", $e);
        }
        $reponse->setHistoDestructeur($user);
        $reponse->setHistoDestruction($date);

        try {
            $this->getEntityManager()->flush($reponse);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Un problème s'est produit lors de l'historisation d'un Reponse.", $e);
        }
        return $reponse;
    }

    /**
     * @param FormulaireReponse $reponse
     * @return FormulaireReponse
     */
    public function restaure($reponse)
    {
        $reponse->setHistoDestructeur(null);
        $reponse->setHistoDestruction(null);

        try {
            $this->getEntityManager()->flush($reponse);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Un problème s'est produit lors de la restauration d'un Reponse.", $e);
        }
        return $reponse;
    }

    /**
     * @param FormulaireReponse $reponse
     * @return FormulaireReponse
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
     * @param Formulaire $formulaire
     * @param FormulaireInstance $instance
     * @param array $data
     */
    public function updateFormulaireReponse($formulaire, $instance, $data)
    {
        /** @var Champ[] $champs */
        $qb = $this->getEntityManager()->getRepository(Champ::class)->createQueryBuilder('champ')
            ->addSelect('categorie')->join('champ.categorie', 'categorie')
            ->andWhere('categorie.formulaire = :formulaire')
            ->andWhere('categorie.histoDestruction IS NULL')
            ->andWhere('champ.histoDestruction IS NULL')

            ->setParameter('formulaire', $formulaire)
        ;
        $champs = $qb->getQuery()->getResult();

        /** @var FormulaireReponse[] $reponses */
        $reponses = $this->getFormulaireResponsesByFormulaireInstance($instance);

        foreach($champs as $champ) {
            $value = $this->getValueFomData($champ, $data);
            if ($value !== null) {
                $found = null;
                foreach($reponses as $reponse) {
                    if ($reponse->getChamp()->getId() === $champ->getId()) {
                        $found = $reponse;
                        break;
                    }
                }
                if ($found !== null) {
                    $reponse = $found;
                    $reponse->setReponse($value);
                    $this->update($reponse); //cas C
                } else {
                    $reponse = new FormulaireReponse();
                    $reponse->setFormulaireInstance($instance);
                    $reponse->setChamp($champ);
                    $reponse->setReponse($value);
                    $this->create($reponse); // cas B
                }
            } else {
                foreach($reponses as $reponse) {
                    if ($reponse->getChamp()->getId() === $champ->getId()) {
                        $this->delete($reponse); //cas A
                        break;
                    }
                }
            }
        }
    }

    /**
     * @param FormulaireInstance $instance
     * @return FormulaireReponse[]
     */
    public function getFormulaireResponsesByFormulaireInstance($instance)
    {
        $qb = $this->getEntityManager()->getRepository(FormulaireReponse::class)->createQueryBuilder('reponse')
            ->andWhere('reponse.instance = :instance')
            ->andWhere('reponse.histoDestruction IS NULL')
            ->setParameter('instance', $instance);

        $result = $qb->getQuery()->getResult();

        $reponses = [];
        /** @var FormulaireReponse $item */
        foreach ($result as $item) {
            $reponses[$item->getChamp()->getId()] = $item;
        }
        return $reponses;
    }

    /**
     * @param Champ $champ
     * @param array $data
     * @return string
     */
    public function getValueFomData($champ, $data)
    {
        switch ($champ->getElement()) {
            case Champ::TYPE_CHECKBOX :
                return isset($data[$champ->getId()]) ? $data[$champ->getId()] : null;
                break;
            case Champ::TYPE_TEXT :
            case Champ::TYPE_TEXTAREA :
                if (isset($data[$champ->getId()])) {
                    $value = trim($data[$champ->getId()]);
                    return ($value !== '')?$value:null;
                } else return null;
                break;
            case Champ::TYPE_SELECT :
                if (isset($data[$champ->getId()])) {
                    $value = $data[$champ->getId()];
                    return ($value !== 'null')?$value:null;
                } else return null;
                break;
            case Champ::TYPE_PERIODE :
                $select = $data['select_'.$champ->getId()];
                if ($select === 'null')    return null;
                if ($select !== 'Balisée') return $select;
                if ($select === 'Balisée') {
                    if ($data['debut_'.$champ->getId()] === '' || $data['fin_'.$champ->getId()] === '') return null;
                    if ($data['debut_'.$champ->getId()] > $data['fin_'.$champ->getId()]) return null;
                    $row_date1 = $data['debut_'.$champ->getId()];
                    $date1 = implode("/",array_reverse(explode('-',$row_date1)));
                    $row_date2 = $data['fin_'.$champ->getId()];
                    $date2 = implode("/",array_reverse(explode('-',$row_date2)));
                    return 'Du '.$date1.' au '.$date2;
                }
                return null;
                break;
            default:
                return null;
                break;
        }
    }
}