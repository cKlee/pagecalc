<?php
/*
* (c) Carsten Klee <mailme.klee@yahoo.de>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace CK\Tests;

use CK\PageCalc;
use PHPUnit\Framework\TestCase;

class PageCalcTest  extends TestCase {

    /**
     * @covers CK\PageCalc::__construct
     */
    public function testDefaults() {
        $page = new PageCalc(1);
        $this->assertEquals(1, $page->getCursor());
        $this->assertEquals(1, $page->getPage());
        $this->assertFalse($page->getPreviousCursor());
        $this->assertFalse($page->getPreviousPage());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testConstructError() {
        $page = new PageCalc(0);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testMoveErrorInvalid() {
        $page = new PageCalc(3);
        $page->moveCursor(3);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testMoveErrorValue() {
        $page = new PageCalc(3);
        $page->moveCursor(0);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testGotoErrorValue() {
        $page = new PageCalc(3);
        $page->gotoPage(0);
    }

    public function validStatesProvider() {
        return $states = [
            [['l' => 1, 't' => 1, 'cc' => 1, 'nc' => false, 'pc' => false, 'lc' => 1, 'cp' => 1, 'np' => false, 'pp' => false, 'lp' => 1, 'tp' => 1]],
            [['l' => 1, 't' => 2, 'cc' => 1, 'nc' => 2, 'pc' => false, 'lc' => 2, 'cp' => 1, 'np' => 2, 'pp' => false, 'lp' => 2, 'tp' => 2]],
            [['l' => 2, 't' => 1, 'cc' => 1, 'nc' => false, 'pc' => false, 'lc' => 1, 'cp' => 1, 'np' => false, 'pp' => false, 'lp' => 1, 'tp' => 1]],
            [['l' => 3, 't' => 10, 'cc' => 4, 'nc' => 7, 'pc' => 1, 'lc' => 10, 'cp' => 2, 'np' => 3, 'pp' => 1, 'lp' => 4, 'tp' => 4]],
            [['l' =>3 , 't' => 47, 'cc' => 10, 'nc' => 13, 'pc' => 7, 'lc' => 46, 'cp' => 4, 'np' => 5, 'pp' => 3, 'lp' => 16, 'tp' => 16]]
        ];
    }

    /**
     * @dataProvider validStatesProvider
     *
     */
    public function testValidStates($state) {
        $page = new PageCalc(3);
        $page->moveCursor($state['cc'], $state['l']);
        $this->assertEquals($state['cc'], $page->getCursor($state['cc']));
        $this->assertEquals($state['nc'], $page->getNextCursor($state['t']));
        $this->assertEquals($state['pc'], $page->getPreviousCursor());
        $this->assertEquals($state['lc'], $page->getLastCursor($state['t']));
        $this->assertEquals($state['cp'], $page->getPage());
        $this->assertEquals($state['np'], $page->getNextPage($state['t']));
        $this->assertEquals($state['pp'], $page->getPreviousPage());
        $this->assertEquals($state['lp'], $page->getLastPage($state['t']));
        $this->assertEquals($state['tp'], $page->getTotalPages($state['t']));

        $page->gotoPage($state['cp'], $state['l']);
        $this->assertEquals($state['cc'], $page->getCursor($state['cc']));
        $this->assertEquals($state['nc'], $page->getNextCursor($state['t']));
        $this->assertEquals($state['pc'], $page->getPreviousCursor());
        $this->assertEquals($state['lc'], $page->getLastCursor($state['t']));
        $this->assertEquals($state['cp'], $page->getPage());
        $this->assertEquals($state['np'], $page->getNextPage($state['t']));
        $this->assertEquals($state['pp'], $page->getPreviousPage());
        $this->assertEquals($state['lp'], $page->getLastPage($state['t']));
        $this->assertEquals($state['tp'], $page->getTotalPages($state['t']));
    }

}
