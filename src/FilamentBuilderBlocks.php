<?php

namespace KLisica\FilamentBuilderBlocks;

use KLisica\FilamentBuilderBlocks\Components\BuilderBlocksInput;

class FilamentBuilderBlocks
{
    private $helper;

    public function __construct()
    {
        $this->helper = new FilamentBuilderHelpers;
    }

    /**
     * Creates a new instance of the BuilderBlocksInput class with the given name and sections,
     * and returns the result of calling the `make`, `sections`, and `columnSpanFull` methods on it.
     *
     * @param string $name The name of the BuilderBlocksInput instance.
     * @param string|null $sections The sections to be passed to the `sections` method. Defaults to null.
     * @param string|null $addActionLabel Button text for adding another section to the block. Defaults to null.
     * @param bool|null $withYieldSection Whether to yield the section view. Defaults to false.
     * @return mixed The result of calling the `make`, `sections`, and `columnSpanFull` methods on the BuilderBlocksInput instance.
     */
    public static function make(string $name, ?string $sections = null, ?string $addActionLabel = null, ?bool $withYieldSection = false)
    {
        $builderInput = new BuilderBlocksInput($name);
        return $builderInput->make($name)
            ->sections(configKey: $sections, withYieldSection: $withYieldSection) // Plugin block components.
            ->columnSpanFull()
            ->hiddenLabel()
            ->blockNumbers(true)
            ->collapsible()
            ->required()
            ->cloneable()
            ->addActionLabel($addActionLabel);
    }

    /**
     * Cleans up the inputs in the provided data array by filtering and expanding content based on class path.
     *
     * @param array $data The data array containing content to be cleaned up.
     * @return array The cleaned up data array.
     */
    public function cleanup(array $data): array
    {
        $contentData = @$data['content'] ?? [];

        foreach ($contentData as $index => $content) {
            if ($content['type'] == 'yield') {
                continue;
            }

            $className = @$content['data']['block']['class_path'] ?? '';
            if (!class_exists($className)) {  continue; }

            $inst = new $className();

            $data['content'][$index]['data']['content'] = $this->helper->getCleanInputs(
                $inst->getFieldset(),
                $content['data']['content']
            );
        }

        return $data;
    }

    /**
     * Renders the given sections and wrapping sections into HTML components.
     *
     * @param array $sections The sections to be rendered. Each section should have a 'data' key with a 'block' key
     *                        containing a 'class_path' key pointing to the class name of the section block.
     * @param array|null $wrappingSections The wrapping sections to be rendered. Each section should have a 'type' key
     *                                    with a value of 'yield' to indicate that the section should yield the
     *                                    previously rendered sections.
     * @param array|null $configs The configuration data to be merged with the section data.
     * @return array The rendered HTML components.
     */
    public function renderSections(array $sections = [], ?array $wrappingSections = [], ?array $configs = []): array
    {
        $htmlComponents = [];

        foreach ($sections as $section) {
            $view = $this->helper->getRenderedView($section, $configs);
            if ($view) { $htmlComponents[] = $view; }
        }

        // Setup wrapping sections.
        if(count($wrappingSections) > 0) {
            $htmlWrappingComponents = [];

            foreach ($wrappingSections as $section) {
                // Prepare yieldable content.
                if ($section['type'] == 'yield') {
                    $htmlWrappingComponents = array_merge($htmlWrappingComponents, $htmlComponents);
                    continue;
                }

                $view = $this->helper->getRenderedView($section, $configs);
                if ($view) { $htmlWrappingComponents[] = $view; }
            }

            $htmlComponents = $htmlWrappingComponents;
        }

        return $htmlComponents;
    }
}
