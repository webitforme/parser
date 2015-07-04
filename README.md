[![Build Status](https://travis-ci.org/webit4me/parser.svg?branch=develop)](https://travis-ci.org/webit4me/parser)
[![Coverage Status](https://coveralls.io/repos/webit4me/parser/badge.svg)](https://coveralls.io/r/webit4me/parser)

# reader
Simply put, a parser to be capable to digest different type of data file structures, starting with CSV.


## Installation

### Using Composer
```bash
composer require  webit4me/parser dev-master
```

## Usage

```php
$parser = Factory::open('/path/to/the/csvFile.csv');

// to iterate through all rows and change their first column's value incrementally and save.
foreach ($parser as $row) {

    $counter = 1;

    /** @var Row $row */
    foreach ($parser as $row) {
        $row->getColumnAt(0)->setValue($counter++);
    }

    Factory::save($parser, '/path/to/the/same/or/another/csvFile.csv');
}

// to search for a text in all columns and print all matching rows as string

$rows = $parser->search("30");
foreach ($rows as $row) {
    echo $row->toString() . PHP_EOL;
}

// to search words 'Book' and 'Ball' only in the column 'product' and print all matching rows as string

$rows = $parser->search(['product' => ['Book', 'Ball']]);
foreach ($rows as $row) {
    echo $row->toString() . PHP_EOL;
}

// to search words 'Book' in columns 'product' and 'description' and print all matching rows as string

$rows = $parser->search(['product' => 'Book', 'description' => 'Book']);
foreach ($rows as $row) {
    echo $row->toString() . PHP_EOL;
}
```
