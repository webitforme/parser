<?php

namespace WebIt4Me\Parser\Csv;

use WebIt4Me\Parser\AbstractFileReader;
use WebIt4Me\Parser\FileHandlerInterface;
use WebIt4Me\Parser\IterableTrait;
use WebIt4Me\Parser\ParserInterface;
use WebIt4Me\Parser\RowInterface;

/**
 * Class Parser
 *
 * @property-read Row[] $iterable handles in IterableTrait
 * @see IterableTrait
 *
 * @author Ali Bahman <abn@webit4.me>
 */
class Parser implements ParserInterface
{
    use IterableTrait;

    const ERR_MSG_ROW_BAD_OFFSET = 'There is no index %d since the are only %d rows in the CSV file';

    /** @var CsvFileHandler */
    private $fileHandler;

    /** @var  array */
    private $columnNames;

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
     * Return an array of all the column names in the subject CSV
     *
     * @return string[]
     */
    public function getColumnNames()
    {
        return $this->columnNames;
    }

    /**
     * Retrieve a row by its index
     *
     * @param int $index
     * @return RowInterface
     */
    public function getRow($index)
    {
        if (isset($this->iterable[$index])) {
            return $this->iterable[$index];
        }

        throw new \OutOfRangeException(sprintf(self::ERR_MSG_ROW_BAD_OFFSET, $index, count($this->iterable)));
    }

    /**
     * Return all the existing rows as an array
     *
     * @return RowInterface[]
     */
    public function getRows()
    {
        return $this->iterable;
    }

    /**
     * Search and return array of matching rows
     * possible search parameters:
     * - string : to be searched on all the existing columns
     *            e.g. 'something to lookup'
     * - array :
     *     - without Keys : to check all its elements values against all the existing columns
     *                      e.g. ['Book','Table']
     *                           to look for 'Book' and 'Table' in the all columns
     *     - with keys    : to look up column names, match with the array keys for the ke value
     *                      e.g. ['Product' => 'Ball', 'Size' => 'Small']
     *                           to find a rows with word 'Ball' in their 'Product' column and 'Small' in their 'Size' column
     *                      e.g. ['Product'' => ['Ball', 'Box']]
     *                           to find rows with word 'Ball' or 'Box' in their 'Product' column
     *
     * @param array|string $searchParams
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
     * Helper to search based on the given string
     *
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
     * Helper to search based on the given array
     *
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

    /**
     * This method has to be run first thing and before readAllRows() any other line
     */
    private function setColumnNames()
    {
        $this->columnNames = $this->fileHandler->readLine();
    }

    /**
     * To go through all lines in the CSV file,
     * convert them to Row object and inject them into the Parser's iterable array
     */
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
}
