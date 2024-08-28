<?php

namespace Application\Service\Bdd;

use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Unicaen\BddAdmin\Bdd;

class BddFactory
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     *
     * @param ContainerInterface $container
     *
     * @return Bdd
     */
    public function __invoke(ContainerInterface $container): Bdd
    {
        $configs = $container->get('Config')['bdd-admin'];

        $bdd = new Bdd($configs['bdd']);
        $bdd->setOptions([
            /* Facultatif, permet de spécifier une fois pour toutes le répertoire où sera renseignée la DDL de votre BDD */
            Bdd::OPTION_DDL_DIR                => $configs[Bdd::OPTION_DDL_DIR],

            /* Facultatif, spécifie le répertoire où seront stockés vos scripts de migration si vous en avez */
            Bdd::OPTION_MIGRATION_DIR          => $configs[Bdd::OPTION_MIGRATION_DIR],

            /* Facultatif, permet de personnaliser l'ordonnancement des colonnes dans les tables */
            Bdd::OPTION_COLUMNS_POSITIONS_FILE => $configs[Bdd::OPTION_COLUMNS_POSITIONS_FILE],
        ]);

        return $bdd;
    }
}