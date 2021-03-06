<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ZendTest\Hydrator\Strategy;

use PHPUnit\Framework\TestCase;
use Zend\Hydrator\Strategy\StrategyChain;
use Zend\Hydrator\Strategy\ClosureStrategy;

/**
 * @covers \Zend\Hydrator\Strategy\StrategyChain
 */
class StrategyChainTest extends TestCase
{
    public function testEmptyStrategyChainReturnsOriginalValue()
    {
        $chain = new StrategyChain([]);
        $this->assertEquals('something', $chain->hydrate('something'));
        $this->assertEquals('something', $chain->extract('something'));
    }

    public function testExtract()
    {
        $chain = new StrategyChain([
            new ClosureStrategy(
                function ($value) {
                    return $value % 12;
                }
            ),
            new ClosureStrategy(
                function ($value) {
                    return $value % 9;
                }
            ),
        ]);
        $this->assertEquals(3, $chain->extract(87));

        $chain = new StrategyChain([
            new ClosureStrategy(
                function ($value) {
                    return $value % 8;
                }
            ),
            new ClosureStrategy(
                function ($value) {
                    return $value % 3;
                }
            ),
        ]);
        $this->assertEquals(1, $chain->extract(20));

        $chain = new StrategyChain([
            new ClosureStrategy(
                function ($value) {
                    return $value % 7;
                }
            ),
            new ClosureStrategy(
                function ($value) {
                    return $value % 6;
                }
            ),
        ]);
        $this->assertEquals(2, $chain->extract(30));
    }

    public function testHydrate()
    {
        $chain = new StrategyChain([
            new ClosureStrategy(
                null,
                function ($value) {
                    return $value % 3;
                }
            ),
            new ClosureStrategy(
                null,
                function ($value) {
                    return $value % 7;
                }
            )
        ]);
        $this->assertEquals(0, $chain->hydrate(87));

        $chain = new StrategyChain([
            new ClosureStrategy(
                null,
                function ($value) {
                    return $value % 8;
                }
            ),
            new ClosureStrategy(
                null,
                function ($value) {
                    return $value % 3;
                }
            ),
        ]);
        $this->assertEquals(2, $chain->hydrate(20));

        $chain = new StrategyChain([
            new ClosureStrategy(
                null,
                function ($value) {
                    return $value % 4;
                }
            ),
            new ClosureStrategy(
                null,
                function ($value) {
                    return $value % 9;
                }
            ),
        ]);
        $this->assertEquals(3, $chain->hydrate(30));
    }
}
