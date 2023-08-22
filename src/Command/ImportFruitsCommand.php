<?php

declare(strict_types=1);

namespace App\Command;

use App\Client\ExternalApiClient;
use App\Client\Mailer;
use App\Repository\FruitRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'import:fruits',
    description: 'Import fruits from external api.',
)]
class ImportFruitsCommand extends Command
{
    public function __construct(
        private ExternalApiClient $externalApiClient,
        private FruitRepository $fruitRepository,
        private Mailer $mailer,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('type', InputArgument::REQUIRED, 'Type of fruit import.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $type = $input->getArgument('type');
        $importCount = 0;
        
        if ('all' === $type) {
            $importCount = $this->importAllAvailableFruits();
            $this->mailer->sendEmail(
                $_ENV['MAIL_SENDER'],
                $_ENV['MAIL_RECIPIENT'],
                'Fruits import report.',
                "Successfully imported $importCount fruits.",
            );

            $io->success('Import is successful, an email will be sent shortly.');

            return Command::SUCCESS;
        }

        $io->error('Import type found.');

        return Command::INVALID;
    }

    private function importAllAvailableFruits(): int
    {
        $fruits = $this->externalApiClient->getAllFruits();
    
        $this->fruitRepository->cleanup();
        $this->fruitRepository->bulkCreate($fruits);

        return \count($fruits);
    }
}
