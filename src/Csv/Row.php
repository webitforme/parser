<?php

namespace WebIt4Me\Parser\Csv;

use WebIt4Me\Parser\ColumnInterface;
use WebIt4Me\Parser\ColumnsInterface;
use WebIt4Me\Parser\IndexableTrait;
use WebIt4Me\Parser\IterableTrait;
use WebIt4Me\Parser\RowInterface;

/**
 * Class Row
 *
 * @property-read Column[] $iterable handles in IterableTrait
 * @see IterableTrait
 *
 * @author Ali Bahman <abn@webit4.me>
 */
class Row implements RowInterface
{
    use IterableTrait;
    use IndexableTrait;

    const ERR_MSG_BAD_NAME = 'There is no column named %s in the row.';
    const ERR_MSG_BAD_INDEX = '%s is an invalid index, since this row only hold % columns';

    /** @var array */
    private $columnNames;

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
     * To return a zero based array to map column index with their name (title).
     * i.e. [0 => 'First Column', 1 => 'And the second']
     *
     * @return null|array
     */
    public function getColumnNames()
    {
        return $this->columnNames;
    }


    /**
     * Return row's all columns
     *
     * @return ColumnInterface[]
     */
    public function getColumns()
    {
        return $this->iterable;
    }

    /**
     * Return the column with the matching name
     *
     * @param string $name
     * @return ColumnInterface
     * @throw \OutOfRangeException
     */
    public function getColumn($name)
    {
        foreach ($this as $column) {
            if ($column->getName() === $name) {
                return $column;
            }
        }

        throw new \OutOfRangeException (sprintf(self::ERR_MSG_BAD_NAME, $name));
    }

    /**
     * Return a column based on its index
     *
     * @param int $index
     * @return ColumnInterface
     * @throw \OutOfRangeException
     */
    public function getColumnAt($index)
    {
        if (!isset($this->iterable[$index])) {
            throw new \OutOfRangeException (sprintf(self::ERR_MSG_BAD_NAME, $index, $this->count()));
        }

        return $this->iterable[$index];
    }

    /**
     * Return number of row's columns
     *
     * @return int
     */
    public function count()
    {
        return count($this->iterable);
    }

    /**
     * To create Column objects based on the provided array
     *
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

            $this->iterable[$counter] = $column;
            $counter++;
        }

    }

    /**
     * Return column name, only if one has been provided
     *
     * @param int $index
     * @return string|null
     */
    private function getColumnNameForIndex($index)
    {
        return (is_array($this->getColumnNames()) && isset($this->getColumnNames()[$index])) ?
            $this->getColumnNames()[$index] :
            null;
    }

    /**
     * Return a key/value set of all existing columns in the row
     *
     * @return array
     */
    public function toArray()
    {
        $array = [];

        /** @var Column $column */
        foreach ($this->iterable as $column) {
            $array[$column->getName()] = $column->getValue();
        }

        return $array;
    }

    /**
     * Return a string consist of all the column values, comma separated
     *
     * @return string
     */
    public function toString()
    {
        return implode(',', $this->toArray());
    }
}
