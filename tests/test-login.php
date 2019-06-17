<?php
/**
 * Class Login
 *
 * @package Wptool
 */

/**
 * Sample test case.
 */
class loginTest extends WP_UnitTestCase {


    public function setUp(): void
    {
        $this->class_instance = new Login;
    }

    public function testRender() {
        $this->assertNotNull($this->class_instance->render_login_form(null));
    }

    

    protected $user;

}
