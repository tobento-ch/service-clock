<?php

/**
 * TOBENTO
 *
 * @copyright   Tobias Strub, TOBENTO
 * @license     MIT License, see LICENSE file distributed with this source code.
 * @author      Tobias Strub
 * @link        https://www.tobento.ch
 */

declare(strict_types=1);

namespace Tobento\Service\Clock\Test;

use PHPUnit\Framework\TestCase;
use Tobento\Service\Clock\SystemClock;
use Psr\Clock\ClockInterface;
use DateTimeImmutable;
use DateTimeZone;

/**
 * SystemClockTest
 */
class SystemClockTest extends TestCase
{
    public function testConstructMethodWithNull()
    {
        $tz = date_default_timezone_get();
        $clock = new SystemClock();
        $this->assertInstanceof(ClockInterface::class, $clock);
        $this->assertSame($tz, $clock->now()->getTimezone()->getName());
        
        $tz = date_default_timezone_get();
        $clock = new SystemClock(timezone: null);
        $this->assertSame($tz, $clock->now()->getTimezone()->getName());
    }
    
    public function testConstructMethodWithString()
    {
        $clock = new SystemClock(timezone: 'UTC');
        $this->assertSame('UTC', $clock->now()->getTimezone()->getName());
    }
    
    public function testConstructMethodWithInvalidStringUsesDefault()
    {
        $clock = new SystemClock(timezone: 'invalid');
        $this->assertSame('UTC', $clock->now()->getTimezone()->getName());
    }
    
    public function testConstructMethodWithDateTimeZone()
    {
        $clock = new SystemClock(timezone: new DateTimeZone('Europe/Berlin'));
        $this->assertSame('Europe/Berlin', $clock->now()->getTimezone()->getName());
    }
    
    public function testNowMethod()
    {
        $clock = new SystemClock();
        $before = new DateTimeImmutable();
        usleep(10);
        $now = $clock->now();
        usleep(10);
        $after = new DateTimeImmutable();

        $this->assertGreaterThan($before, $now);
        $this->assertLessThan($after, $now);
    }
}