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

    public function testGetProfileData() {
        $user = User::getUserProfile(1);
        $this->assertNotNull($user);
        $this->assertNotNull($user['email']);
    }

    public function testUpdateCustomMeta() {
        User::updateMetadata('custom', 1, 'value');
        $meta = User::getMetadata('custom',1, true);
        $this->assertEquals('value', $meta);
    }

    public function testOptionDropDown() {
        $this->assertNotNull(User::getOptionFromConstant('LANGUAGES'));
    }
}
