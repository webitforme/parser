<?php

namespace WebIt4Me\Reader;

interface LoaderInterface extends \Iterator
{
    /**
     * @param string $filePath
     */
    public function __construct($filePath);

    /**
     * @return string[]
     */
    public function getColumnNames();

    /**
     * @param int $rowIndex
     * @return RowInterface
     */
    public function readRowAt($rowIndex);

    /**
     * @param string $keyword
     * @return RowInterface[]
     */
    public function search($keyword);

    /**
     * @param int $keyword
     * @return RowInterface[]
     */
    public function readAllRows();
}
