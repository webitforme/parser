<?php

namespace WebIt4Me\Reader\Csv;

use WebIt4Me\Reader\IterableTrait;
use WebIt4Me\Reader\LoaderInterface;
use WebIt4Me\Reader\RowInterface;

/**
 * Class Loader
 * @package WebIt4Me\Reader\Csv
 *
 * @property-read Row[] $iterable handles in IterableTrait
 * @see IterableTrait
 */
class Loader implements LoaderInterface
{
    use IterableTrait;

    const DELIMITER = ',';

    const ERR_MSG_FAILED_TO_OPEN_FILE = 'Failed to open "%s" to read';
    const ERR_MSG_ROW_BAD_OFFSET = 'There is no index %d since the are only %d rows in the CSV file';

    private $handler;

    /** @var string */
    private $filePath;

    /** @var  array */
    private $columnNames;

    /**
     * @param string $filePath
     */
    public function __construct($filePath)
    {
        ini_set('auto_detect_line_endings', true);

        $this->filePath = $filePath;

        $this->openFile();

        $this->setColumnNames();

    }

    /**
     * @return string[]|null
     */
    public function getColumnNames()
    {
        return $this->columnNames;
    }

    /**
     * @return RowInterface
     */
    public function readRow()
    {
        $nextLine = $this->readLine();

        if (false === $nextLine) {
            return false;
        }

        $row = new Row(
            count($this->iterable),
            $nextLine,
            $this->getColumnNames()
        );

        $this->iterable[] = $row;

        return $row;
    }

    /**
     * @param int $rowIndex
     * @return RowInterface
     */
    public function readRowAt($rowIndex)
    {
        if (isset($this->iterable[$rowIndex])) {
            return $this->iterable[$rowIndex];
        }

        $counter = count($this->iterable);
        while (($row = $this->readRow()) !== false) {
            if ($rowIndex === $counter) {
                return $row;
            }
            $counter++;
        }

        throw new \OutOfRangeException(sprintf(self::ERR_MSG_ROW_BAD_OFFSET, $rowIndex, count($this->iterable)));
    }

    /**
     * @param string $keyword
     * @return RowInterface
     */
    public function findRow($keyword)
    {
        // TODO: Implement findRow() method.
    }

    /**
     * @param string $keyword
     * @return RowInterface[]
     */
    public function findAllRow($keyword)
    {
        $this->loadAllIfNotYet();

        $matchingRows = [];
        foreach ($this->iterable as $row) {
            foreach ($row as $column) {
                if (strpos($column->getValue(), $keyword)) {
                    $matchingRows[$row->getIndex()] = $row;
                }
            }
        }

        return $matchingRows;
    }

    /**
     * @return RowInterface[]
     */
    public function readAll()
    {
        while ($this->readRow() !== false) ;

        return $this->iterable;
    }

    private function loadAllIfNotYet()
    {
        if (count($this->iterable) === 0) {
            $this->readAll();
        }
    }

    /**
     * This method has to be run first think before reading/storing any other line
     */
    private function setColumnNames()
    {
        $this->columnNames = $this->readLine();
    }

    /**
     * Returns an array of all values in the next line or false if its end of the file
     * @return array|false
     */
    private function readLine()
    {
        return ($row = fgetcsv($this->handler, 1000, self::DELIMITER)) !== false ?
            $row :
            false;
    }

    /**
     * @throws \Exception
     */
    private function openFile()
    {
        if (($handle = fopen($this->filePath, "r")) === false) {
            throw new \Exception(sprintf(self::ERR_MSG_FAILED_TO_OPEN_FILE, $this->filePath));
        }

        $this->handler = $handle;
    }

    private function closeFile()
    {
        if (!is_null($this->handler)) {
            fclose($this->handler);
        }
    }


    public function __destruct()
    {
        $this->closeFile();
    }
}
