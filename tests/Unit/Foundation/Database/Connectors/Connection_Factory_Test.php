<?php

declare(strict_types=1);

namespace Enpii_Base\Tests\Unit\Foundation\Database\Connectors;

use Enpii_Base\Foundation\Database\Connectors\Connection_Factory;
use Enpii_Base\Foundation\Database\Connectors\Wpdb_Connector;
use Enpii_Base\Foundation\Database\Wpdb_Connection;
use Enpii_Base\Tests\Support\Unit\Libs\Unit_Test_Case;
use Illuminate\Database\Connection;
use Illuminate\Database\Connectors\MySqlConnector;
use Illuminate\Database\MySqlConnection;
use InvalidArgumentException;
use Mockery;


class Connection_Factory_Test extends Unit_Test_Case {
	protected $container;
	protected $connection_factory;

	protected function setUp(): void {
		parent::setUp();

		$this->container = new Container_Test_Tmp();
		$this->connection_factory = new Connection_Factory( $this->container );
	}

	protected function tearDown(): void {
		Mockery::close();
		parent::tearDown();
	}

	public function test_create_connector_throws_exception_when_driver_is_empty() {
		$config = [];

		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( 'A driver must be specified.' );

		$this->connection_factory->createConnector( $config );
	}

	public function test_create_connector_with_registered_connector() {
		$config = [
			'driver' => 'mysql',
		];

		// Invoke the createConnector method
		$connector = $this->connection_factory->createConnector( $config );

		// Assert that the result is an instance of MySqlConnector
		$this->assertInstanceOf( MySqlConnector::class, $connector );
	}

	public function test_create_connector_with_wpdb_driver() {
		$config = [
			'driver' => 'wpdb',
		];

		// Invoke the createConnector method
		$connector = $this->connection_factory->createConnector( $config );

		// Assert that the result is an instance of Wpdb_Connector
		$this->assertInstanceOf( Wpdb_Connector::class, $connector );
	}

	/**
	 * @throws \ReflectionException
	 */
	public function test_create_connection_with_resolver() {
		$driver = 'mysql';
		// phpcs:ignore WordPress.DB.RestrictedClasses.mysql__PDO
		$connection = $this->getMockBuilder( \PDO::class )
							->disableOriginalConstructor()
							->getMock();
		$database = 'test';
		$prefix = 'prefix_';
		$config = [];

		// Invoke the createConnection method
		$result = $this->invoke_protected_method( $this->connection_factory, 'createConnection', [ $driver, $connection, $database, $prefix, $config ] );

		// Assert that the result is an instance of Connection
		$this->assertInstanceOf( Connection::class, $result );
	}

	/**
	 * @throws \ReflectionException
	 */
	public function test_create_connection_with_wpdb_driver() {
		// phpcs:ignore WordPress.DB.RestrictedClasses.mysql__PDO
		$connection = $this->getMockBuilder( \PDO::class )
							->disableOriginalConstructor()
							->getMock();
		$driver     = 'wpdb';
		$database   = 'test';
		$prefix     = 'prefix_';
		$config     = [];

		// Mock the $wpdb global variable
		global $wpdb;
		// phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		$wpdb     = new \stdClass();
		$wpdb->db = $connection;

		// Invoke the createConnection method
		$result = $this->invoke_protected_method( $this->connection_factory, 'createConnection', [ $driver, $wpdb->db, $database, $prefix, $config ] );

		// Assert that the result is an instance of Wpdb_Connection
		$this->assertInstanceOf( Wpdb_Connection::class, $result );
	}

	/**
	 * @throws \ReflectionException
	 */
	public function test_create_connection_with_supported_driver() {
		$driver     = 'mysql';
		$connection = 'customConnection';
		$database   = 'customDatabase';
		$prefix     = 'customPrefix';
		$config     = [ 'customConfig' ];

		// Invoke the createConnection method
		$result = $this->invoke_protected_method( $this->connection_factory, 'createConnection', [ $driver, $connection, $database, $prefix, $config ] );

		// Assert that the result is an instance of MySqlConnection
		$this->assertInstanceOf( MySqlConnection::class, $result );
	}
}

namespace Enpii_Base\Tests\Unit\Foundation\Database\Connectors;

use Illuminate\Container\Container as ContainerContainer;

class Container_Test_Tmp extends ContainerContainer {
}
