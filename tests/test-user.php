<?php
/**
 * Class User
 *
 * @package Wptool
 */

/**
 * User test case.
 */
class UserTest extends WP_UnitTestCase {

	/**
	 * A single example test.
	 */
	public function testConstants() {
        // Replace this with some actual testing code.
        $this->assertIsArray(User::LANGUAGES);
    }

    public function testCountryByIp() {
        $this->assertNotNull(User::currentIpCountry());
    }
}
