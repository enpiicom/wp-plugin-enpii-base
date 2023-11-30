<?php

declare(strict_types=1);
use WP_Mock\Tools\TestCase;

class EnpiiBaseTest extends TestCase
{
	public function test_enpii_base_get_major_version()
	{

		$version = '1.2.3';

		$result = enpii_base_get_major_version($version);
		$expected = 1;


		$this->assertEquals($expected, $result);

	}

}
