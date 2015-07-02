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
    public function getRow($rowIndex);

    /**
     * @param string|array $searchParams
     * @return RowInterface[]
     */
    public function search($searchParams);

    /**
     * @param int $keyword
     * @return RowInterface[]
     */
    public function getRows();
}
