<?php

namespace Tests\Unit;

use DateTime;
use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_isTimeOnRange_function()
    {
            $result = isTimeOnRange(
                new DateTime('2025-08-16 00:17:03'),
                new DateTime('2025-08-15 20:00:00'),
                new DateTime('2025-08-16 01:00:00')
            );

            // Assert: Verify the result using PHPUnit assertions
            $this->assertTrue($result); // Example: Asserting the function returns 10 for input 5
    }

    public function test_timeDiference_function()
    {
            $dif = timeDiference(
                new DateTime('2025-08-16 19:10:00'),
                new DateTime('2025-08-16 20:00:00')
            );

            // Assert: Verify the result using PHPUnit assertions
            $this->assertTrue($dif==-50); // Example: Asserting the function returns 10 for input 5
    }
}
