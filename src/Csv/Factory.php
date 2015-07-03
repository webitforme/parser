<?php

namespace WebIt4Me\Reader\Csv;

class Factory
{
    public static function open($csvFilePath)
    {
        $parser = new Parser(
            new CsvFileHandler($csvFilePath, "r")
        );

        return $parser;
    }

    public static function save(Parser $parser, $csvFilePath)
    {
        $writer = new CsvFileHandler($csvFilePath, "w");

        $writer->writeLine($parser->getColumnNames());

        foreach ($parser->getRows() as $row) {
            $writer->writeRow($row);
        }
    }
}