<?php

namespace WebIt4Me\Parser\Csv;

use WebIt4Me\Parser\FactoryInterface;
use WebIt4Me\Parser\ParserInterface;

/**
 * Class Factory
 *
 * @author Ali Bahman <abn@webit4.me>
 */
class Factory implements FactoryInterface
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
     * @param Parser $parser
     * @param string $filePath
     * @return boolean
     */
    public static function save(ParserInterface $parser, $filePath)
    {
        $writer = new CsvFileHandler($filePath, "w");

        $writer->writeLine($parser->getColumnNames());

        foreach ($parser->getRows() as $row) {
            $writer->writeRow($row);
        }

        return true;
    }


}