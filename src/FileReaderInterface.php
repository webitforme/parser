<?php

namespace WebIt4Me\Reader;

interface FileReaderInterface
{
    /**
     * @param $filePath
     */
    public function __construct($filePath);

    /**
     * @return string
     */
    public function readLine();

    /**
     * @return null
     */
    public function closeFile();
}
