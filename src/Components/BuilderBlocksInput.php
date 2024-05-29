<?php

namespace KLisica\FilamentBuilderBlocks\Components;

use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Placeholder;
use ReflectionClass;

class BuilderBlocksInput extends Builder
{
    private $configKey;

    /**
     * Retrieves the sections based on the given configuration key and sets the correct component order.
     *
     * @param  string|null  $configKey  The configuration key to retrieve the sections. Defaults to null.
     * @param  bool|null  $withYieldSection  Whether to yield the sections. Defaults to false.
     * @return Builder The Builder instance with the assigned block elements.
     */
    public function sections(?string $configKey = null, ?bool $withYieldSection = false): Builder
    {
        $this->configKey = $configKey;

        $componentInstances = [];
        $classes = $this->getSectionClasses();

        foreach ($classes as $class) {
            $reflectionClass = new ReflectionClass($class);

            if (! $reflectionClass->isInstantiable()) {
                continue;
            }

            $instance = $reflectionClass->newInstance();

            if (method_exists($instance, 'getOrder') && method_exists($instance, 'getSectionItems')) {
                $componentInstances[] = $instance;
            }
        }

        // Set correct component order.
        usort($componentInstances, fn ($a, $b) => $a->getOrder() <=> $b->getOrder());

        $blocks = array_map(fn ($instance) => $instance->getSectionItems(), $componentInstances);

        // Assign readonly yield block section.
        if ($withYieldSection) {
            $blocks = array_merge($blocks, [
                Builder\Block::make('yield')
                    ->label('⭐ Yield Section')
                    ->disabled()
                    ->schema([
                        Placeholder::make('yield')->label('Here is where your nested sections will be displayed.'),
                    ]),
            ]);
        }

        // Assign block elements.
        $this->blocks($blocks);

        return $this;
    }

    /**
     * Retrieves the configuration for the 'filament-builder-blocks' package.
     *
     * @return array The configuration array for the 'filament-builder-blocks' package.
     */
    private function getConfig(): array
    {
        return config("filament-builder-blocks.$this->configKey")
            ?? ['sections' => __DIR__.'/../Sections'];
    }

    /**
     * Retrieves the class name from a given file.
     *
     * @param  string  $file  The path to the file.
     * @return string The fully qualified class name.
     */
    private function getClassNameFromFile($file)
    {
        $contents = file_get_contents($file);
        $namespace = '';

        if (preg_match('/namespace\s+([^;]+);/', $contents, $matches)) {
            $namespace = $matches[1].'\\';
        }

        $class = '';

        if (preg_match('/class\s+(\w+)/', $contents, $matches)) {
            $class = $matches[1];
        }

        return $namespace.$class;
    }

    /**
     * Retrieves the classes of the section files based on the given configuration key.
     *
     * @param  string|null  $configKey  The configuration key to retrieve the section classes. Defaults to null.
     * @return array The array of class names of the section files.
     */
    private function getSectionClasses(?string $configKey = null)
    {
        $directory = $this->getConfig()['sections'];

        if (! is_dir($directory)) {
            return [];
        }

        $files = glob($directory.'/*.php');
        $classes = [];

        foreach ($files as $file) {
            $className = $this->getClassNameFromFile($file);

            if (class_exists($className)) {
                $classes[] = $className;
            }
        }

        return $classes;
    }
}
