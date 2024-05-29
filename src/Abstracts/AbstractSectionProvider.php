<?php

namespace KLisica\FilamentBuilderBlocks\Abstracts;

use KLisica\FilamentBuilderBlocks\Interfaces\SectionBlockInterface;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Get;

class AbstractSectionProvider implements SectionBlockInterface
{
    public string $key = '';

    public string $name = '';

    public string $icon = '';

    public int $order = 1;

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

    public function getBlocks(): Builder\Block
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
                    ->map(fn ($component) =>
                        $component
                            ->getFieldset()
                            ->visible(fn (Get $get): bool => $get('block.view_key') == $component->getView()),
                    )
                    ->toArray()
            ]);
    }
}


/*<?php

namespace App\Blocks\Providers;

use App\Blocks\AbstractBlockProvider;

class Hero extends AbstractBlockProvider
{
    public function __construct()
    {
        $this->key = 'hero';
        $this->name = 'Hero';
        $this->icon = 'polaris-image-with-text-overlay-icon';
        $this->order = 2;

        $this->components = [
            new \App\Blocks\Providers\Heros\BasicHero,
            new \App\Blocks\Providers\Heros\TextWithImage,
            new \App\Blocks\Providers\Heros\TextWithCarousel,
            new \App\Blocks\Providers\Heros\FullPageCarousel,
        ];
    }
}*/

