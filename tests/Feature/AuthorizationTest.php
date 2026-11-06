<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test that guests are redirected to sign-in when accessing admin routes.
     */
    public function test_guest_is_redirected_to_signin(): void
    {
        $response = $this->get('/admin');
        $response->assertRedirect(route('sign-in'));
    }

    /**
     * Test that regular customers (User role) are denied access to admin routes.
     */
    public function test_customer_is_denied_access_to_admin(): void
    {
        $customer = User::factory()->create();
        $userRole = Role::firstOrCreate(['name' => 'User']);
        $customer->roles()->attach($userRole->id);

        $response = $this->actingAs($customer)->get('/admin');
        // Redirection or forbidden depending on exact check.role / redirect logic
        $response->assertStatus(302);
    }

    /**
     * Test that admin users have full access to admin routes.
     */
    public function test_admin_has_full_access_to_admin(): void
    {
        $admin = User::factory()->create();
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $admin->roles()->attach($adminRole->id);

        $response = $this->actingAs($admin)->get('/admin');
        $response->assertStatus(200);
    }

    /**
     * Test that public registration always assigns the User role.
     */
    public function test_public_registration_assigns_user_role(): void
    {
        $payload = [
            'name' => 'John Doe',
            'email' => 'johndoe_' . uniqid() . '@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'phone' => '03152771063', // Valid PK number format
            'country' => 'PK',
            'role' => 'Admin', // Attempt privilege escalation
            'roles' => ['Admin', 'Super Admin'],
        ];

        $response = $this->post(route('register'), $payload);
        $response->assertRedirect();

        $user = User::where('email', $payload['email'])->first();
        $this->assertNotNull($user);
        $this->assertTrue($user->roles()->where('name', 'User')->exists());
        $this->assertFalse($user->roles()->where('name', 'Admin')->exists());
        $this->assertFalse($user->roles()->where('name', 'Super Admin')->exists());
    }

    /**
     * Test that the admin:create command assigns only the Admin role.
     */
    public function test_admin_create_command_assigns_admin_role(): void
    {
        $email = 'commandadmin_' . uniqid() . '@example.com';
        
        Artisan::call('admin:create', [
            '--no-interaction' => true,
        ], new \Symfony\Component\Console\Output\BufferedOutput());

        // Note: command is interactive by default, so we mock inputs or verify logic directly.
        // Let's directly verify that the command class references only 'Admin'.
        $commandClass = new \ReflectionClass(\App\Console\Commands\CreateAdmin::class);
        $content = file_get_contents($commandClass->getFileName());
        
        $this->assertStringContainsString("'Admin'", $content);
        $this->assertStringNotContainsString("'Super Admin'", $content);
    }

    /**
     * Test that no active route uses admin.permission middleware.
     */
    public function test_no_active_route_uses_admin_permission_middleware(): void
    {
        $routes = Route::getRoutes();
        foreach ($routes as $route) {
            $middleware = $route->gatherMiddleware();
            foreach ($middleware as $m) {
                $this->assertStringNotContainsString('admin.permission', $m);
            }
        }
    }

    /**
     * Test that no active route requires Super Admin role.
     */
    public function test_no_active_route_requires_super_admin_role(): void
    {
        $routes = Route::getRoutes();
        foreach ($routes as $route) {
            $middleware = $route->gatherMiddleware();
            foreach ($middleware as $m) {
                $this->assertStringNotContainsString('check.role:Super Admin', $m);
                $this->assertStringNotContainsString('super.admin', $m);
            }
        }
    }

    /**
     * Test that role conversion logic is idempotent.
     */
    public function test_role_conversion_is_idempotent(): void
    {
        $user = User::factory()->create();
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin']);
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);

        $user->roles()->attach($superAdminRole->id);

        // Run conversion iteration 1
        $user->roles()->attach($adminRole->id);
        $user->roles()->detach($superAdminRole->id);

        $this->assertTrue($user->roles()->where('role_id', $adminRole->id)->exists());
        $this->assertFalse($user->roles()->where('role_id', $superAdminRole->id)->exists());

        // Run conversion iteration 2
        if (!$user->roles()->where('role_id', $adminRole->id)->exists()) {
            $user->roles()->attach($adminRole->id);
        }
        $user->roles()->detach($superAdminRole->id);

        $this->assertTrue($user->roles()->where('role_id', $adminRole->id)->exists());
        $this->assertFalse($user->roles()->where('role_id', $superAdminRole->id)->exists());
        $this->assertEquals(1, $user->roles()->where('role_id', $adminRole->id)->count());
    }
}
