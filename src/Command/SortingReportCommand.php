<?php

declare(strict_types=1);

namespace App\Command;

use App\Chain\InspectionReportChain\InspectionReportTypeChain;
use App\Chain\MalfunctionReportChain\MalfunctionReportTypeChain;
use App\Converter\PhoneConverter;
use App\Factory\ReportFactory;
use App\Finder\DuplicateFinder;
use App\Report\InspectionReport;
use App\Report\ReportInterface;
use App\Resolver\ReportTypeResolver;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class SortingReportCommand extends Command
{
    protected static $defaultName = 'sg:sort:report';

    private const PATH = '/../../etc/report/';

    private const MALFUNCTION_REPORTS_FILENAME = 'malfunction_reports.json';

    private const INSPECTION_REPORTS_FILENAME = 'inspection_reports.json';
    private const DUPLICATE_REPORTS_FILENAME = 'duplicate_reports.json';

    private ReportFactory $reportFactory;

    private MessageBusInterface $messageBus;

    private MalfunctionReportTypeChain $malfunctionReportTypeChain;

    private InspectionReportTypeChain $inspectionReportTypeChain;

    private ReportTypeResolver $reportTypeResolver;

    private DuplicateFinder $duplicateFinder;

    private LoggerInterface $logger;

    public function __construct(
        ReportFactory $reportFactory,
        MessageBusInterface $messageBus,
        MalfunctionReportTypeChain $malfunctionReportTypeChain,
        InspectionReportTypeChain $inspectionReportTypeChain,
        ReportTypeResolver $reportTypeResolver,
        DuplicateFinder $duplicateFinder,
        LoggerInterface $logger
    ) {
        parent::__construct();
        $this->reportFactory = $reportFactory;
        $this->messageBus = $messageBus;
        $this->malfunctionReportTypeChain = $malfunctionReportTypeChain;
        $this->inspectionReportTypeChain = $inspectionReportTypeChain;
        $this->reportTypeResolver = $reportTypeResolver;
        $this->duplicateFinder = $duplicateFinder;
        $this->logger = $logger;
    }

    protected function configure(): void
    {
        $this->setDescription('Sort reports from file');
        $this->addArgument('file', InputArgument::REQUIRED, 'File name');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $sourceFileName = $input->getArgument('file');
        $sourceFilePath = __DIR__ . self::PATH . $sourceFileName;

        $output->writeln('Reading file...');
        if (!file_exists($sourceFilePath)) {
            $output->writeln(sprintf(
                'File "%s" doesn\'t exist in %s. Please make sure that file name is correct',
                $sourceFileName,
                self::PATH,
            ));
            $this->logger->error(sprintf(
                'File "%s" doesn\'t exist in %s. Please make sure that file name is correct',
                $sourceFileName,
                self::PATH,
            ));

            return Command::FAILURE;
        }

        $sourceFileString = file_get_contents($sourceFilePath);

        $output->writeln(sprintf('File %s opened correctly', $sourceFileName));

        $reports = json_decode($sourceFileString, true);
        $uniqueReports = $this->duplicateFinder->findUniqueByDescription($reports);
        $duplicatedReports = $this->duplicateFinder->findDuplicatedByDescription($reports);

        $output->writeln(
            sprintf('%d unique reports found in source file, also found %d duplicates with given ids:',
            count($uniqueReports),
            count($duplicatedReports)
            )
        );

        foreach ($duplicatedReports as $duplicate) {
            $output->writeln(sprintf('%d', $duplicate['number']));
        }

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

        file_put_contents(
            self::MALFUNCTION_REPORTS_FILENAME,
            json_encode($malfunctionReports),
            JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES
        );
        file_put_contents(
            self::INSPECTION_REPORTS_FILENAME,
            json_encode($inspectionReports),
            JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES
        );
        file_put_contents(
            self::DUPLICATE_REPORTS_FILENAME,
            json_encode($duplicatedReports),
            JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES
        );

        $this->logger->info(sprintf(
            '%d reports proccesed. %d was qualified as a inspection reports and %d was qualified as a malfunction reports. %d of the reports was duplicated.',
            count($reports),
            count($inspectionReports),
            count($malfunctionReports),
            count($duplicatedReports),
        ));

        $output->writeln(sprintf(
            '%d reports proccesed. %d was qualified as a inspection reports and %d was qualified as a malfunction reports. %d of the reports was duplicated.',
                    count($reports),
                    count($inspectionReports),
                    count($malfunctionReports),
                    count($duplicatedReports),
        ));
        // TODO: , zapiać querybusa, dodać logi
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
