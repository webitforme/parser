<?php

namespace WebIt4Me\Reader;

/**
 * Interface RowInterface
 *
 * @author Ali Bahman <abn@webit4.me>
 */
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
     * @param string $name
     * @return ColumnInterface
     */
    public function getColumn($name);

    /**
     * @param int $index
     * @return ColumnInterface
     */
    public function getColumnAt($index);

    /**
     * Returns a key/value set of all existing columns in the row
     * @return array
     */
    public function toArray();

    /**
     * Returns a string of all the column values, comma separated
     * @return string
     */
    public function toString();
}
