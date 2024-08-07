<?php

declare(strict_types=1);

namespace Testcontainers\Container;

use Testcontainers\Wait\WaitForExec;

class MariaDBContainer extends Container
{
    private function __construct(string $version, string $mysqlRootPassword)
    {
        parent::__construct('mariadb:' . $version);
        $this->withEnvironment('MARIADB_ROOT_PASSWORD', $mysqlRootPassword);

        $binary = 'mysqladmin';

        if ($version === 'latest' || version_compare($version, '11.0.0', '>')) {
            $binary = 'mariadb-admin';
        }

        $this->withWait(new WaitForExec([$binary, 'ping', '-h', '127.0.0.1']));
    }

    public static function make(string $version = 'latest', string $mysqlRootPassword = 'root'): self
    {
        return new self($version, $mysqlRootPassword);
    }

    public function withMariaDBUser(string $username, string $password): self
    {
        $this->withEnvironment('MARIADB_USER', $username);
        $this->withEnvironment('MARIADB_PASSWORD', $password);

        return $this;
    }

    public function withMariaDBDatabase(string $database): self
    {
        $this->withEnvironment('MARIADB_DATABASE', $database);

        return $this;
    }
}
