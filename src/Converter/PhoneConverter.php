<?php

declare(strict_types=1);

namespace App\Converter;

use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberUtil;
use Psr\Log\LoggerInterface;

final class PhoneConverter
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function unifyPhoneNumber(?string $phoneNumber): ?string
    {
        $phoneNumberUtil = PhoneNumberUtil::getInstance();
        if (empty($phoneNumber)){
            return null;
        }

    try {
        $polishPhoneNumber = $phoneNumberUtil->parse($phoneNumber, "PL");
    } catch (NumberParseException $e) {
        $this->logger->info(sprintf('Phone number %s did not seem to be a phone number', $phoneNumber));

        return null;
    }

        return $polishPhoneNumber->getNationalNumber();
    }
}
