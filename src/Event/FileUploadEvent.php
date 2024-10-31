<?php

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploadEvent extends Event
{
    public const NAME = 'file.uploaded';

    private $file;

    public function __construct(UploadedFile $file)
    {
        $this->file = $file;
    }

    public function getFile(): UploadedFile
    {
        return $this->file;
    }
}
