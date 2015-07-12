<?php

namespace WebIt4Me\Parser;

/**
 * Interface FileHandlerInterface
 *
 * @author Ali Bahman <abn@webit4.me>
 */
interface FileHandlerInterface
{
    /**
     * @param string $filePath
     * @param string $mode The mode parameter specifies the type of access you require to the stream.
     */
    public function __construct($filePath, $mode);


    /**
     * To pot the pointer in the beginning of the file
     */
    public function rewind();

    /**
     * @return string
     */
    public function readLine();

    /**
     * @param string $content
     */
    public function writeLine($content);
}

