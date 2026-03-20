<?php

if (!function_exists('loadEnv')) {
    function loadEnv($envFilePath)
    {
        if (!is_readable($envFilePath)) {
            return;
        }

        $lines = file($envFilePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($lines === false) {
            return;
        }

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || strpos($line, '#') === 0) {
                continue;
            }

            $parts = explode('=', $line, 2);
            if (count($parts) !== 2) {
                continue;
            }

            $key = trim($parts[0]);
            $value = trim($parts[1]);
            $value = trim($value, "\"'");

            if ($key === '') {
                continue;
            }

            if (getenv($key) === false) {
                putenv($key . '=' . $value);
            }

            $_ENV[$key] = $value;
            $_SERVER[$key] = $value;
        }
    }
}

if (!function_exists('env')) {
    function env($key, $default = null)
    {
        $value = $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key);
        return ($value === false || $value === null || $value === '') ? $default : $value;
    }
}

if (!function_exists('app_base_path')) {
    function app_base_path()
    {
        $appUrl = (string) env('APP_URL', '/');
        $path = (string) parse_url($appUrl, PHP_URL_PATH);

        if ($path === '') {
            $path = '/';
        }

        $path = '/' . trim($path, '/');
        if ($path === '/') {
            return '/';
        }

        return $path . '/';
    }
}

if (!function_exists('app_url')) {
    function app_url($path = '')
    {
        $basePath = app_base_path();
        $path = ltrim((string) $path, '/');

        if ($basePath === '/') {
            return '/' . $path;
        }

        return $basePath . $path;
    }
}

if (!function_exists('redirect_to')) {
    function redirect_to($path)
    {
        header('Location: ' . app_url($path));
        exit();
    }
}

if (!function_exists('set_flash')) {
    function set_flash($type, $message)
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            return;
        }

        $_SESSION['flash'] = [
            'type' => (string) $type,
            'message' => (string) $message,
        ];
    }
}

if (!function_exists('get_flash')) {
    function get_flash()
    {
        if (session_status() !== PHP_SESSION_ACTIVE || !isset($_SESSION['flash'])) {
            return null;
        }

        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
}

if (!function_exists('csrf_token')) {
    function csrf_token()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            return '';
        }

        if (empty($_SESSION['_csrf_token'])) {
            $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['_csrf_token'];
    }
}

if (!function_exists('verify_csrf')) {
    function verify_csrf($token)
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            return false;
        }

        if (!isset($_SESSION['_csrf_token']) || !is_string($token)) {
            return false;
        }

        return hash_equals($_SESSION['_csrf_token'], $token);
    }
}
