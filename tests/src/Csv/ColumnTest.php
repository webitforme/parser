<?php

namespace WebIt4MeTest\Reader\Csv;

use WebIt4Me\Reader\Csv\Column;

class ColumnTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dpColumns
     */
    public function test_init($data)
    {

        $column = new Column(
            $data['index'],
            $data['value'],
            $data['name']
        );

        $this->assertEquals($data['index'], $column->getIndex());
        $this->assertEquals($data['value'], $column->getValue());
        $this->assertEquals($data['expectedName'], $column->getName());
    }

    public function test_intValidationException()
    {
        $this->setExpectedException(\Exception::class, '$index must be integer.');
        new Column('bad argument', 'some value');
    }

    public function dpColumns()
    {
        return [
            [
                [
                    'index' => 0,
                    'value' => 'Column one, value',
                    'name' => null,
                    'expectedName' => 'Column 1',
                ],
                [
                    'index' => 1,
                    'value' => 'Second Columns value',
                    'name' => 'Second',
                    'expectedName' => 'Second',
                ],
                [
                    'index' => 2,
                    'value' => '& the last 1',
                    'name' => null,
                    'expectedName' => 'Column 3',
                ]
            ]
        ];
    }

}
