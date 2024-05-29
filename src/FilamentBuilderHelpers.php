<?php

namespace KLisica\FilamentBuilderBlocks;

use Filament\Forms\Components\Component;
use Filament\Forms\Components\Fieldset;

class FilamentBuilderHelpers
{
    /**
     * Retrieves the rendered view for a given section.
     *
     * @param  array  $section  The section data containing the block class path and content.
     * @param  array  $configs  (optional) The configuration data to be merged with the section content.
     * @return string|null The rendered view as a string, or null if the class does not exist.
     */
    public function getRenderedView(array $section, array $configs = []): ?string
    {
        $className = @$section['data']['block']['class_path'] ?? null;

        if (! $className || ! class_exists($className)) {
            return null;
        }

        $inst = new $className();
        $sectionData = array_merge(@$section['data']['content'] ?? [], $configs);
        return view($inst->getView(), $sectionData)->render();
    }

    /**
     * Retrieves cleaned up inputs from a given Fieldset and data array.
     *
     * @param  Fieldset  $fieldset  The Fieldset object containing the inputs.
     * @param  array  $data  The data array containing the content.
     * @return array The cleaned up inputs.
     */
    public function getCleanInputs(Fieldset $fieldset, array $data): array
    {
        // Input names to keep.
        $inputs = $this->getInputsFromComponent($fieldset);

        // Cleaned up content.
        $clean = $this->getFieldsetContent($inputs, $data);

        return $this->expandArray($clean);
    }

    /**
     * Retrieves inputs from the given component, including child components recursively.
     *
     * @param  Component  $component  The component to retrieve inputs from.
     * @return array The array of inputs extracted from the component and its children.
     */
    public function getInputsFromComponent(Component $component): array
    {
        $inputs = [];

        $children = $component->getChildComponents();

        if (count($children) > 0) {
            foreach ($children as $child) {
                $inputs = array_merge(
                    $inputs,
                    $this->getInputsFromComponent($child)
                );
            }
        } else if (method_exists($component, 'getName')) {
            $inputs[] = $component->getName();
        }

        return $inputs;
    }

    /**
     * Retrieves the content from a fieldset array, filtering out unwanted values.
     *
     * @param  array  $content  The fieldset array containing the content.
     * @param  string|null  $prefix  An optional prefix to prepend to the keys.
     * @return array The filtered content array.
     */
    public function getFieldsetContent(array $inputs, array $content, ?string $prefix = null): array
    {
        $vals = [];

        foreach ($content as $key => $value) {
            $formattedKey = $prefix ? "$prefix.$key" : $key;

            if (is_array($value) || is_object($value)) {
                $cleanedValue = $this->getFieldsetContent($inputs, $value, $formattedKey);

                // Remove null, empty strings, and empty arrays
                $cleanedValue = array_filter($cleanedValue, function ($item) {
                    return ! is_null($item) && $item !== '' && ! empty($item);
                });

                if (! empty($cleanedValue)) {
                    // Merge arrays to avoid unnecessary depth and repetition
                    $vals = array_merge($vals, $cleanedValue);
                }
            } else {
                // Directly assign value to the key if it's not an array/object
                // Check if the key should be included based on specific conditions, if any
                $keyExists = collect($inputs)->contains(fn ($value) => stripos($value, $key) !== false);

                if ($keyExists) {
                    $vals[$formattedKey] = $value;
                }
            }
        }

        return $vals;
    }

    /**
     * Expand a dot-notation array into a nested array structure.
     *
     * @param  array  $array  The dot-notation array to expand.
     * @return array The expanded nested array structure.
     */
    public function expandArray(array $array)
    {
        $result = [];

        foreach ($array as $key => $value) {
            // Split the dot notated key into parts (levels)
            $keys = explode('.', $key);

            // Start building the nested array
            $temp = &$result;

            // Process each part of the key
            foreach ($keys as $part) {
                // If the key part doesn't exist at this depth, create an array to hold further values
                // @phpstan-ignore-next-line
                if (!isset($temp[$part])) {
                    $temp[$part] = [];
                }
                // Move the reference deeper into the array
                $temp = &$temp[$part];
            }

            // Set the final value
            $temp = $value;
        }

        return $result;
    }
}
