<?php

namespace WebIt4Me\Reader\Csv\Factory;

use WebIt4Me\Reader\Csv\Parser;

class Factory
{
    /** @var string */
    private static $csvFilePath;

    /** @var Parser */
    private static $parser;

    public static function open($csvFilePath)
    {
        self::$csvFilePath = $csvFilePath;

        self::$parser = new Parser(
            new CsvFileHandler(slef::$csvFilePath, "r")
        );

        return self::$parser;
    }

    public static function save($csvFilePath)
    {
        $writer = new CsvFileHandler($csvFilePath, "w");

        $writer->writeLine(self::$parser->getColumnNames());

        foreach (self::$parser->getRows() as $row) {
            $writer->writeRow($row);
        }
    }
}