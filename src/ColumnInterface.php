<?php

namespace WebIt4Me\Reader;

interface ColumnInterface {

    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getValue();
}
