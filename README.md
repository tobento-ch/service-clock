# Cache Service

Providing [PSR-20](https://www.php-fig.org/psr/psr-20) clocks for PHP applications.

## Table of Contents

- [Getting started](#getting-started)
    - [Requirements](#requirements)
    - [Highlights](#highlights)
- [Documentation](#documentation)
    - [Available Clocks](#available-clocks)
        - [System Clock](#system-clock)
        - [Frozen Clock](#frozen-clock)
- [Credits](#credits)
___

# Getting started

Add the latest version of the clock service project running this command.

```
composer require tobento/service-clock
```

## Requirements

- PHP 8.0 or greater

## Highlights

- Framework-agnostic, will work with any project
- Decoupled design

# Documentation

## Available Clocks

### System Clock

The system clock relies on the current system time. If the ```timezone``` parameter is not defined it uses the timezone from the ```date_default_timezone_get``` method.

```php
use Tobento\Service\Clock\SystemClock;
use Psr\Clock\ClockInterface;

$clock = new SystemClock();

var_dump($clock instanceof ClockInterface);
// bool(true)
```

**With defined timezone parameter**

```php
use Tobento\Service\Clock\SystemClock;
use DateTimeZone;

$clock = new SystemClock(
    timezone: 'UTC'
);

$clock = new SystemClock(
    timezone: new DateTimeZone('Europe/Berlin')
);
```

### Frozen Clock

The frozen clock will always return the same date time, suitable for testing. If the ```now``` parameter is not defined it uses the current time of the [System Clock](#system-clock).

```php
use Tobento\Service\Clock\FrozenClock;
use Psr\Clock\ClockInterface;

$clock = new FrozenClock();

var_dump($clock instanceof ClockInterface);
// bool(true)

// Modify the clock returning a new instance.
// Will accept all formats supported by DateTimeImmutable::modify()
$clockNew = $clock->modify('+30 seconds');

// With a new timezone returning a new instance.
$clockNew = $clock->withTimeZone(timezone: 'UTC');
```

**With defined now parameter**

```php
use Tobento\Service\Clock\FrozenClock;
use Tobento\Service\Clock\SystemClock;
use Psr\Clock\ClockInterface;
use DateTimeImmutable;

$clock = new FrozenClock(
    now: new DateTimeImmutable()
);

// or from another clock:
$clock = new FrozenClock(
    now: new SystemClock()
);
```

# Credits

- [Tobias Strub](https://www.tobento.ch)
- [All Contributors](../../contributors)