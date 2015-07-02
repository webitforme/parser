<?php

namespace WebIt4Me\Reader;

interface FileWriterInterface
{
    /**
     * @param string $filePath
     */
    public function __construct($filePath);

    /**
     * @param string $content
     */
    public function writeLine($content);
}
