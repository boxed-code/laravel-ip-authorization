<?php

namespace Tests\Integration;

class EnforcingMiddlewareTestCase extends TestCase
{
    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testDefaultConfigurationDenied()
    {
        $response = $this->actingAs($this->testUser)->get('/');

        $response->assertForbidden();
    }

    public function testWhitelistAuthorized()
    {
        config()->set('ip_auth.addresses.whitelist', ['127.0.0.1']);

        $response = $this->actingAs($this->testUser)->get('/');

        $response->assertOk();
    }

    public function testBlacklistOverridesWhitelist()
    {
        config()->set('ip_auth.addresses.whitelist', ['127.0.0.1']);
        config()->set('ip_auth.addresses.blacklist', ['127.0.0.1']);

        $response = $this->actingAs($this->testUser)->get('/');

        $response->assertForbidden();
    }

    public function testDefaultActionAllow()
    {
        config()->set('ip_auth.default_action', 'allow');

        $response = $this->actingAs($this->testUser)->get('/');

        $response->assertOk();
    }
}
