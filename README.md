[![Build Status](https://travis-ci.org/cKlee/pagecalc.svg?branch=master)](https://travis-ci.org/cKlee/pagecalc)

# PageCalc

It calculates the page number and cursor (first item number on the current page) vice versa.

It also calculates

- next cursor (first item number on the next page if applicable)
- previous cursor (first item number on the previous page if applicable)
- last cursor (first item number on the last page)
- next page number if applicable
- previous page number if applicable
- last page number
- total number of pages

## Installation

Install with composer:

    composer require "ck/pagecalc:@dev"

## Usage example

```php
require '../vendor/autoload.php';

use CK\PageCalc;

$pc = new PageCalc(20) ; // Initiate with a default number of items on one page (limit)

// Someone makes a request on page 3 and a limit of 20 items on the page
$pc->gotoPage($_GET['page'], $_GET['limit']);

// Now do your query using the cursor (offset)
doYourQuery($query = $_GET['q'], $offset = $pc->getCursor(), $limit = $_GET['limit']);

/*
*  With the results you have a total number of results (e.g. $total = 100)
*/

echo 'There are '. $pc->getTotalPages($total) .' pages'; # There are 5 pages

echo 'Page '. $pc->getPage() . ' starts at ' .$pc->getCursor(); # Page 3 starts at 41

// make sure there is a next page
if($pc->getNextPage($total)) {
    echo 'Next page is '. $pc->getNextPage($total) . ' and starts at ' . $pc->getNextCursor($total); # Next page is 4 and starts at 61
}

// make sure there is a previous page
if($pc->getPreviousPage()) {
    echo 'Previous page was '. $pc->getPreviousPage() . ' and started at ' . $pc->getPreviousCursor(); # Previous page was 2 and started at 21
}

// there is always a last page (same as total number of pages)
echo $pc->getLastPage($total) .' is the last page and starts at '. $pc->getLastCursor($total); # 5 is the last page and starts at 81

```

## Methods

### __construct($limit)

Sets the default limit. If limit is lower than one, default limit will be 1.


### moveCursor($cursor, $limit = false)

Moves cursor, calcultes page number and optionally sets a new limit

Since cursor is always the first item number on the current page, cursor must be a multitude of the limit plus 1. If cursor number is not correct method moveCursor will correct this. E.g.:

```php
$pc = new PageCalc(3); // limit 3
$pc->moveCursor(5); // cursor can't be 5
echo $pc->getCursor(); // current cursor is now 4.
//4 is the first item number on page 2, wich contains item number 5
```

### gotoPage($page, $limit = false)

Sets the current page number, calculates cursor number and optionally sets a new limit

### getCursor()

Gets the number of the first item on the current page

### getNextCursor($total)

$total is the total number of items.

Gets the number of the first item on the next page. Returns false if there is no next page.

### getPreviousCursor()

Gets the number of the first item on the previous page. Returns false if there is no previous page.

### getLastCursor($total)

$total is the total number of items.

Gets the number of the first item on the last page.

### getPage()

Gets the number of the current page

### getNextPage($total)

$total is the total number of items.

Gets the number of the next page. Returns false if there is no next page.

### getPreviousPage()

Gets the number of the previous page. Returns false if there is no previous page.

### getLastPage($total)

$total is the total number of items.

Gets the number of last page.

### getTotalPages($total)

$total is the total number of items.

Gets the total number of pages.



