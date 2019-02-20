<?php declare(strict_types=1);

namespace WyriHaximus\React;

final class Lock
{
    /** @var string */
    private $key;

    /** @var string */
    private $rng;

    /**
     * @param string $key
     * @param string $rng
     */
    public function __construct(string $key, string $rng)
    {
        $this->key = $key;
        $this->rng = $rng;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @return string
     */
    public function getRng(): string
    {
        return $this->rng;
    }
}
