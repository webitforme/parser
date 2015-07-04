<?php

namespace WebIt4MeTest\Reader\Csv;

use WebIt4Me\Parser\Csv\Column;
use WebIt4Me\Parser\Csv\Row;

class RowTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dpRow
     */
    public function test_initRowWithColumnNames($data)
    {
        $row = new Row(
            $data['index'],
            $data['columnValues'],
            $data['columnNames']
        );

        $this->assertEquals(
            $data['index'],
            $row->getIndex()
        );

        $this->assertEquals(
            $data['columnNames'],
            $row->getColumnNames()
        );

        $this->assertContainsOnlyInstancesOf(Column::class, $row->getColumns());

        $counter = 0;
        foreach ($row as $column) {
            $this->assertInstanceOf(Column::class, $column);
            $this->assertEquals($counter, $column->getIndex());
            $this->assertEquals($data['columnValues'][$counter], $column->getValue());
            $this->assertEquals($data['columnNames'][$counter], $column->getName());
            $counter++;
        }

        $counter = 0;
        foreach ($data['columnNames'] as $name) {
            $this->assertInstanceOf(Column::class, $row->getColumn($name));
            $this->assertEquals($name, $row->getColumn($name)->getName());
            $this->assertEquals($data['columnValues'][$counter++], $row->getColumn($name)->getValue());
        }
    }

    /**
     * @dataProvider dpRow
     */
    public function test_initRowWithoutColumnNames($data)
    {
        $row = new Row(
            $data['index'],
            $data['columnValues']
        );

        $this->assertEquals(
            $data['index'],
            $row->getIndex()
        );

        $this->assertNull($row->getColumnNames());

        $this->assertContainsOnlyInstancesOf(Column::class, $row->getColumns());

        $counter = 0;
        foreach ($row as $column) {
            $this->assertInstanceOf(Column::class, $column);
            $this->assertEquals($counter, $column->getIndex());
            $this->assertEquals($data['columnValues'][$counter], $column->getValue());
            $this->assertEquals($data['expectedColumnNamesIfNameIsNotProvided'][$counter], $column->getName());
            $counter++;
        }


        $counter = 0;
        foreach ($data['expectedColumnNamesIfNameIsNotProvided'] as $name) {
            $this->assertInstanceOf(Column::class, $row->getColumn($name));
            $this->assertEquals($name, $row->getColumn($name)->getName());
            $this->assertEquals($data['columnValues'][$counter++], $row->getColumn($name)->getValue());
        }
    }

    public function test_exceptionOnBadColumnName()
    {
        $incorrectName = 'incorrect name';

        $row = new Row(
            1,
            ['a', 'b', 'c']
        );

        $this->setExpectedException(\OutOfRangeException::class, sprintf(Row::ERR_MSG_BAD_NAME, $incorrectName));
        $row->getColumn($incorrectName);
    }

    public function test_getColumnAtAndExceptionOnBadColumnIndex()
    {
        $incorrectIndex = 4;
        $sampleColumns = ['a', 'b', 'c'];

        $row = new Row(
            1,
            $sampleColumns
        );

        $this->assertEquals($sampleColumns[2], $row->getColumnAt(2)->getValue());

        $this->setExpectedException(\OutOfRangeException::class, sprintf(Row::ERR_MSG_BAD_NAME, $incorrectIndex, count($sampleColumns)));
        $row->getColumnAt($incorrectIndex);
    }

    public function test_currentNextAndKeys()
    {
        $sampleColumns = ['a', 'b', 'c'];

        $row = new Row(
            1,
            $sampleColumns
        );

        foreach ($sampleColumns as $key => $value) {
            $this->assertEquals($key, $row->key());
            $this->assertInstanceOf(Column::class, $row->current());
            $this->assertEquals($value, $row->current()->getValue());
            $row->next();
        }

    }

    public function dpRow()
    {
        return [
            [
                [
                    'index' => 0,
                    'columnValues' => [
                        'value for the first column',
                        'value for the second column',
                        'value for the third column',
                        'value for the fourth column',
                        'value for the fifth column',
                    ],
                    'columnNames' => [
                        'Column One',
                        'Column Two',
                        'Column Three',
                        'Column Four',
                        'Column Five',
                    ],
                    'expectedColumnNamesIfNameIsNotProvided' => [
                        'Column 1',
                        'Column 2',
                        'Column 3',
                        'Column 4',
                        'Column 5',
                    ],
                ],
            ]
        ];
    }
}
