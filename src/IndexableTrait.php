<?php

namespace WebIt4Me\Parser;

/**
 * Class IndexableTrait
 *
 * @author Ali Bahman <abn@webit4.me>
 */
trait IndexableTrait
{
    /** @var int */
    private $index;

    /**
     * @param $index
     * @return $this
     * @throw \InvalidArgumentException
     */
    public function setIndex($index)
    {
        if (false === filter_var($index, FILTER_VALIDATE_INT)) {
            throw new \InvalidArgumentException('$index must be integer.');
        }

        $this->index = $index;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getIndex()
    {
        return $this->index;
    }
}
