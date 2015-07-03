<?php

namespace WebIt4Me\Reader;

/**
 * Interface ColumnInterface
 *
 * @author Ali Bahman <abn@webit4.me>
 */
interface ColumnInterface
{
    /**
     * @return int
     */
    public function getIndex();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getValue();

    /**
     * @param string $value
     */
    public function setValue($value);
}
