<?php

declare(strict_types=1);

namespace Bic\Async\Tests;

use Bic\Async\Task;

class TaskTestCase extends TestCase
{
    /**
     * @testdox Checks that the converted Fiber to Coroutine is correctly iterated and returns sent values.
     */
    public function testFiberToCoroutine(): void
    {
        // List of inner iterator values (coroutine response)
        $expectedGiven = $this->randomArrayValues();

        // List of outer iterator values (coroutine sent values)
        $expectedSend = $this->randomArrayValues();

        $actual = Task::toCoroutine(new \Fiber(static function () use ($expectedGiven) {
            $result = [];

            foreach ($expectedGiven as $value) {
                $result[] = \Fiber::suspend($value);
            }

            return $result;
        }));

        $expectedIndex = 0;
        while ($actual->valid()) {
            // Compares that the expected index is the same
            // as the one returned from the coroutine.
            $this->assertSame($expectedIndex++, $actual->key());

            // Compares current element of array element
            // with coroutine step value.
            $this->assertSame($expectedGiven[$actual->key()], $actual->current());

            // Send new value to coroutine from expected array
            $actual->send($expectedSend[$actual->key()]);
        }

        // Compares all sent items to fiber return:
        //  1. Checking that the values were sent correctly.
        //  2. Checking that the values from the coroutine are returned correctly.
        $this->assertSame($expectedSend, $actual->getReturn());
    }

    /**
     * @testdox Checks that the converted Fiber without "suspend" expressions returns empty Coroutine.
     */
    public function testFiberToCoroutineWithoutSteps(): void
    {
        $coroutine = Task::toCoroutine(new \Fiber(static fn(): int => 0xDEAD_BEEF));

        $this->assertFalse($coroutine->valid());
        $this->assertSame(0xDEAD_BEEF, $coroutine->getReturn());
    }

    /**
     * @testdox Checking that an asynchronous function call correctly returns a result.
     */
    public function testAsyncCall(): void
    {
        $async = Task::async($sync = static fn(): int => 0xDEAD_BEEF);

        $this->assertFalse($async->isRunning());
        $this->assertFalse($async->isSuspended());

        $this->assertTrue($async->isStarted());
        $this->assertTrue($async->isTerminated());

        $this->assertSame($sync(), $async->getReturn());
    }

    /**
     * @testdox Checking that the arguments are correctly passed to the asynchronous function call.
     */
    public function testAsyncCallWithArgs(): void
    {
        $sync  = static fn(int $a, int $b): int => 42 + $a - $b;
        $async = Task::async($sync, 0xDEAD_BEEF, 0xFEED_FACE);

        $expected = $sync(0xDEAD_BEEF, 0xFEED_FACE);
        $this->assertSame($expected, $async->getReturn());
    }

    public function testAllTasks(): void
    {
        $a = function () { \Fiber::suspend(1); \Fiber::suspend(3); return 0xDEAD_BEEF; };
        $b = function () { \Fiber::suspend(2); return 0xFEED_FACE; };

        $this->assertSame([0xDEAD_BEEF, 0xFEED_FACE], Task::all($a, $b));
    }

    public function testAllTasksInterception(): void
    {
        $a = function () {
            \Fiber::suspend(10);
            \Fiber::suspend(11);
            \Fiber::suspend(12);

            return 0xDEAD_BEEF;
        };

        $b = function () {
            \Fiber::suspend(20);

            return 0xFEED_FACE;
        };

        $all = new \Fiber(Task::all(...));

        // Execute fiber and suspend with int(10) (firest fiber)
        $this->assertSame(10, $all->start($a, $b));

        // Suspend fiber with int(20) (second fiber)
        $this->assertSame(20, $all->resume());

        // Suspend fiber with int(11) (first fiber)
        $this->assertSame(11, $all->resume());

        // Suspend fiber with int(12) (first fiber)
        $this->assertSame(12, $all->resume());

        // Resume fiber and go to return
        $this->assertSame(null, $all->resume());

        $this->assertSame([0xDEAD_BEEF, 0xFEED_FACE], $all->getReturn());
    }
}
