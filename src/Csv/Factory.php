<?php

namespace WebIt4Me\Reader\Csv;

/**
 * Class Factory
 *
 * @author Ali Bahman <abn@webit4.me>
 */
class Factory
{
    /**
     * Open a file to read
     *
     * @param string $csvFilePath
     * @return Parser
     */
    public static function open($csvFilePath)
    {
        $parser = new Parser(
            new CsvFileHandler($csvFilePath, "r")
        );

        return $parser;
    }

    /**
     * Write all the existing rows (comma separated) in the file
     *
     * @param Parser $parser
     * @param $csvFilePath
     */
    public static function save(Parser $parser, $csvFilePath)
    {
        $writer = new CsvFileHandler($csvFilePath, "w");

        $writer->writeLine($parser->getColumnNames());

        foreach ($parser->getRows() as $row) {
            $writer->writeRow($row);
        }
    }
}