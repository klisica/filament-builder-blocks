<?php

namespace KLisica\FilamentBuilderBlocks\Sections\Heros;

use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use KLisica\FilamentBuilderBlocks\Abstracts\AbstractSectionItemProvider;

class ExampleHero extends AbstractSectionItemProvider
{
    public function __construct()
    {
        $this->view = 'heros.basic';
        $this->name = 'Basic Hero';
        $this->previewUrl = 'https://flowbite.com/blocks/marketing/hero/#default-hero-section';
    }

    // Get field with inputs for Filament panel.
    public function getFieldset(): Fieldset
    {
        return Fieldset::make($this->getName())
            ->schema([
                $this->getPreviewLink()->columnSpanFull(),

                Toggle::make('content.has_main_heading')
                    ->label('Use with main heading (<h1>)')
                    ->default(false),

                Toggle::make('content.is_centered')
                    ->label('Align to the center')
                    ->default(false),

                TextInput::make('content.heading')
                    ->placeholder('Write hero heading here...')
                    ->required()
                    ->maxLength(255)
            ]);
    }
}

