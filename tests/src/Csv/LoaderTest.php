<?php

namespace WebIt4MeTest\Reader\Csv;

use WebIt4Me\Reader\Csv\Loader;
use WebIt4Me\Reader\Csv\Row;

class LoaderTest extends \PHPUnit_Framework_TestCase
{
    /** @var string */
    private $mockCsvFilePath;

    /** @var \SplFileObject */
    private $mockFileObject;

    /** @var Loader */
    private $loader;

    public function setUp()
    {
        $this->mockCsvFilePath = __DIR__ . '/../../mockCsvFiles/FL_insurance_sample_short.csv';

        $this->loader = new Loader($this->mockCsvFilePath); //$this->getMock(Loader::class, [$this->mockCsvFilePath]);
    }

    public function test_getColumnNames()
    {
        $this->assertEquals(
            explode(',', trim(file($this->mockCsvFilePath)[0])),
            $this->loader->getColumnNames()
        );
    }

    public function test_readRowAt()
    {
        $this->assertEquals(
            trim(file($this->mockCsvFilePath)[5]),
            $this->loader->readRowAt(4)->toString()
        );

        // this is to cover the cache mechanism which will use the already loaded row
        // e.g. reading row 1 after already read up to row 5 doesn't need reading line in the file
        $this->assertEquals(
            trim(file($this->mockCsvFilePath)[2]),
            $this->loader->readRowAt(1)->toString()
        );

        $incorrectRowIndex = 125;
        $mockFileAvailableDataRows = 6;

        $this->setExpectedException(
            \OutOfRangeException::class,
            sprintf(Loader::ERR_MSG_ROW_BAD_OFFSET, $incorrectRowIndex, $mockFileAvailableDataRows)
            );

        $this->loader->readRowAt($incorrectRowIndex);
    }

    public function test_search()
    {
        $this->assertEquals(
            trim(file($this->mockCsvFilePath)[4]),
            $this->loader->search('30.063236')[0]->toString()
        );

        $searchResultWithMoreThanSingleRecord = $this->loader->search('-81.707');

        $this->assertContainsOnlyInstancesOf(Row::class, $searchResultWithMoreThanSingleRecord);

        $this->assertEquals(
            trim(file($this->mockCsvFilePath)[2]),
            $searchResultWithMoreThanSingleRecord[0]->toString()
        );

        $this->assertEquals(
            trim(file($this->mockCsvFilePath)[4]),
            $searchResultWithMoreThanSingleRecord[1]->toString()
        );

        $searchResult = $this->loader->search(['policyID' => '19']);
        $a = $searchResult[0]->toArray();
    }

    public function test_readAll()
    {
        $all = $this->loader->readAllRows();

        $this->assertContainsOnlyInstancesOf(Row::class, $all);

        $lineNumber = 1;
        foreach ($all as $row) {
            $this->assertEquals(
                trim(file($this->mockCsvFilePath)[$lineNumber++]),
                $row->toString()
            );
        }
    }
}
