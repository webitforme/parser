<?php

namespace WebIt4Me\Reader;

interface ReaderInterface {

    /**
     * @param string $filePath
     * @return boolean
     */
    public function openFile(string $filePath);

    /**
     * @return ColumnInterface[]
     */
    public function getColumnNames();

    /**
     * @return RowInterface
     */
    public function readNextRow();

    /**
     * @param int $rowNumber
     * @return RowInterface
     */
    public function readRow(int $rowNumber);

    /**
     * @param string $keyword
     * @return RowInterface
     */
    public function findRow(string $keyword);

    /**
     * @param string $keyword
     * @return RowInterface[]
     */
    public function findAllRow(string $keyword);

    /**
     * @param int $keyword
     * @return RowInterface[]
     */
    public function readAll();

    public function closeFile();
}
