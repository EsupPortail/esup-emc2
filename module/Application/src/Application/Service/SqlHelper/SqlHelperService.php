<?php

namespace Application\Service\SqlHelper;

use Doctrine\DBAL\Driver\Exception as DBA_Driver_Exception;
use Doctrine\DBAL\Exception as DBA_Exception;
use DoctrineModule\Persistence\ProvidesObjectManager;
use RuntimeException;

class SqlHelperService {

    use ProvidesObjectManager;

    public function executeQuery(string $query, array $params = [], array $options = []): array
    {
        try {
            $this->getObjectManager()->getConnection()->prepare($query);
        } catch (DBA_Exception $e) {
            throw new RuntimeException(
                "Un problème est survenu lors de la préparation de la requête."
                ."<br>" ."<pre>".$query."</pre>", 0, $e);
        }
        try {
            $res = $this->getObjectManager()->getConnection()->executeQuery($query, $params, $options);
            $data = $res->fetchAllAssociative();
        } catch (DBA_Driver_Exception $e) {
            throw new RuntimeException("Un problème est survenu lors de la récupération des données.", 0, $e);
        }

        return $data;
    }
}
