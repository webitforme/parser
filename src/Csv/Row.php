<?php

namespace WebIt4Me\Reader\Csv;

use WebIt4Me\Reader\ColumnInterface;
use WebIt4Me\Reader\ColumnsInterface;
use WebIt4Me\Reader\IndexableTrait;
use WebIt4Me\Reader\RowInterface;

class Row implements RowInterface
{
    use IndexableTrait;

    const ERR_MSG_BAD_NAME = 'There is no column named %s in the row.';
    const ERR_MSG_BAD_INDEX = '%s is an invalid index, since this row only hold % columns';

    /** @var array */
    private $columnNames;

    /** @var int */
    private $pointer = 0;

    /** @var Column[] */
    private $columns = [];

    /**
     * @param int $index
     * @param $columnValues
     * @param array|null $columnNames
     */
    public function __construct($index, $columnValues, $columnNames = null)
    {
        $this->setIndex($index);

        $this->columnNames = $columnNames;

        $this->setColumns($columnValues);
    }

    /**
     * To return an zero based array to map column index with their name (title).
     * i.e. [0 => 'First Column', 1 => 'And the second']
     * @return null|array
     */
    public function getColumnNames()
    {
        return $this->columnNames;
    }


    /**
     * Return row's all columns
     * @return ColumnInterface[]
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * Return the column with the matching name
     *
     * @param string $columnName
     * @return ColumnInterface
     * @throw \OutOfRangeException
     */
    public function getColumn($columnName)
    {
        foreach ($this as $column) {
            if ($column->getName() === $columnName){
                return $column;
            }
        }

        throw new \OutOfRangeException (sprintf(self::ERR_MSG_BAD_NAME, $columnName));
    }

    /**
     * Return a column based on its name or null if column with the given name is not exist
     * @param int $columnIndex
     * @return ColumnInterface
     */
    public function getColumnAt($columnIndex)
    {
        if (!isset($this->columns[$columnIndex])) {
            throw new \OutOfRangeException (sprintf(self::ERR_MSG_BAD_NAME, $columnIndex, $this->count()));
        }

        return $this->columns[$columnIndex];
    }


    /**
     * @link http://php.net/manual/en/iterator.current.php
     * @return Column.
     */
    public function current()
    {
        return $this->columns[$this->pointer];
    }

    /**
     * @link http://php.net/manual/en/iterator.next.php
     */
    public function next()
    {
        $this->pointer++;
    }

    /**
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return int scalar on success, or null on failure.
     */
    public function key()
    {
        return $this->pointer;
    }

    /**
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     */
    public function valid()
    {
        return isset($this->columns[$this->pointer]);
    }

    /**
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     */
    public function rewind()
    {
        $this->pointer = 0;
    }

    /**
     * Count row's columns
     * @link http://php.net/manual/en/countable.count.php
     * @return int
     */
    public function count()
    {
        return count($this->columns);
    }


    /**
     * Receives a list of values for all columns and create Column object for each and store it
     * @param array $columnValues
     */
    private function setColumns($columnValues)
    {
        $counter = 0;
        foreach ($columnValues as $columnValue) {
            $column = new Column(
                $counter,
                $columnValue,
                $this->getColumnNameForIndex($counter));

            $this->columns[$counter] = $column;
            $counter++;
        }

    }

    /**
     * Return name only if one been provided
     * @param int $index
     * @return string|null
     */
    private function getColumnNameForIndex($index)
    {
        return (is_array($this->getColumnNames()) && isset($this->getColumnNames()[$index])) ?
            $this->getColumnNames()[$index] :
            null;
    }
}
