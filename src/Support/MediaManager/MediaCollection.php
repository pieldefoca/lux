<?php

namespace Pieldefoca\Lux\Support\MediaManager;

class MediaCollection
{
    public $singleFile = false;

    public $translatable = false;

    public function __construct(
        public string $name,
    ) {}

    public function singleFile()
    {
        $this->singleFile = true;

        return $this;
    }

    public function translatable()
    {
        $this->translatable = true;

        return $this;
    }

    public function isSingleFile()
    {
        return $this->singleFile;
    }

    public function isTranslatable()
    {
        return $this->translatable;
    }
}