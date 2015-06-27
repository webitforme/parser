<?php

namespace WebIt4Me\Reader\Csv;

use WebIt4Me\Reader\LoaderInterface;
use WebIt4Me\Reader\RowInterface;

class Loader implements LoaderInterface
{
    const DELIMITER = ',';

    const ERR_MSG_FAILED_TO_OPEN_FILE = 'Failed to open "%s" to read';
    const ERR_MSG_ROW_BAD_OFFSET = 'There is no index %d since the are only %d rows in the CSV file';

    private $handler;

    /** @var int */
    private $pointer = 0;

    /** @var Row[] */
    private $rows;

    /** @var string */
    private $filePath;

    /** @var boolean */
    private $firstLineIsFiledName;

    /** @var  array */
    private $columnNames;

    /**
     * @param string $filePath
     */
    public function __construct($filePath, $firstLineIsFiledName = true)
    {
        ini_set('auto_detect_line_endings', true);

        $this->filePath = $filePath;
        $this->firstLineIsFiledName = $firstLineIsFiledName;

        $this->openFile();

        if (true === $this->firstLineIsFiledName) {
            $this->setColumnNames();
        }
    }

    /**
     * @return string[]|null
     */
    public function getColumnNames()
    {
        return $this->columnNames;
    }

    /**
     * @return RowInterface
     */
    public function readRow()
    {
        $nextLine = $this->readLine();

        if (false === $nextLine) {
            return false;
        }

        $row = new Row(
            count($this->rows),
            $nextLine,
            $this->getColumnNames()
        );

        $this->rows[] = $row;

        return $row;
    }

    /**
     * @param int $rowIndex
     * @return RowInterface
     */
    public function readRowAt($rowIndex)
    {
        if (isset($this->rows[$rowIndex])) {
            return $this->rows[$rowIndex];
        }

        $counter = count($this->rows);
        while (($row = $this->readRow()) !== false){
            if ($rowIndex === $counter) {
                return $row;
            }
            $counter++;
        }

        throw new \OutOfRangeException(sprintf(self::ERR_MSG_ROW_BAD_OFFSET, $rowIndex, count($this->rows)));
    }

    /**
     * @param string $keyword
     * @return RowInterface
     */
    public function findRow($keyword)
    {
        // TODO: Implement findRow() method.
    }

    /**
     * @param string $keyword
     * @return RowInterface[]
     */
    public function findAllRow($keyword)
    {
        $this->loadAllIfNotYet();

        $rowsWithMatchingValue = [];
        foreach ($this->rows as $row) {
            foreach ($row as $column) {
                if (strpos($column->getValue(), $keyword)) {
                    $rowsWithMatchingValue[$row->getIndex()] = $row;
                }
            }
        }

        return $rowsWithMatchingValue;
    }

    /**
     * @return RowInterface[]
     */
    public function readAll()
    {
        while ($this->readRow() !== false){
            $a = 1;
        }

        return $this->rows;
    }

    /**
     * Return the current row
     * @link http://php.net/manual/en/iterator.current.php
     * @return Row
     */
    public function current()
    {
        return $this->rows[$this->pointer];
    }

    /**
     * Move forward to next row
     * @link http://php.net/manual/en/iterator.next.php
     */
    public function next()
    {
        $this->pointer++;
    }

    /**
     * Return the key of the current row
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     */
    public function key()
    {
        return $this->pointer;
    }

    /**
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean
     */
    public function valid()
    {
        $this->loadAllIfNotYet();

        return isset($this->rows[$this->pointer]);
    }

    private function loadAllIfNotYet()
    {
        if (count($this->rows) === 0 ) {
            $this->readAll();
        }
    }
    /**
     * Rewind the Iterator to the first row
     * @link http://php.net/manual/en/iterator.rewind.php
     */
    public function rewind()
    {
        $this->pointer = 0;
    }

    /**
     * This method has to be run first think before reading/storing any other line
     */
    private function setColumnNames()
    {
        $this->columnNames = $this->readLine();
    }

    /**
     * Returns an array of all values in the next line or false if its end of the file
     * @return array|false
     */
    private function readLine()
    {
        return ($row = fgetcsv($this->handler, 1000, self::DELIMITER)) !== false ?
            $row :
            false;
    }

    /**
     * @throws \Exception
     */
    private function openFile()
    {
        if (($handle = fopen($this->filePath, "r")) === false) {
            throw new \Exception(sprintf(self::ERR_MSG_FAILED_TO_OPEN_FILE, $this->filePath));
        }

        $this->handler = $handle;
    }

    private function closeFile()
    {
        if (!is_null($this->handler)) {
            fclose($this->handler);
        }
    }


    function __destruct()
    {
        $this->closeFile();
    }
}
