<?php

namespace WebIt4Me\Reader;

interface RowInterface
{
    /**
     * Each row must be aware of its own index in the reader object.
     * This will be used to get specific row
     * @see ReaderInterface::readRowAt()
     * @return int
     */
    public function getIndex();

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
