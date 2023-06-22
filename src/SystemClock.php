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
 * A clock that relies on the system time.
 */
final class SystemClock implements ClockInterface
{
    /**
     * @var DateTimeZone
     */
    protected DateTimeZone $dateTimeZone;
    
    /**
     * Create a new SystemClock.
     *
     * @param null|string|DateTimeZone $timezone = null,
     */
    public function __construct(
        null|string|DateTimeZone $timezone = null,
    ) {
        $timezone = $timezone ?: date_default_timezone_get();
        
        if (is_string($timezone)) {
            try {
                $this->dateTimeZone = new DateTimeZone($timezone);
            } catch (Throwable $e) {
                $this->dateTimeZone = new DateTimeZone('UTC');
            }
        } else {
            $this->dateTimeZone = $timezone;
        }
    }
    
    /**
     * Returns the current time as a DateTimeImmutable Object
     *
     * @return DateTimeImmutable
     */
    public function now(): DateTimeImmutable
    {
        return new DateTimeImmutable('now', $this->dateTimeZone);
    }
}