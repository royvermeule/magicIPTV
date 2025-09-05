<?php

namespace Src\core;

final class Session
{
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!self::has('language')) {
            self::set('language', 'en');
        }

        self::regenerate();
        self::set('csrf_token', md5(uniqid(
            (string) rand(), true)
        ));
    }

    public static function regenerate(): void
    {
        $time = time();

        /** @var int $lastSessionTime */
        $lastSessionTime = self::get('time') ?? $time;

        if ($time - $lastSessionTime < 30) {
            session_regenerate_id(true);
            self::set('time', $time);
        }
    }

    public static function destroy(): void
    {
        session_destroy();
    }

    /**
     * @param string $key
     * @param scalar|array $value
     * @return void
     */
    public static function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * @param string $key
     * @return scalar|array|null
     */
    public static function get(string $key): mixed
    {
        if (!isset($_SESSION[$key])) {
            return null;
        }

        /** @var scalar|array $value */
        $value = $_SESSION[$key];
        return $value;
    }

    public static function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    public static function unset(string $key): void
    {
        unset($_SESSION[$key]);
    }
}