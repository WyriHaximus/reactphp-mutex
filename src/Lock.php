<?php

declare(strict_types=1);

namespace WyriHaximus\React\Mutex;

final class Lock implements LockInterface
{
    private string $key;

    private string $rng;

    public function __construct(string $key, string $rng)
    {
        $this->key = $key;
        $this->rng = $rng;
    }

    public function key(): string
    {
        return $this->key;
    }

    public function rng(): string
    {
        return $this->rng;
    }
}
