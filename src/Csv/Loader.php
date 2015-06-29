<?php

namespace WebIt4Me\Reader\Csv;

use WebIt4Me\Reader\AbstractFileReader;
use WebIt4Me\Reader\IterableTrait;
use WebIt4Me\Reader\LoaderInterface;
use WebIt4Me\Reader\RowInterface;

/**
 * Class Loader
 * @package WebIt4Me\Reader\Csv
 *
 * @property-read Row[] $iterable handles in IterableTrait
 * @see IterableTrait
 */
class Loader implements LoaderInterface
{
    use IterableTrait;

    const ERR_MSG_ROW_BAD_OFFSET = 'There is no index %d since the are only %d rows in the CSV file';

    /** @var string */
    private $filePath;

    /** @var Reader */
    private $fileReader;

    /** @var  array */
    private $columnNames;

    /**
     * @param string $filePath
     */
    public function __construct($filePath)
    {
        $this->filePath = $filePath;
        $this->setColumnNames();
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
        $nextLine = $this->getFileReader()->readLine();

        if (false === $nextLine) {
            return false;
        }

        $row = new Row(
            count($this->iterable),
            $nextLine,
            $this->getColumnNames()
        );

        $this->iterable[] = $row;

        return $row;
    }

    /**
     * @param int $rowIndex
     * @return RowInterface
     */
    public function readRowAt($rowIndex)
    {
        if (isset($this->iterable[$rowIndex])) {
            return $this->iterable[$rowIndex];
        }

        $counter = count($this->iterable);
        while (($row = $this->readRow()) !== false) {
            if ($rowIndex === $counter) {
                return $row;
            }
            $counter++;
        }

        throw new \OutOfRangeException(sprintf(self::ERR_MSG_ROW_BAD_OFFSET, $rowIndex, count($this->iterable)));
    }

    /**
     * @param string $keyword
     * @return RowInterface[]
     */
    public function search($keyword)
    {
        $this->loadAllIfNotYet();

        $matchingRows = [];
        foreach ($this->iterable as $row) {
            foreach ($row as $column) {
                if (strpos($column->getValue(), $keyword)) {
                    $matchingRows[$row->getIndex()] = $row;
                }
            }
        }

        return $matchingRows;
    }

    /**
     * @return RowInterface[]
     */
    public function readAll()
    {
        while ($this->readRow() !== false) ;

        return $this->iterable;
    }

    private function loadAllIfNotYet()
    {
        if (count($this->iterable) === 0) {
            $this->readAll();
        }
    }

    /**
     * This method has to be run first think before reading/storing any other line
     */
    private function setColumnNames()
    {
        $this->columnNames = $this->getFileReader()->readLine();
    }

    private function getFileReader()
    {
        if (is_null($this->fileReader)) {
            $this->fileReader = new Reader($this->filePath);
        }
        return $this->fileReader;
    }
}
