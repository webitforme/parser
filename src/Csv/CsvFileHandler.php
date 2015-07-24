<?php

namespace WebIt4Me\Parser\Csv;

use WebIt4Me\Parser\AbstractFileHandler;
use WebIt4Me\Parser\RowInterface;

/**
 * Class CsvFileHandler
 *
 * @author Ali Bahman <abn@webit4.me>
 */
class CsvFileHandler extends AbstractFileHandler
{
    const DELIMITER = ',';

    /**
     * Return an array of all values in the next line or false if its end of the file
     *
     * @return array|false
     */
    public function readLine()
    {
        return ($row = fgetcsv($this->handler, 1000, self::DELIMITER)) !== false ?
            $row :
            false;
    }

    /**
     * Write the given array as comma separated values in the next line
     *
     * @param string[] $data
     * @return int
     */
    public function writeLine($data)
    {
        return fputcsv($this->handler, $data, self::DELIMITER);
    }

    /**
     * Write the given row as comma separated values in the next line
     *
     * @param RowInterface $row
     * @return int
     */
    public function writeRow(RowInterface $row)
    {
        return $this->writeLine($row->toArray());
    }

    public function getPointerPosition()
    {
        return ftell($this->handler);
    }

    /**
     * @return bool
     */
    public function isEndOfFile()
    {
        return feof($this->handler);
    }
}
