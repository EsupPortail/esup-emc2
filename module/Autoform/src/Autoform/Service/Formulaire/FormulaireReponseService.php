<?php

namespace Autoform\Service\Formulaire;

use Autoform\Entity\Db\Champ;
use Autoform\Entity\Db\Formulaire;
use Autoform\Entity\Db\FormulaireInstance;
use Autoform\Entity\Db\FormulaireReponse;
use Autoform\Service\Champ\ChampServiceAwareTrait;
use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Exception;
use UnicaenApp\Entity\UserInterface;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenUtilisateur\Entity\Db\User;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;

class FormulaireReponseService {
    use EntityManagerAwareTrait;
    use ChampServiceAwareTrait;
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

        try {
            $this->getEntityManager()->persist($reponse);
            $this->getEntityManager()->flush($reponse);
        } catch (ORMException $e) {
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
        } catch (ORMException $e) {
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
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème s'est produit lors de l'historisation d'un Reponse.", $e);
        }
        return $reponse;
    }

    /**
     * @param FormulaireReponse $reponse
     * @return FormulaireReponse
     */
    public function restore($reponse)
    {
        $reponse->setHistoDestructeur(null);
        $reponse->setHistoDestruction(null);

        try {
            $this->getEntityManager()->flush($reponse);
        } catch (ORMException $e) {
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
        try {
            $this->getEntityManager()->remove($reponse);
            $this->getEntityManager()->flush();
        } catch (ORMException $e) {
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

        $ids = [];
        foreach ($champs as $champ) {
            $ids[] = $champ->getId();
        }
        foreach($champs as $champ) {
            $value = $this->getValueFomData($champ, $data);
            if ($value !== null && $value !== "") {
                $found = null;
                foreach($reponses as $reponse) {
                    if ($reponse->getChamp()->getId() === $champ->getId()) {
                        $found = $reponse;
                        break;
                    }
                }
                if ($found !== null) {
                    // Le champ existe et a une réponse :: mise à jour
                    // TODO :: historise duplique avec la nouvelle valeur
                    $reponse = $found;
                    $reponse->setReponse($value);
                    $this->update($reponse);
                } else {
                    // Le champ n'existe pas et un réponse doit être sauvegardé :: creation
                    $reponse = new FormulaireReponse();
                    $reponse->setFormulaireInstance($instance);
                    $reponse->setChamp($champ);
                    $reponse->setReponse($value);
                    $this->create($reponse);
                }
            } else {
                foreach($reponses as $reponse) {
                    // Le champ existe mais n'a plus de réponse :: destruction
                    // TODO :: devrait être historisée
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
            case Champ::TYPE_NOMBRE :
            case Champ::TYPE_TEXTAREA :
                if (isset($data[$champ->getId()])) {
                    $value = trim($data[$champ->getId()]);
                    return ($value !== '')?$value:null;
                } else return null;
                break;
            case Champ::TYPE_SELECT :
            case Champ::TYPE_ENTITY :
            case Champ::TYPE_ANNEE :
                if (isset($data[$champ->getId()])) {
                    $value = $data[$champ->getId()];
                    return ($value !== 'null')?$value:null;
                } else return null;
                break;
            case Champ::TYPE_ENTITY_MULTI:
                $instances = $this->getChampService()->getAllInstance($champ->getOptions());
                $values = [];
                foreach($instances as $id => $instance) {
                    if (isset($data[$champ->getId()."_".$id])) {
                        $values[] = $id;
                    }
                }
                return implode(";", $values);
                break;
            case Champ::TYPE_MULTIPLE :
                $options = explode(";", $champ->getOptions());
                $values = [];
                foreach($options as $option) {
                    $option = str_replace(" ","_", $option);
                    if (isset($data[$champ->getId()."_".$option])) {
                        $values[] = "on_".$option;
                    }
                }
                return implode(";", $values);
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