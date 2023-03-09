<?php

namespace Enpii\WP_Plugin\Enpii_Base\Dependencies\Illuminate\Database;

use Doctrine\DBAL\Driver\PDOPgSql\Driver as DoctrineDriver;
use Enpii\WP_Plugin\Enpii_Base\Dependencies\Illuminate\Database\Query\Grammars\PostgresGrammar as QueryGrammar;
use Enpii\WP_Plugin\Enpii_Base\Dependencies\Illuminate\Database\Query\Processors\PostgresProcessor;
use Enpii\WP_Plugin\Enpii_Base\Dependencies\Illuminate\Database\Schema\Grammars\PostgresGrammar as SchemaGrammar;
use Enpii\WP_Plugin\Enpii_Base\Dependencies\Illuminate\Database\Schema\PostgresBuilder;
use PDO;

class PostgresConnection extends Connection
{
    /**
     * Bind values to their parameters in the given statement.
     *
     * @param  \PDOStatement  $statement
     * @param  array  $bindings
     * @return void
     */
    public function bindValues($statement, $bindings)
    {
        foreach ($bindings as $key => $value) {
            if (is_int($value)) {
                $pdoParam = PDO::PARAM_INT;
            } elseif (is_resource($value)) {
                $pdoParam = PDO::PARAM_LOB;
            } else {
                $pdoParam = PDO::PARAM_STR;
            }

            $statement->bindValue(
                is_string($key) ? $key : $key + 1,
                $value,
                $pdoParam
            );
        }
    }

    /**
     * Get the default query grammar instance.
     *
     * @return \Enpii\WP_Plugin\Enpii_Base\Dependencies\Illuminate\Database\Query\Grammars\PostgresGrammar
     */
    protected function getDefaultQueryGrammar()
    {
        return $this->withTablePrefix(new QueryGrammar);
    }

    /**
     * Get a schema builder instance for the connection.
     *
     * @return \Enpii\WP_Plugin\Enpii_Base\Dependencies\Illuminate\Database\Schema\PostgresBuilder
     */
    public function getSchemaBuilder()
    {
        if (is_null($this->schemaGrammar)) {
            $this->useDefaultSchemaGrammar();
        }

        return new PostgresBuilder($this);
    }

    /**
     * Get the default schema grammar instance.
     *
     * @return \Enpii\WP_Plugin\Enpii_Base\Dependencies\Illuminate\Database\Schema\Grammars\PostgresGrammar
     */
    protected function getDefaultSchemaGrammar()
    {
        return $this->withTablePrefix(new SchemaGrammar);
    }

    /**
     * Get the default post processor instance.
     *
     * @return \Enpii\WP_Plugin\Enpii_Base\Dependencies\Illuminate\Database\Query\Processors\PostgresProcessor
     */
    protected function getDefaultPostProcessor()
    {
        return new PostgresProcessor;
    }

    /**
     * Get the Doctrine DBAL driver.
     *
     * @return \Doctrine\DBAL\Driver\PDOPgSql\Driver
     */
    protected function getDoctrineDriver()
    {
        return new DoctrineDriver;
    }
}
