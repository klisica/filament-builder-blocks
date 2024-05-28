<?php

namespace KLisica\FilamentBuilderBlocks\Commands;

use Illuminate\Console\Command;

class FilamentBuilderBlocksCommand extends Command
{
    public $signature = 'filament-builder-blocks';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
