<?php

namespace KLisica\FilamentBuilderBlocks;

class FilamentBuilderHelpers
{
    /**
     * Retrieves the rendered view for a given section.
     *
     * @param array $section The section data containing the block class path and content.
     * @param array $configs (optional) The configuration data to be merged with the section content.
     * @return string|null The rendered view as a string, or null if the class does not exist.
     */
    public function getRenderedView(array $section, array $configs = []): string | null
    {
        $className = @$section['data']['block']['class_path'] ?? null;

        if (!$className || !class_exists($className)) {
            return null;
        }

        $inst = new $className();
        $sectionData = array_merge(@$section['data']['content'] ?? [], $configs ?? []);
        return view($inst->getView(), $sectionData)->render();
    }
}
