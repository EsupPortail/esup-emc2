<?php

namespace FicheMetier\Form\CodeFonction;

use FicheMetier\Entity\Db\FicheMetier;
use Laminas\Hydrator\HydratorInterface;

class CodeFonctionHydrator implements HydratorInterface
{
    public function extract(object $object): array
    {
        /** @var FicheMetier $object */
        $data = [
            'code-fonction' => $object->getCodeFonction(),
        ];
        return $data;
    }

    public function hydrate(array $data, object $object): object
    {
        $code = (isset($data['code-fonction']) AND trim($data['code-fonction']) !=='') ? trim ($data['code-fonction']) : null;

        /** @var FicheMetier $object */
        $object->setCodeFonction($code);
        return $object;
    }

}