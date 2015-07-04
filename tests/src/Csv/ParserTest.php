<?php

namespace WebIt4MeTest\Reader\Csv;

use WebIt4Me\Parser\Csv\CsvFileHandler;
use WebIt4Me\Parser\Csv\Parser;
use WebIt4Me\Parser\Csv\Row;

class ParserTest extends \PHPUnit_Framework_TestCase
{
    /** @var string */
    private $mockCsvFilePath;

    /** @var \SplFileObject */
    private $mockFileObject;

    /** @var Parser */
    private $parser;

    public function setUp()
    {
        $this->mockCsvFilePath = __DIR__ . '/../../mockCsvFiles/FL_insurance_sample_short.csv';

        $this->parser = new Parser(
            new CsvFileHandler($this->mockCsvFilePath, "r")
        );
    }

    public function test_getColumnNames()
    {
        $this->assertEquals(
            explode(',', trim(file($this->mockCsvFilePath)[0])),
            $this->parser->getColumnNames()
        );
    }

    public function test_readRowAt()
    {
        $this->assertEquals(
            trim(file($this->mockCsvFilePath)[5]),
            $this->parser->getRow(4)->toString()
        );

        // this is to cover the cache mechanism which will use the already loaded row
        // e.g. reading row 1 after already read up to row 5 doesn't need reading line in the file
        $this->assertEquals(
            trim(file($this->mockCsvFilePath)[2]),
            $this->parser->getRow(1)->toString()
        );

        $incorrectRowIndex = 125;
        $mockFileAvailableDataRows = 6;

        $this->setExpectedException(
            \OutOfRangeException::class,
            sprintf(Parser::ERR_MSG_ROW_BAD_OFFSET, $incorrectRowIndex, $mockFileAvailableDataRows)
            );

        $this->parser->getRow($incorrectRowIndex);
    }

    public function test_search()
    {
        $this->assertEquals(
            trim(file($this->mockCsvFilePath)[4]),
            $this->parser->search('30.063236')[0]->toString()
        );

        $searchResultWithMoreThanSingleRecord = $this->parser->search('-81.707');

        $this->assertContainsOnlyInstancesOf(Row::class, $searchResultWithMoreThanSingleRecord);

        $this->assertEquals(
            trim(file($this->mockCsvFilePath)[2]),
            $searchResultWithMoreThanSingleRecord[0]->toString()
        );

        $this->assertEquals(
            trim(file($this->mockCsvFilePath)[4]),
            $searchResultWithMoreThanSingleRecord[1]->toString()
        );

    }

    public function test_searchSpecificColumn()
    {
        $result = $this->parser->search(['policyID' => '19']);

        $this->assertCount(1, $result);

        $this->assertEquals(
            trim(file($this->mockCsvFilePath)[1]),
            $result[0]->toString()
        );

        $result = $this->parser->search(['policyID' => ['19','20']]);

        $this->assertCount(2, $result);

        $this->assertEquals(
            trim(file($this->mockCsvFilePath)[1]),
            $result[0]->toString()
        );

        $this->assertEquals(
            trim(file($this->mockCsvFilePath)[3]),
            $result[1]->toString()
        );
    }

    public function test_searchSpecificColumns()
    {
        $result = $this->parser->search(['policyID' => '119736', 'construction' => 'Concrete' ]);

        $this->assertCount(2, $result);

        $this->assertEquals(
            trim(file($this->mockCsvFilePath)[1]),
            $result[0]->toString()
        );

        $this->assertEquals(
            trim(file($this->mockCsvFilePath)[6]),
            $result[1]->toString()
        );
    }

    public function test_readAll()
    {
        $all = $this->parser->getRows();

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
