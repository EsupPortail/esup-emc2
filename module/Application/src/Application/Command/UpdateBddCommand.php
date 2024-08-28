<?php

namespace Application\Command;

use Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Unicaen\BddAdmin\Bdd;

class UpdateBddCommand extends CommandAbstract
{
    protected static $defaultName = 'update-bdd';

    protected function configure(): void
    {
        $this->setDescription("Mise à jour de la base de données selon la ddl");
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = $this->getIO($input, $output);

        $io->title("Update de la bdd");
        $io->text("Mise à jour de la Bdd en cours");

        /**
         * @var Bdd $bdd
         */
        $bdd = $this->getServicemanager()->get(Bdd::class);
        if ($bdd != null) {
            try {
                $ref = $bdd->getRefDdl();
            } catch (Exception $e) {
                $io->error($e->getMessage());
                return self::FAILURE;
            }

            $filters = $ref->makeFilters();

            $bdd->alter($ref, $filters);
            $io->success("Bdd mise à jour");
        }

        return self::SUCCESS;
    }
}