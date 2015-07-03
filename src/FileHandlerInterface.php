<?php

namespace WebIt4Me\Reader;

interface FileHandlerInterface
{
    /**
     * @param string $filePath
     * @param string $mode The mode parameter specifies the type of access you require to the stream.
     */
    public function __construct($filePath, $mode);

    /**
     * @return string
     */
    public function readLine();

    /**
     * @param string $content
     */
    public function writeLine($content);
}

