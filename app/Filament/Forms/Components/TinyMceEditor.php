<?php

namespace App\Filament\Forms\Components;

use Filament\Forms\Components\Field;

class TinyMceEditor extends Field
{
    protected string $view = 'filament.forms.components.tiny-mce-editor';

    protected string|\Closure|null $uploadImageUrl = null;

    protected string|\Closure|null $uploadFileUrl = null;

    public function uploadImageUrl(string|\Closure $url): static
    {
        $this->uploadImageUrl = $url;
        return $this;
    }

    public function uploadFileUrl(string|\Closure $url): static
    {
        $this->uploadFileUrl = $url;
        return $this;
    }

    public function getUploadImageUrl(): ?string
    {
        return $this->evaluate($this->uploadImageUrl);
    }

    public function getUploadFileUrl(): ?string
    {
        return $this->evaluate($this->uploadFileUrl);
    }
}

