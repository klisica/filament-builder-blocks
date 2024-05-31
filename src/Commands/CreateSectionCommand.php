<?php

namespace KLisica\FilamentBuilderBlocks\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;


class CreateSectionCommand extends Command
{
    protected $signature = 'make:section {name}';
    protected $description = 'Create a new section with a given name';

    protected $files;

    public function handle()
    {
        $name = $this->argument('name');
        $sectionDirectory = config('filament-builder-blocks.path');
        $componentDirectory = config('filament-builder-blocks.path') . "/$name";
        $viewDirectory = resource_path('views/sections/' . strtolower($name));

        if (File::exists($componentDirectory)) {
            $this->error('Section components already exists!');
            return 1;
        }

        // Create directories
        File::makeDirectory($componentDirectory, 0755, true);
        File::makeDirectory($viewDirectory, 0755, true);

        // Create files from stubs
        $this->createSectionProvider($name, $sectionDirectory);
        $this->createComponentProvider($name, $componentDirectory);
        $this->createViewFile($name, $viewDirectory);

        $this->info('Section created successfully.');
        return 0;
    }

    protected function createSectionProvider($name, $directory)
    {
        $stub = File::get(__DIR__ . '/../../resources/stubs/section-provider.stub');
        $stub = str_replace('{{name}}', $name, $stub);
        $filePath = $directory . "/$name.php";
        File::put($filePath, $stub);
    }

    protected function createComponentProvider($name, $directory)
    {
        $stub = File::get(__DIR__ . '/../../resources/stubs/section-block-provider.stub');
        $stub = str_replace('{{name}}', $name, $stub);
        $stub = str_replace('{{view}}', 'sections.' . strtolower($name) . '.example-' . strtolower($name), $stub);
        $filePath = $directory . "/Example$name.php";
        File::put($filePath, $stub);
    }

    protected function createViewFile($name, $directory)
    {
        $content = <<<'BLADE'
            @php
                $headingElement = @$has_main_heading ? 'h1' : 'h2';
            @endphp

            <section>
                <div @if (@$is_centered) style="text-align: center;" @endif>
                    <{{ $headingElement }}>
                        {{ $heading }}
                    </{{ $headingElement }}>
                </div>
            </section>
            BLADE;

            $filePath = $directory . '/example-' . strtolower($name) . '.blade.php';
            File::put($filePath, $content);
        }

}
