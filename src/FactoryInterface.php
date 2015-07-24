<?php

namespace WebIt4Me\Parser;

interface FactoryInterface
{

    /**
     * @param string $filePath
     * @return ParserInterface
     */
    public static function open($filePath);

    /**
     * @param ParserInterface $parser
     * @param string $filePath
     * @return boolean
     */
    public static function save(ParserInterface $parser, $filePath);

}