<?php

declare(strict_types=1);

namespace App\Converter;

use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberUtil;
use Psr\Log\LoggerInterface;

final class PhoneConverter implements PhoneConverterInterface
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function unifyPhoneNumber(?array $report): ?string
    {
        $phoneNumberUtil = PhoneNumberUtil::getInstance();
        $phoneNumber = $report['phone'];
        if (empty($phoneNumber)){
            $this->logger->info(sprintf('Report with id %s has empty phone number field', $report['number']));
            return null;
        }

    try {
        $polishPhoneNumber = $phoneNumberUtil->parse($phoneNumber, "PL");
    } catch (NumberParseException $e) {
        $this->logger->info(
            sprintf(
                'Phone number %s from report with id %s did not seem to be a phone number',
                $phoneNumber,
                $report['number']
            ));

        return null;
    }

        return $polishPhoneNumber->getNationalNumber();
    }
}
