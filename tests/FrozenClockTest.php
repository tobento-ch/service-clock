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
use Tobento\Service\Clock\FrozenClock;
use Tobento\Service\Clock\SystemClock;
use Psr\Clock\ClockInterface;
use DateTimeImmutable;
use DateTimeZone;

/**
 * FrozenClockTest
 */
class FrozenClockTest extends TestCase
{
    public function testConstructMethodWithNull()
    {
        $clock = new FrozenClock();
        $this->assertInstanceof(ClockInterface::class, $clock);
        $this->assertInstanceof(DateTimeImmutable::class, $clock->now());
    }
    
    public function testConstructMethodWithDateTimeImmutable()
    {
        $now = new DateTimeImmutable();
        $clock = new FrozenClock($now);
        $this->assertSame($now, $clock->now());
    }
    
    public function testConstructMethodWithClock()
    {
        $systemClock = new SystemClock();
        $clock = new FrozenClock($systemClock);
        $this->assertInstanceof(DateTimeImmutable::class, $clock->now());
    }
    
    public function testNowMethod()
    {
        $now = new DateTimeImmutable();
        $clock = new FrozenClock($now);
        
        $this->assertSame($now, $clock->now());
        usleep(10);
        $this->assertSame($now, $clock->now());
    }
    
    public function testModifyMethod()
    {
        $clock = new FrozenClock();
        $clockNew = $clock->modify('+30 seconds');
        
        $this->assertNotSame($clock, $clockNew);
        $this->assertGreaterThan($clock->now(), $clockNew->now());
    }
    
    public function testWithTimeZoneMethodWithString()
    {
        $clock = new FrozenClock();
        $clockNew = $clock->withTimeZone(timezone: 'UTC');
        
        $this->assertNotSame($clock, $clockNew);
        $this->assertSame('UTC', $clockNew->now()->getTimezone()->getName());
    }
    
    public function testWithTimeZoneMethodWithInvalidStringUsesDefault()
    {
        $clock = new FrozenClock();
        $clockNew = $clock->withTimeZone(timezone: 'invalid');
        
        $this->assertNotSame($clock, $clockNew);
        $this->assertSame('UTC', $clockNew->now()->getTimezone()->getName());
    }
    
    public function testWithTimeZoneMethodWithDateTimeZone()
    {
        $clock = new FrozenClock();
        $clockNew = $clock->withTimeZone(timezone: new DateTimeZone('Europe/Berlin'));
        
        $this->assertNotSame($clock, $clockNew);
        $this->assertSame('Europe/Berlin', $clockNew->now()->getTimezone()->getName());
    }
}