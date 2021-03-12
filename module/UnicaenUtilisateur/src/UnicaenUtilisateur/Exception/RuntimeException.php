<?php

namespace UnicaenUtilisateur\Exception;

use UnicaenApp\Exception\ExceptionInterface;

/**
 * Exception émise quand une erreur est rencontrée durant l'exécution 
 * d'une application Unicaen.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class RuntimeException extends \RuntimeException implements ExceptionInterface
{
    
}