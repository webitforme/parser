<?php

namespace WebIt4Me\Reader;

interface RowInterface {

    /**
     * @return ColumnInterface[]
     */
    public function getColumns();

    /**
     * @param string $columnName
     * @return ColumnInterface
     */
    public function getColumn(string $columnName);

    /**
     * @param int $columnIndex
     * @return ColumnsInterface
     */
    public function getColumnAt(int $columnIndex);
}
