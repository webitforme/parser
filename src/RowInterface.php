<?php

namespace WebIt4Me\Reader;

interface RowInterface extends \Iterator, \Countable
{
    /**
     * Each row must be aware of its own index in the reader object.
     * This will be used to get specific row
     * @see ReaderInterface::readRowAt()
     * @return int
     */
    public function getIndex();

    /**
     * To return an zero based array to map column index with their name (title).
     * i.e. [0 => 'First Column', 1 => 'And the second']
     * @return null|array
     */
    public function getColumnNames();

    /**
     * @return ColumnInterface[]
     */
    public function getColumns();

    /**
     * @param string $columnName
     * @return ColumnInterface
     */
    public function getColumn($columnName);

    /**
     * @param int $columnIndex
     * @return ColumnsInterface
     */
    public function getColumnAt($columnIndex);
}
