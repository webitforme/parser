<?php

namespace WebIt4Me\Reader;

interface ReaderInterface
{
    /**
     * @param string $filePath
     * @return boolean
     */
    public function openFile($filePath);

    /**
     * @return ColumnInterface[]
     */
    public function getColumnNames();

    /**
     * @return RowInterface
     */
    public function readRow();

    /**
     * @param int $rowNumber
     * @return RowInterface
     */
    public function readRowAt($rowNumber);

    /**
     * @param string $keyword
     * @return RowInterface
     */
    public function findRow($keyword);

    /**
     * @param string $keyword
     * @return RowInterface[]
     */
    public function findAllRow($keyword);

    /**
     * @param int $keyword
     * @return RowInterface[]
     */
    public function readAll();

    public function closeFile();
}
