<?php

namespace KLisica\FilamentBuilderBlocks\Abstracts;

use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Get;
use KLisica\FilamentBuilderBlocks\Interfaces\SectionBlockInterface;

class AbstractSectionProvider implements SectionBlockInterface
{
    public string $key = '';

    public string $name = '';

    public string $icon = '';

    public int $order = 1;

    public array $tags = [];

    public array $components = [];

    public function getKey(): string
    {
        return $this->key;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getIcon(): string
    {
        return $this->icon;
    }

    public function getOrder(): int
    {
        return $this->order;
    }

    public function getTags(): array
    {
        return $this->tags;
    }

    public function getSectionItems(): Builder\Block
    {
        $components = collect($this->components);

        $options = $components
            ->mapWithKeys(fn ($component) => [$component->getView() => $component->getName()])
            ->toArray();

        return Builder\Block::make($this->getKey())
            ->label($this->getName())
            ->icon($this->getIcon())
            ->schema([
                Hidden::make('block.class_path')->default($components->first()->getClassPath()),

                ToggleButtons::make('block.view_key')
                    ->label('Select option')
                    ->options($options)
                    ->default($components->first()->getView())
                    ->inline()
                    ->live()
                    ->afterStateUpdated(function (Get $get, callable $set) use ($components) {
                        $instance = $components->firstWhere('view', $get('block.view_key'));
                        $set('block.class_path', $instance->getClassPath());
                    }),

                // Dynamically import each component registered for this provider.
                ...$components
                    ->map(fn ($component) => $component
                        ->getFieldset()
                        ->visible(fn (Get $get): bool => $get('block.view_key') == $component->getView()),
                    )
                    ->toArray(),
            ]);
    }
}
