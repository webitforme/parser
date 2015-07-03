<?php

namespace WebIt4Me\Reader;

interface ParserInterface extends \Iterator
{
    /**
     * @param FileHandlerInterface $fileHandler
     */
    public function __construct(FileHandlerInterface $fileHandler);

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
