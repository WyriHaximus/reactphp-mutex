# Mutex locking for ReactPHP projects

![Continuous Integration](https://github.com/WyriHaximus/reactphp-mutex/workflows/Continuous%20Integration/badge.svg)
[![Latest Stable Version](https://poser.pugx.org/WyriHaximus/react-mutex/v/stable.png)](https://packagist.org/packages/WyriHaximus/react-mutex)
[![Total Downloads](https://poser.pugx.org/WyriHaximus/react-mutex/downloads.png)](https://packagist.org/packages/WyriHaximus/react-mutex)
[![Code Coverage](https://scrutinizer-ci.com/g/WyriHaximus/reactphp-mutex/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/WyriHaximus/reactphp-mutex/?branch=master)
[![License](https://poser.pugx.org/WyriHaximus/react-mutex/license.png)](https://packagist.org/packages/WyriHaximus/react-mutex)

# Install

To install via [Composer](http://getcomposer.org/), use the command below, it will automatically detect the latest version and bind it with `^`.

```
composer require wyrihaximus/react-mutex
```

# Usage

```php
use WyriHaximus\React\Mutex\Contracts\LockInterface;use WyriHaximus\React\Mutex\Memory;

$key = 'key'; // Unique key for this operation
$ttl = 0.1; // The time after which the lock expires
$mutex = new Memory();
$mutex->acquire($key, $ttl)->then(function ($lock) use ($mutex) {
    if (!($lock instanceof LockInterface)) {
        // We couldn't acquired the lock on this key
        return;
    }

    // We acquired the lock on this key
    // Do long running non-blocking thing
    $mutex->release($lock);
});
```

# License

The MIT License (MIT)

Copyright (c) 2021 Cees-Jan Kiewiet

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
