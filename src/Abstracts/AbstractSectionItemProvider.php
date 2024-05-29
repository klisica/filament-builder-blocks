<?php

namespace KLisica\FilamentBuilderBlocks\Abstracts;

use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Placeholder;
use Illuminate\Support\HtmlString;
use KLisica\FilamentBuilderBlocks\Interfaces\SectionItemInterface;

class AbstractSectionItemProvider extends AbstractSectionProvider implements SectionItemInterface
{
    public string $view = '';

    public ?string $previewUrl = null;

    public function getView(): string
    {
        return $this->view;
    }

    public function getClassPath(): string
    {
        return get_called_class();
    }

    // Get field with inputs for Filament panel.
    public function getFieldset(): Fieldset
    {
        return Fieldset::make($this->name)->schema([]);
    }

    // Preview URL on Flowbite page.
    public function getPreviewLink(): Placeholder | null
    {
        return $this->previewUrl
            ? Placeholder::make('preview')->hiddenLabel()
                ->content(function () {
                    $url = $this->previewUrl;
                    return new HtmlString('<a href="' . $url . '" target="_blank"><u>Show Component</u></a>');
                })
            : null;
    }
}

