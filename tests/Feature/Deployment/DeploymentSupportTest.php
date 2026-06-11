<?php

declare(strict_types=1);

namespace Tests\Feature\Deployment;

use Tests\TestCase;

class DeploymentSupportTest extends TestCase
{
    public function test_deployment_support_exists_for_all_required_environments(): void
    {
        $this->assertFileExists(base_path('deploy/specs/local-dev/compose.yml'));
        $this->assertFileExists(base_path('deploy/specs/staging/compose.yml'));
        $this->assertFileExists(base_path('deploy/specs/production/compose.yml'));
        $this->assertFileExists(base_path('deploy/scripts/local-dev/up.sh'));
        $this->assertFileExists(base_path('deploy/scripts/staging/up.sh'));
        $this->assertFileExists(base_path('deploy/scripts/production/up.sh'));
    }

    public function test_local_dev_support_includes_a_mongodb_client_path(): void
    {
        $override = file_get_contents(base_path('docker-compose.override.yml'));

        $this->assertNotFalse($override);
        $this->assertStringContainsString('mongo-web-client', $override);
        $this->assertStringContainsString('local-dev', $override);
        $this->assertStringContainsString('8081:8081', $override);
    }
}
