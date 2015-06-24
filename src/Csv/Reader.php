<?php

namespace WebIt4Me\Reader\Csv;


use WebIt4Me\Reader\AbstractFileReader;

class Reader extends AbstractFileReader{

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
}