<?php

namespace WebIt4Me\Reader;

abstract class AbstractFileWriter implements FileWriterInterface
{
    const LINE_LENGTH =4096;

    const ERR_MSG_FAILED_TO_OPEN_FILE = 'Failed to open "%s" to write';

    protected $handler;

    /**
     * @param string $filePath
     */
    public function __construct($filePath)
    {
        ini_set('auto_detect_line_endings', true);
        $this->openFile($filePath);
    }

    /**
     * @param string $filePath
     * @throws \Exception
     */
    protected function openFile($filePath)
    {
        if (is_null($this->handler)) {

            if (($handle = @fopen($filePath, "w")) === false) {
                throw new \Exception(sprintf(self::ERR_MSG_FAILED_TO_OPEN_FILE, $filePath));
            }

            $this->handler = $handle;

        }
    }

    public function writeLine($content)
    {
        return fputs($this->handler, $content);
    }
}
