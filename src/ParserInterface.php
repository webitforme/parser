<?php

namespace WebIt4Me\Parser;

/**
 * Interface ParserInterface
 *
 * @author Ali Bahman <abn@webit4.me>
 */
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
     * @param int $index
     * @return RowInterface
     */
    public function getRow($index);

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
