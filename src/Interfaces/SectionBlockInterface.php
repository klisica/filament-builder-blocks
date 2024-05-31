<?php

namespace KLisica\FilamentBuilderBlocks\Interfaces;

use Filament\Forms\Components\Builder;

interface SectionBlockInterface
{
    // Unique block key for Filament panel.
    public function getKey(): string;

    // Readable name for Filament panel.
    public function getName(): string;

    // Icon key for Filament panel.
    public function getIcon(): string;

    // Order position for Filament panel.
    public function getOrder(): int;

    // Tags to filter sections through resources.
    public function getTags(): array;

    // Get builder block for Filament panel.
    public function getSectionItems(): Builder\Block;
}
