<?php

namespace WebIt4MeTest\Reader\Csv;

use WebIt4Me\Reader\Csv\Loader;

class LoaderTest extends \PHPUnit_Framework_TestCase
{
    private $mockCsvFilePath;

    public function setUp()
    {
        $this->mockCsvFilePath = __DIR__ . '/../../mockCsvFiles/FL_insurance_sample_short.csv';
    }

    public function test_doSomething()
    {
        $loader = new Loader($this->mockCsvFilePath);

//        $line1 = $loader->readRow();
//        $line2 = $loader->readRow();
//        $line3 = $loader->readRow();
//        $line4 = $loader->readRow();
//        $line5 = $loader->readRow();
//        $line6 = $loader->readRow();
//        $line7 = $loader->readRow();

//        $row5 = $loader->readRowAt(8);
//        $b = 1;

//        $a = $loader->readAll();

        foreach ($loader as $key => $row)
        {
            echo $row->getColumn('policyID')->getName() .' : '. $row->getColumn('policyID')->getValue() . PHP_EOL;
        }

        $searchAll = $loader->findAllRow('43');

        $a = 1;
    }
}
