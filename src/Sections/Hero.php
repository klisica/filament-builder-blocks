<?php

namespace KLisica\FilamentBuilderBlocks\Sections;

use KLisica\FilamentBuilderBlocks\Abstracts\AbstractSectionProvider;

class Hero extends AbstractSectionProvider
{
    public function __construct()
    {
        $this->key = 'hero';
        $this->name = 'Hero Section';
        $this->icon = 'heroicon-s-star';
        $this->order = 1;

        $this->components = [
            new \KLisica\FilamentBuilderBlocks\Sections\Heros\ExampleHero,
        ];
    }
}
