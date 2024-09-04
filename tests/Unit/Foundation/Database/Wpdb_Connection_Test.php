<?php

declare(strict_types=1);

namespace Enpii_Base\Tests\Unit\Foundation\Database;

use Enpii_Base\Foundation\Database\Connectors\Connection_Factory;
use Enpii_Base\Foundation\Database\Wpdb_Connection;
use Enpii_Base\Tests\Support\Unit\Libs\Unit_Test_Case;
use Mockery;
use PDO;

class Wpdb_Connection_Test extends Unit_Test_Case {

	protected $wpdb_connection;

	protected function setUp(): void {
		parent::setUp();
		$pdo_mock = $this->getMockBuilder( PDO::class )
		->disableOriginalConstructor()
		->getMock();
		$config  = [
			'wpdb' => 'mock_wpdb_instance',
		];

		$this->wpdb_connection = new Wpdb_Connection( $pdo_mock, 'database', 'prefix', $config );
	}

	protected function tearDown(): void {
		Mockery::close();
		parent::tearDown();
	}

	public function test_constructor() {
		$property_value = $this->get_protected_property_value( $this->wpdb_connection, 'wpdb' );

		// Verify that the $wpdb property is set correctly
		$this->assertEquals( 'mock_wpdb_instance', $property_value );
	}

	public function test_determine_wbdb_bound_string_returns_d_for_integer() {
		$result = $this->invoke_protected_method( $this->wpdb_connection, 'determine_wbdb_bound_string', [ 121212 ] );
		$this->assertSame( '%d', $result );
	}

	public function test_determine_wbdb_bound_string_returns_f_for_float() {
		$result = $this->invoke_protected_method( $this->wpdb_connection, 'determine_wbdb_bound_string', [ 123.45 ] );

		$this->assertSame( '%f', $result );
	}

	public function test_determine_wbdb_bound_string_returns_s_for_string() {
		$result = $this->invoke_protected_method( $this->wpdb_connection, 'determine_wbdb_bound_string', [ 'test string' ] );

		$this->assertSame( '%s', $result );
	}

	public function test_determine_wbdb_bound_string_returns_i_for_other_types() {
		$result = $this->invoke_protected_method( $this->wpdb_connection, 'determine_wbdb_bound_string', [ [ 'array' ] ] );

		$this->assertSame( '%i', $result );
	}
}
