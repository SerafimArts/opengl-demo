<?php

declare(strict_types=1);

namespace Bic\Loop\Tests;

use Bic\Loop\Task;

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

        $actual = Task::fiberToCoroutine(new \Fiber(static function () use ($expectedGiven) {
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
        $coroutine = Task::fiberToCoroutine(new \Fiber(static fn(): int => 0xDEAD_BEEF));

        $this->assertFalse($coroutine->valid());
        $this->assertSame(0xDEAD_BEEF, $coroutine->getReturn());
    }

    public function testCoroutineToFiber(): void
    {
        $actual = Task::coroutineToFiber((function () {
            yield 1;
            yield 2;
            yield 3;

            return 0xDEAD_BEEF;
        })());

        $this->assertSame(1, $actual->start());
        $this->assertSame(2, $actual->resume());
        $this->assertSame(3, $actual->resume());

        $actual->resume(); // GOTO return stmt

        $this->assertSame(0xDEAD_BEEF, $actual->getReturn());
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

    public function testGeneratorAwaiting(): void
    {
        $generator = (static function(): \Generator {
            yield 1;
            yield 2;
            yield 3;

            return 0xDEAD_BEEF;
        })();

        $this->assertSame(0xDEAD_BEEF, Task::await($generator));
    }

    public function testSyncFunctionAwaiting(): void
    {
        $function = static function(): int {
            return 0xDEAD_BEEF;
        };

        $this->assertSame(0xDEAD_BEEF, Task::await($function));
    }

    public function testFiberAwaiting(): void
    {
        $fiber = new \Fiber(static function () {
            \Fiber::suspend(1);
            \Fiber::suspend(2);
            \Fiber::suspend(3);

            return 0xDEAD_BEEF;
        });

        $this->assertSame(0xDEAD_BEEF, Task::await($fiber));
    }

    public function testAwaitingInterception(): void
    {
        $task = Task::async(static function () {
            $a = Task::await(static fn() => \Fiber::suspend());
            $b = Task::await(static fn() => \Fiber::suspend());

            return $a + $b;
        });

        $task->resume(0xDEAD_BEEF);
        $task->resume(0xFEED_FACE);

        $this->assertSame(0xDEAD_BEEF + 0xFEED_FACE, $task->getReturn());
    }
}
