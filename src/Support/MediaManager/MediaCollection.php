<?php

namespace Pieldefoca\Lux\Support\MediaManager;

class MediaCollection
{
    public $singleFile = false;

    public function __construct(
        public string $name,
    ) {}

    public function singleFile()
    {
        $this->singleFile = true;

        return $this;
    }

    public function isSingleFile()
    {
        return $this->singleFile;
    }
}