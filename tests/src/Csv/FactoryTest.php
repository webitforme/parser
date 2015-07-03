<?php

namespace WebIt4MeTest\Reader\Csv;

use WebIt4Me\Reader\Csv\Factory;
use WebIt4Me\Reader\Csv\Parser;
use WebIt4Me\Reader\Csv\Row;

class FactoryTest extends \PHPUnit_Framework_TestCase
{
    public function test_open()
    {
        $mockCsvFile = __DIR__ . '/../../mockCsvFiles/FL_insurance_sample_short.csv';
        $parser = Factory::open($mockCsvFile);

        $parser->getRow(1)->getColumnAt(1)->setValue('FM');
        /** @var Row $row */
        foreach ($parser as $row) {
            echo $row->toString() . PHP_EOL;
        }

        $this->assertInstanceOf(Parser::class, $parser);
    }

    public function test_save()
    {
        $mockCsvFile = __DIR__ . '/../../mockCsvFiles/FL_insurance_sample_short.csv';
        $newCsvFile = __DIR__ . '/../../mockCsvFiles/FL_insurance_sample_copy.csv';
        $parser = Factory::open($mockCsvFile);

        $counter = 1;

        /** @var Row $row */
        foreach ($parser as $row) {
            $row->getColumnAt(0)->setValue($counter++);
        }

        Factory::save($parser, $newCsvFile);
    }
}
