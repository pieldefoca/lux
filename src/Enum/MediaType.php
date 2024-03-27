<?php

namespace Pieldefoca\Lux\Enum;

enum MediaType: string
{
    case Image = 'image';
    case Video = 'video';
    case File = 'file';
    case Pdf = 'pdf';

    public function forHumans(): string
    {
        return match($this) {
            self::Image => 'Imagen',
            self::Video => 'Vídeo',
            self::File => 'Fichero',
            self::Pdf => 'PDF',
        };
    }

    public static function fromMimeType($mimeType)
    {
        $mimeTypeStr = str($mimeType);

        if($mimeTypeStr->startsWith('image')) {
            return self::Image;
        } elseif($mimeTypeStr->startsWith('video')) {
            return self::Video;
        } elseif($mimeTypeStr->contains('pdf')) {
            return self::Pdf;
        } else {
            return self::File;
        }
    }
}
