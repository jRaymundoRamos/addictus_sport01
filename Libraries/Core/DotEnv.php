<?php
class DotEnv {
    public function __construct(string $path) {
        if (!file_exists($path)) {
            throw new \InvalidArgumentException("El archivo .env no existe.");
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || strpos($line, '#') === 0 || strpos($line, '=') === false) {
                continue;
            }

            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value, " \t\n\r\0\x0B\"'");

            putenv("$name=$value");
        }
    }
}
