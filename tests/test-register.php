<?php
/**
 * Class Register
 *
 * @package Wptool
 */

/**
 * Sample test case.
 */
class registerTest extends WP_UnitTestCase {

    protected $user;

    public function test_createUser() {
        $this->user = Register::register_user('test@test.com', 'Jose', 'Perez', 'password', 'custom');
        $this->assertIsInt($this->user);

        return "this: " . $this->user;
    }

    public function test_True_crf_user_register() {
        $this->assertTrue(Register::crf_user_register(1));
    }

    public function test_EditCustommeta() {        
        $_POST['custom'] = 'test';
        Register::crf_user_register(1);
        $this->assertTrue('test' == get_user_meta(1, Register::WP_CUSTOM . 'custom', true));
    }
}
