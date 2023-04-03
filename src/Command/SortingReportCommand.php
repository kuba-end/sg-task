<?php

declare(strict_types=1);

namespace App\Command;

use App\Chain\InspectionReportChain\InspectionReportTypeChain;
use App\Chain\MalfunctionReportChain\MalfunctionReportTypeChain;
use App\Chain\ReportTypeChain;
use App\Factory\ReportFactory;
use App\Finder\DuplicateFinder;
use App\Report\InspectionReport;
use App\Report\ReportInterface;
use App\Resolver\ReportTypeResolver;
use App\Service\ReportSorter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class SortingReportCommand extends Command
{
    protected static $defaultName = 'sg:sort:report';

    private const PATH = '/../../etc/report/';

    private ReportSorter $reportSorter;

    private ReportFactory $reportFactory;

    private MessageBusInterface $messageBus;

    private MalfunctionReportTypeChain $malfunctionReportTypeChain;

    private InspectionReportTypeChain $inspectionReportTypeChain;

    private ReportTypeResolver $reportTypeResolver;

    private DuplicateFinder $duplicateFinder;

    public function __construct(
        ReportSorter $reportSorter,
        ReportFactory $reportFactory,
        MessageBusInterface $messageBus,
        MalfunctionReportTypeChain $malfunctionReportTypeChain,
        InspectionReportTypeChain $inspectionReportTypeChain,
        ReportTypeResolver $reportTypeResolver,
        DuplicateFinder $duplicateFinder
    ) {
        parent::__construct();
        $this->reportSorter = $reportSorter;
        $this->reportFactory = $reportFactory;
        $this->messageBus = $messageBus;
        $this->malfunctionReportTypeChain = $malfunctionReportTypeChain;
        $this->inspectionReportTypeChain = $inspectionReportTypeChain;
        $this->reportTypeResolver = $reportTypeResolver;
        $this->duplicateFinder = $duplicateFinder;
    }

    protected function configure(): void
    {
        $this->setDescription('Sort reports from file');
        $this->addArgument('file', InputArgument::REQUIRED, 'File name');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // todo: pierwszy serwis 1.sprawdzić czy istnieje/wyszukać plik 2.zwrócić stringa
        $sourceFileName = $input->getArgument('file');
        $sourceFilePath = __DIR__ . self::PATH . $sourceFileName;
        if (!file_exists($sourceFilePath)) {
            $output->writeln(sprintf(
                'File "%s" doesn\'t exist in %s. Please make sure that file name is correct',
                $sourceFileName,
                self::PATH,
            ));

            return Command::FAILURE;
        }
        $sourceFileString = file_get_contents($sourceFilePath);
        $reports = json_decode($sourceFileString, true);
        $uniqueReports = $this->duplicateFinder->findByDescription($reports);

//        $messeage = new Message
//        $envelope = new Envelope();
//        $this->messageBus->dispatch($reports);
        // todo: drugi serwis, sortuje czy przegląd czy awaria
        $deserializedReports = $this->deserialize($uniqueReports);

        $inspectionReports = [];
        $malfunctionReports = [];
        foreach ($deserializedReports as $report){
            $specificReport = $this->reportTypeResolver->resolve($report);
            if ($specificReport instanceof InspectionReport) {
                $inspectionReports[] = $this->inspectionReportTypeChain->filter($specificReport);

                continue;
            }
            $malfunctionReports[] = $this->malfunctionReportTypeChain->filter($specificReport);
        }

        // TODO: dodać ujednolicenie numeru telefonu, zapiać querybusa, uporządkować, dodać logi, dodać ładny output
        dd($inspectionReports, $malfunctionReports);
        return Command::SUCCESS;
    }

    private function deserialize(array $reports): ReportInterface|array
    {
        $deserializedReports = [];
        foreach ($reports as $singleReport) {
            $deserializedReports[] = $this->reportFactory->create($singleReport);
        }

        return $deserializedReports;
    }
}
