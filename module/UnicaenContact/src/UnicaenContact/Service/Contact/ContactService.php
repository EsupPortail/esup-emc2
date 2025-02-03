<?php

namespace UnicaenContact\Service\Contact;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Laminas\Mvc\Controller\AbstractActionController;
use RuntimeException;
use UnicaenContact\Entity\Db\Contact;
use UnicaenContact\Entity\Db\Type;

class ContactService {
    use ProvidesObjectManager;

    /** GESTION DES ENTITÉS *******************************************************************************************/

    public function create(Contact $contact): Contact
    {
        $this->getObjectManager()->persist($contact);
        $this->getObjectManager()->flush();
        return $contact;
    }

    public function update(Contact $contact): Contact
    {
        $this->getObjectManager()->flush();
        return $contact;
    }

    public function historise(Contact $contact): Contact
    {
        $contact->historiser();
        $this->getObjectManager()->flush();
        return $contact;
    }

    public function restore(Contact $contact): Contact
    {
        $contact->dehistoriser();
        $this->getObjectManager()->flush();
        return $contact;
    }

    public function delete(Contact $contact): Contact
    {
        $this->getObjectManager()->remove($contact);
        $this->getObjectManager()->flush();
        return $contact;
    }

    /** REQUETAGE *****************************************************************************************************/

    public function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(Contact::class)->createQueryBuilder('contact')
            ->join('contact.type', 'contacttype')->addSelect('contacttype')
        ;
        return $qb;
    }

    public function getContacts(bool $withHisto = false) : array
    {
        $qb = $this->createQueryBuilder();
        if (!$withHisto) $qb = $qb->andWhere('contact.histoDestruction IS NULL');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getContactsByType(Type $type, bool $withHisto = false) : array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('contact.type = :type')->setParameter('type', $type)
        ;
        if (!$withHisto) $qb = $qb->andWhere('contact.histoDestruction IS NULL');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getContact(?int $id): ?Contact
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('contact.id = :id')->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [".Contact::class."] partagent le même id [".$id."]", 0 , $e);
        }
        return $result;
    }

    public function getRequestedContract(AbstractActionController $controller, string $params = 'contact'): ?Contact
    {
        $id = $controller->params()->fromRoute($params);
        $result = $this->getContact($id);
        return $result;
    }

    /** FACADE ********************************************************************************************************/
}