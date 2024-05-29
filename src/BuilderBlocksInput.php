<?php

namespace KLisica\FilamentBuilderBlocks;

use Filament\Forms\Components\Builder;
use KLisica\FilamentBuilderBlocks\Interfaces\BuilderBlockInterface;
use ReflectionClass;

class BuilderBlocksInput extends Builder implements BuilderBlockInterface
{
    private $configKey;

    // #TODO - Load somehow sections with blocks.
    public function sections(?string $configKey = null): Builder
    {
        $this->configKey = $configKey;

        $componentInstances = [];
        $classes = $this->getComponentClasses();

        foreach ($classes as $class) {
            $reflectionClass = new ReflectionClass($class);

            if (!$reflectionClass->isInstantiable()) { continue; }

            $instance = $reflectionClass->newInstance();

            if (method_exists($instance, 'getOrder') && method_exists($instance, 'getBlocks')) {
                $componentInstances[] = $instance;
            }
        }

        // Set correct component order.
        usort($componentInstances, fn ($a, $b) => $a->getOrder() <=> $b->getOrder());

        // Assign block elements.
        $this->blocks(array_map(fn ($instance) => $instance->getBlocks(), $componentInstances));

        return $this;
    }

    private function getConfig(): array
    {
        return config('filament-builder-blocks. ', $this->configKey)
            ?? [ 'components' => __DIR__ . '/Components' ];
    }

    private function getClassNameFromFile($file)
    {
        $contents = file_get_contents($file);
        $namespace = '';

        if (preg_match('/namespace\s+([^;]+);/', $contents, $matches)) {
            $namespace = $matches[1] . '\\';
        }

        $class = '';

        if (preg_match('/class\s+(\w+)/', $contents, $matches)) {
            $class = $matches[1];
        }

        return $namespace . $class;
    }

    private function getComponentClasses(?string $configKey = null)
    {
        $directory = $this->getConfig()['components'];

        if (!is_dir($directory)) { return []; }

        $files = glob($directory . '/*.php');
        $classes = [];

        foreach ($files as $file) {
            $className = $this->getClassNameFromFile($file);
            if (!class_exists($className)) { continue; }
            $classes[] = $className;
        }

        return $classes;
    }
}
