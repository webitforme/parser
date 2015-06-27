<?php

namespace WebIt4Me\Reader;

interface LoaderInterface extends \Iterator
{
    /**
     * @param string $filePath
     * @param boolean $firstLineIsFiledName
     */
    public function __construct($filePath, $firstLineIsFiledName = true);

    /**
     * @return string[]
     */
    public function getColumnNames();

    /**
     * @return RowInterface
     */
    public function readRow();

    /**
     * @param int $rowIndex
     * @return RowInterface
     */
    public function readRowAt($rowIndex);

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
}
