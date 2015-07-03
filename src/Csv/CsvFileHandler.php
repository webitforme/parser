<?php

namespace WebIt4Me\Reader\Csv;

use WebIt4Me\Reader\AbstractFileHandler;
use WebIt4Me\Reader\RowInterface;

class CsvFileHandler extends AbstractFileHandler
{
    const DELIMITER = ',';

    /**
     * Returns an array of all values in the next line or false if its end of the file
     * @return array|false
     */
    public function readLine()
    {
        return ($row = fgetcsv($this->handler, 1000, self::DELIMITER)) !== false ?
            $row :
            false;
    }

    /**
     * @param string[] $data
     * @return int
     */
    public function writeLine($data)
    {
        return fputcsv($this->handler, $data, self::DELIMITER);
    }

    /**
     * @param RowInterface $row
     * @return int
     */
    public function writeRow(RowInterface $row)
    {
        return $this->writeLine($row->toArray());
    }
}
