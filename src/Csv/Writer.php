<?php

namespace WebIt4Me\Reader\Csv;

use WebIt4Me\Reader\AbstractFileWriter;
use WebIt4Me\Reader\RowInterface;

class Writer extends AbstractFileWriter
{
    const DELIMITER = ',';

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