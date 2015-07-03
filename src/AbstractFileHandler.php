<?php

namespace WebIt4Me\Reader;

/**
 * Class AbstractFileHandler
 *
 * @author Ali Bahman <abn@webit4.me>
 */
abstract class AbstractFileHandler implements FileHandlerInterface
{
    const LINE_LENGTH = 4096;

    const ERR_MSG_FAILED_TO_OPEN_FILE = 'Failed to open "%s" in "%s" mode';

    protected $handler;

    /**
     * {@inheritdoc }
     */
    public function __construct($filePath, $mode)
    {
        ini_set('auto_detect_line_endings', true);
        $this->openFile($filePath, $mode);
    }

    /**
     * @param string $filePath
     * @param string $mode The mode parameter specifies the type of access you require to the stream.
     * @throws \Exception
     */
    protected function openFile($filePath, $mode = "r")
    {
        if (is_null($this->handler)) {

            if (($handle = @fopen($filePath, $mode)) === false) {
                throw new \Exception(sprintf(self::ERR_MSG_FAILED_TO_OPEN_FILE, $filePath, $mode));
            }

            $this->handler = $handle;

        }
    }

    /**
     * @return string
     */
    public function readLine()
    {
        return fgets($this->handler, self::LINE_LENGTH);
    }

    /**
     * @param string $content
     * @return int
     */
    public function writeLine($content)
    {
        return fputs($this->handler, $content);
    }
}
