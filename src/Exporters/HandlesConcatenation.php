<?php

namespace Pbmedia\LaravelFFMpeg\Exporters;

use Pbmedia\LaravelFFMpeg\Filesystem\Media;

trait HandlesConcatenation
{
    protected bool $concatWithTranscoding = false;
    protected bool $concatWithVideo       = false;
    protected bool $concatWithAudio       = false;

    public function concatWithTranscoding($hasVideo = true, $hasAudio = true): self
    {
        $this->concatWithTranscoding = true;
        $this->concatWithVideo       = $hasVideo;
        $this->concatWithAudio       = $hasAudio;

        return $this;
    }

    private function addConcatFilterAndMapping(Media $outputMedia)
    {
        $sources = $this->driver->getMediaCollection()->map(function ($media, $key) {
            return "[{$key}]";
        });

        $concatWithVideo = $this->concatWithVideo ? 1 : 0;
        $concatWithAudio = $this->concatWithAudio ? 1 : 0;

        $this->addFilter(
            $sources->implode(''),
            "concat=n={$sources->count()}:v={$concatWithVideo}:a={$concatWithAudio}",
            '[concat]'
        )->addFormatOutputMapping($this->format, $outputMedia, ['[concat]']);
    }
}
