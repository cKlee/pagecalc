<?php
/*
* (c) Carsten Klee <mailme.klee@yahoo.de>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace CK;

/**
 * A class to calculate page numbers and cursors of paged results
 * 
 * @author Carsten Klee
 */
class PageCalc {

    /** @var int Number of items on one page */
    protected $limit;

    /** @var int Number of the first item on the current page*/
    protected $cursor;

    /** @var int Number of the current page*/
    protected $page;

    /**
     * Construct and set defaults
     * 
     * @param int $limit  Number of items on one page
     * 
     * @throws \UnexpectedValueException if limit is not higher than zero
     */
    public function __construct($limit) {
        if (0 < (int) $limit) {
            $this->limit = (int) $limit;
        } else {
            throw new \UnexpectedValueException('Method setLimit only accepts integer values higher than zero for parameter two. Input was: '.$limit);
        }

        $this->cursor = 1;
        $this->page = 1;
    }


    /**
     * Set current cursor and update current page
     * 
     * @param int $cursor number of the first item
     * @param int $limit number of items on one page is optional
     * 
     * @throws \UnexpectedValueException if value of parameter cursor makes pagination invalid
     */
    public function moveCursor($cursor, $limit = false) {
        $this->limit = ($limit) ? (int) $limit : $this->limit;

        if (1 > (int) $cursor) {
            $cursor = 1;
        }

        // check for invalidity
       if(1 < $this->limit) {
            if(((int) $cursor % $this->limit) != 1) {
                throw new \UnexpectedValueException('Invalid cursor position for limit ' .$limit. '. Input was: '.$cursor);
            }
        }

        $this->cursor = (int) $cursor;

        // update current page
        if(1 == $this->cursor) {
            $this->page = 1;
        } else {
            $modPage = (($this->cursor % $this->limit) > 0) ? 1 : 0;
            $this->page = floor($this->cursor / $this->limit) + $modPage;
        }
    }

    /**
     * Set current page and update current cursor
     * 
     * @param int $page current page number
     * @param int $limit number of items on one page is optional
     */
    public function gotoPage($page, $limit = false) {
        $this->limit = ($limit) ? (int) $limit : $this->limit;

        if (1 > (int) $page) {
           $page = 1;
        }
        
        $this->page = (int) $page;

        // update cursor
        if(1 == $this->page) {
            $this->cursor = 1;
        } else {
            $this->cursor = ($this->page - 1) * $this->limit + 1;
        }
    }

    /**
     * Get number of current cursor
     * 
     * @return int number of current cursor
     */
    public function getCursor() {
        return $this->cursor;
    }

    /**
     * Get number of next cursor
     * 
     * @param int $total Total number of items
     * 
     * @return int|bool number of next cursor or false if not applicable
     */
    public function getNextCursor($total) {
        $next = $this->cursor + $this->limit;
        return ($next > (int) $total) ? false : $this->cursor + $this->limit;
    }

    /**
     * Get number of previous cursor
     * 
     * @return int|bool number of previous cursor or false if not applicable
     */
    public function getPreviousCursor() {
        if($this->cursor > $this->limit) {
            return $this->cursor - $this->limit;
        }
        return false;
    }

    /**
     * Get number of last cursor
     * 
     * @param int $total total number of items
     * 
     * @return int number of last cursor
     */
    public function getLastCursor($total) {
        if(1 == $this->limit) {
            return $total;
        }
        return (floor($total / $this->limit) * $this->limit) + 1;
    }

    /**
     * Get total number of pages
     * 
     * @param int $total total number of items
     */
    public function getTotalPages($total) {
        return ($this->limit > $total) ? 1 : ceil($total / $this->limit);
    }

    /**
     * Get number of current page
     * 
     * @return int number of current page
     */
    public function getPage() {
        return $this->page;
    }

    /**
     * Get number of last page
     * 
     * @param int $total total number of items
     * 
     * @return int total number of pages
     */
    public function getLastPage($total) {
        return $this->getTotalPages($total);
    }

    /**
     * Get number of next page
     * 
     * @param int $total total number of items
     * 
     * @return int|bool number of next page or false if no applicable
     */
    public function getNextPage($total) {
        return ($this->page < $this->getTotalPages($total)) ? $this->page + 1 : false;
    }

    /**
     * Get the previous page
     * 
     * @return int|bool number of previous page or false if no applicable
     */
    public function getPreviousPage() {
        return (1 < $this->page) ? $this->page - 1 : false;
    }

}
