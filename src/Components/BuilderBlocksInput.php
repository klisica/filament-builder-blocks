<?php

namespace KLisica\FilamentBuilderBlocks\Components;

use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Placeholder;
use ReflectionClass;

class BuilderBlocksInput extends Builder
{
    /**
     * Retrieves the sections based on the given configuration key and sets the correct component order.
     *
     * @param  array|null  $withTags  The array of tags to include in the sections. Defaults to null.
     * @param  bool|null  $withYieldSection  Whether to yield the sections. Defaults to false.
     * @return Builder The Builder instance with the assigned block elements.
     */
    public function sections(?array $withTags = [], ?bool $withYieldSection = false): Builder
    {
        $componentInstances = [];
        $classes = $this->getSectionClasses();

        foreach ($classes as $class) {
            $reflectionClass = new ReflectionClass($class);

            if (! $reflectionClass->isInstantiable()) {
                continue;
            }

            $instance = $reflectionClass->newInstance();

            if (
                method_exists($instance, 'getOrder')
                && method_exists($instance, 'getSectionItems')
                && method_exists($instance, 'getTags')
            ) {
                // Filter by tags.
                if (count($withTags) > 0) {
                    if (array_intersect($withTags, $instance->getTags())) {
                        $componentInstances[] = $instance;
                    }

                    continue;
                }

                $componentInstances[] = $instance;
            }
        }

        // Set correct component order.
        usort($componentInstances, fn ($a, $b) => $a->getOrder() <=> $b->getOrder());

        $blocks = array_map(fn ($instance) => $instance->getSectionItems(), $componentInstances);

        // Assign readonly yield block section.
        if ($withYieldSection) {
            $blocks = array_merge([
                Builder\Block::make('yield')
                    ->label(__('filament-builder-blocks::translations.yield'))
                    ->disabled()
                    ->schema([
                        Placeholder::make('yield')->label(__('filament-builder-blocks::translations.yield_placeholder')),
                    ]),
            ], $blocks);
        }

        // Assign block elements.
        $this->blocks($blocks);

        return $this;
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
     * @return array The array of class names of the section files.
     */
    private function getSectionClasses()
    {
        if (! is_dir(config('filament-builder-blocks.path'))) {
            return [];
        }

        $files = glob(config('filament-builder-blocks.path').'/*.php');
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
