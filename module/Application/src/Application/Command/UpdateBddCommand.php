<?php

namespace Application\Command;

use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Unicaen\BddAdmin\Bdd;

class UpdateBddCommand extends Command
{
    protected static $defaultName = 'update-bdd';

    protected ?Bdd $bdd = null;

    public function setBdd(?Bdd $bdd): void
    {
        $this->bdd = $bdd;
    }

    protected function configure(): void
    {
        $this->setDescription("Mise à jour de la base de données selon la ddl");
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title("Update de la bdd");
        $io->text("Mise à jour de la Bdd en cours");

        /**
         * @var Bdd $bdd
         */
        $bdd = $this->bdd;
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