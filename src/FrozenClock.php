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

namespace Tobento\Service\Clock;

use Psr\Clock\ClockInterface;
use DateTimeImmutable;
use DateTimeZone;
use Throwable;

/**
 * A clock that always returns the same date time, suitable for testing.
 */
final class FrozenClock implements ClockInterface
{
    /**
     * @var DateTimeImmutable
     */
    protected DateTimeImmutable $now;
    
    /**
     * Create a new Clock.
     *
     * @param null|DateTimeImmutable|ClockInterface $now
     */
    public function __construct(
        null|DateTimeImmutable|ClockInterface $now = null,
    ) {
        if (is_null($now)) {
            $this->now = (new SystemClock())->now();
        } elseif ($now instanceof ClockInterface) {
            $this->now = $now->now();
        } else {
            $this->now = $now;
        }
    }

    /**
     * Returns the current time as a DateTimeImmutable Object
     *
     * @return DateTimeImmutable
     */
    public function now(): DateTimeImmutable
    {
        return $this->now;
    }
    
    /**
     * Returns an instance with the modified clock.
     *
     * @param string $modifier
     * @return static
     */
    public function modify(string $modifier): static
    {
        $modified = $this->now()->modify($modifier);
        
        if ($modified === false) {
            return new static(clone $this->now());
        }
        
        return new static($modified);
    }
    
    /**
     * Returns an instance with specified timezone.
     *
     * @param string|DateTimeZone $timezone The time zone such as 'Europe/Berlin' or DateTimeZone
     * @return static
     */
    public function withTimeZone(string|DateTimeZone $timezone): static
    {
        if (is_string($timezone)) {
            try {
                $timezone = new DateTimeZone($timezone);
            } catch (Throwable $e) {
                $timezone = new DateTimeZone('UTC');
            }
        }
        
        return new static($this->now()->setTimezone($timezone));
    }
}