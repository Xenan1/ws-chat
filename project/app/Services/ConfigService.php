<?php

namespace App\Services;

use App\Models\Config;

class ConfigService
{
    protected Config $config;

    public function __construct()
    {
        $this->config = Config::get();
    }

    public function isParsingEnabled(): bool
    {
        return $this->config->isParsingEnabled();
    }

    public function enableParsing(): void
    {
        $this->setParsingEnabled(true);
    }

    public function disableParsing(): void
    {
        $this->setParsingEnabled(false);
    }

    protected function setParsingEnabled(bool $enabled): void
    {
        $this->config->parsing_enabled = $enabled;
        $this->config->save();
    }
}
