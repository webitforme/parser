<?php

namespace WebIt4Me\Reader\Csv;

use WebIt4Me\Reader\AbstractFileReader;
use WebIt4Me\Reader\FileHandlerInterface;
use WebIt4Me\Reader\IterableTrait;
use WebIt4Me\Reader\ParserInterface;
use WebIt4Me\Reader\RowInterface;

/**
 * Class Parser
 * @package WebIt4Me\Reader\Csv
 *
 * @property-read Row[] $iterable handles in IterableTrait
 * @see IterableTrait
 */
class Parser implements ParserInterface
{
    use IterableTrait;

    const ERR_MSG_ROW_BAD_OFFSET = 'There is no index %d since the are only %d rows in the CSV file';

    /** @var CsvFileHandler */
    private $fileHandler;

    /** @var  array */
    private $columnNames;

    /** @var bool */
    private $reachedToLastLine = false;

    /**
     * @param FileHandlerInterface $fileHandler
     */
    public function __construct(FileHandlerInterface $fileHandler)
    {
        $this->fileHandler = $fileHandler;
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
    public function getRow($rowIndex)
    {
        if (isset($this->iterable[$rowIndex])) {
            return $this->iterable[$rowIndex];
        }

        throw new \OutOfRangeException(sprintf(self::ERR_MSG_ROW_BAD_OFFSET, $rowIndex, count($this->iterable)));
    }

    /**
     * @return RowInterface[]
     */
    public function getRows()
    {
        return $this->iterable;
    }

    private function readAllRows()
    {
        $this->iterable = [];
        while (($nextLine = $this->fileHandler->readLine()) !== false) {
            $row = new Row(
                count($this->iterable),
                $nextLine,
                $this->getColumnNames()
            );
            $this->iterable[] = $row;
        }
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
            }

            if (!is_array($searchParams)) {
                if ($this->hasMatchOnAnyColumn($row, $searchParams)) {
                    $matchingRows[] = $row;
                }
            }
        }

        return $matchingRows;
    }

    /**
     * This method has to be run first think before reading/storing any other line
     */
    private function setColumnNames()
    {
        $this->columnNames = $this->fileHandler->readLine();
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

            if (is_array($value)) {

                foreach ($value as $v) {

                    if (strpos($row->getColumn($name)->getValue(), $v) !== false) {
                        return true;
                    }

                }

            }

            if (!is_array($value)) {

                if (strpos($row->getColumn($name)->getValue(), $value) !== false) {
                    return true;
                }

            }

        }

        return false;
    }
}
