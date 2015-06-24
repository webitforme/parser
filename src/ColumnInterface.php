<?php

namespace WebIt4Me\Reader;

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
}
