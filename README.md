[![Build Status](https://travis-ci.org/webit4me/reader.svg?branch=master)](https://travis-ci.org/webit4me/reader)
[![Coverage Status](https://coveralls.io/repos/webit4me/reader/badge.svg)](https://coveralls.io/r/webit4me/reader)

# reader
Simply put, a file reader, to be capable to digest different type of data file structures. starting with csv.


## Installation

### Using Composer
```bash
composer require  webit4me/reader dev-master
```

## Usage

```php
$loader = new Loader($this->mockCsvFilePath);

// to iterate through all rows and print their column name and values
foreach ($loader->readAllRows() as $row) {

    foreach($row as $column) {

        echo $column->getName() . ' : ' . $column->getValue() . PHP_EOL;
    }

    echo PHP_EOL;
}

// to search for a text in all rows and print all matching rows as string

$rows = $loader->search("30");
foreach ($rows as $row) {
    echo $row->toString() . PHP_EOL;
}
```
