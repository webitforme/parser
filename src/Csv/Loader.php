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

    /** @var bool */
    private $reachedToLastLine = false;

    /**
     * @param string $filePath
     */
    public function __construct($filePath)
    {
        $this->filePath = $filePath;
        $this->setColumnNames();
        $this->readAllRows();
    }

    /**
     * @return string[]|null
     */
    public function getColumnNames()
    {
        return $this->columnNames;
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

        throw new \OutOfRangeException(sprintf(self::ERR_MSG_ROW_BAD_OFFSET, $rowIndex, count($this->iterable)));
    }

    /**
     * @param string|array $searchParams
     * @return RowInterface[]
     */
    public function search($searchParams)
    {
        $matchingRows = [];
        foreach ($this->iterable as $row) {

            if (is_array($searchParams)) {
                if ($this->hasMatchOnSpecificColumns($row, $searchParams)) {
                    $matchingRows[] = $row;
                }
            } else {
                if ($this->hasMatchOnAnyColumn($row, $searchParams)) {
                    $matchingRows[] = $row;
                }
            }
        }

        return $matchingRows;
    }

    /**
     * @return RowInterface[]
     */
    public function readAllRows()
    {
        while (($nextLine = $this->getFileReader()->readLine()) !== false) {
            $row = new Row(
                count($this->iterable),
                $nextLine,
                $this->getColumnNames()
            );
            $this->iterable[] = $row;
        }

        return $this->iterable;
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


    /**
     * @param RowInterface $row
     * @param array $searchParams
     * @return bool
     */
    private function hasMatchOnAnyColumn($row, $searchParams)
    {
        foreach ($row as $column) {
            if (strpos($column->getValue(), $searchParams) !== false) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param RowInterface $row
     * @param array $searchParams
     * @return bool
     */
    private function hasMatchOnSpecificColumns($row, $searchParams)
    {
        foreach ($searchParams as $name => $value) {

            if (strpos($row->getColumn($name)->getValue(), $value) !== false) {
                return true;
            }
        }

        return false;
    }
}
