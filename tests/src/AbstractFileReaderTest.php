<?php

namespace WebIt4MeTest;

use Symfony\Component\Config\Definition\Exception\Exception;
use WebIt4Me\Reader\AbstractFileReader;

class AbstractFileReaderTest extends \PHPUnit_Framework_TestCase
{
    /** @var string */
    private $mockCsvFilePath;

    /** @var AbstractFileReader $fileReader */
    private static $fileReader;

    public function setUp()
    {
        $this->mockCsvFilePath = __DIR__ . '/../mockCsvFiles/FL_insurance_sample_short.csv';

        if (is_null(self::$fileReader)) {
            self::$fileReader = $this->getMockForAbstractClass(
                AbstractFileReader::class,
                [$this->mockCsvFilePath]
            );
        }
    }

    public function test_fileOpenException()
    {
        $badFilePath = 'a/file/that/is/not/exists.now';

        $this->setExpectedException(
            \Exception::class,
            sprintf(AbstractFileReader::ERR_MSG_FAILED_TO_OPEN_FILE, $badFilePath)
        );

        $this->getMockForAbstractClass(
            AbstractFileReader::class,
            [$badFilePath]
        );
    }

    /**
     * @dataProvider dpMockFileLines
     */
    public function test_readLine($line)
    {

        if (is_bool($line)){
            $this->assertFalse($line);
        }else{
            $this->assertStringStartsWith($line, self::$fileReader->readLine());
        }
    }

    public function dpMockFileLines()
    {
        return [


                    ['policyID,statecode,county,eq_site_limit,hu_site_limit,fl_site_limit,fr_site_limit,tiv_2011,tiv_2012,eq_site_deductible,hu_site_deductible,fl_site_deductible,fr_site_deductible,point_latitude,point_longitude,line,construction,point_granularity'],
                    ['119736,FL,CLAY COUNTY,498960,498960,498960,498960,498960,792148.9,0,9979.2,0,0,30.102261,-81.711777,Residential,Masonry,1'],
                    ['448094,FL,CLAY COUNTY,1322376.3,1322376.3,1322376.3,1322376.3,1322376.3,1438163.57,0,0,0,0,30.063936,-81.707664,Residential,Masonry,3'],
                    ['206893,FL,CLAY COUNTY,190724.4,190724.4,190724.4,190724.4,190724.4,192476.78,0,0,0,0,30.089579,-81.700455,Residential,Wood,1'],
                    ['333743,FL,CLAY COUNTY,0,79520.76,0,0,79520.76,86854.48,0,0,0,0,30.063236,-81.707703,Residential,Wood,3'],
                    ['172534,FL,CLAY COUNTY,0,254281.5,0,254281.5,254281.5,246144.49,0,0,0,0,30.060614,-81.702675,Residential,Wood,1'],
                    ['995932,FL,CLAY COUNTY,0,19260000,0,0,19260000,20610000,0,0,0,0,30.102226,-81.713882,Commercial,Reinforced Concrete,1'],
                    [false],


        ];
    }
}