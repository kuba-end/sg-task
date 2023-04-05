<?php

namespace App\Converter;

interface PhoneConverterInterface
{
    public function unifyPhoneNumber(?array $report): ?string;
}
