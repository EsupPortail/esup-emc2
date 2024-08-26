<?php

namespace Formation\Form\Validator\LieuUtilise;

use Formation\Service\Lieu\LieuService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class LieuUtiliseValidatorFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : LieuUtiliseValidator
    {
        /**
         * @var LieuService $lieuService
         */
        $lieuService = $container->get(LieuService::class);

        $validator = new LieuUtiliseValidator();
        $validator->setLieuService($lieuService);
        return $validator;
    }
}