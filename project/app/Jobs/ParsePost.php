<?php

namespace App\Jobs;

use App\Logging\Enum\LogLevels;
use App\Logging\ParsingLogger;
use App\Parsing\AbstractParser;
use App\Parsing\Exceptions\AbstractParserException;
use App\Services\ConfigService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ParsePost implements ShouldQueue
{
    use Queueable;

    public function handle(
        ParsingLogger $logger,
        ConfigService $config,
        AbstractParser $parser
    ): void
    {
        if ($config->isParsingEnabled()) {
            try {
                $newPost = $parser->createPost();
                $logger->log(LogLevels::Info, "Parsed post {$newPost->getId()}");
            } catch (AbstractParserException $e) {
                $logger->log(LogLevels::Error, 'Error during parsing post: ' . $e->getMessage());
            }

        }
    }
}
