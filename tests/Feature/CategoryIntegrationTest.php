<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use App\Models\Category;
use App\Models\Course;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CategoryIntegrationTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Ensure Admin and User roles exist
        Role::firstOrCreate(['name' => 'Admin']);
        Role::firstOrCreate(['name' => 'User']);
    }

    /**
     * Test admin can create a category.
     */
    public function test_admin_can_create_category(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin_test_' . uniqid() . '@gmail.com',
            'password' => bcrypt('Password123!'),
        ]);
        $admin->roles()->attach(Role::where('name', 'Admin')->first()->id);

        $name = 'New Test Category ' . uniqid();
        $response = $this->actingAs($admin)->post(route('admin.categories.store'), [
            'name' => $name,
        ]);

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', [
            'name' => $name,
            'is_storefront_category' => true,
        ]);
    }

    /**
     * Test guest cannot create categories.
     */
    public function test_guest_cannot_create_categories(): void
    {
        $response = $this->post(route('admin.categories.store'), [
            'name' => 'Guest Category',
        ]);

        $response->assertRedirect(route('sign-in'));
    }

    /**
     * Test customer (User role) cannot create categories.
     */
    public function test_customer_cannot_create_categories(): void
    {
        $customer = User::create([
            'name' => 'Customer User',
            'email' => 'customer_test_' . uniqid() . '@gmail.com',
            'password' => bcrypt('Password123!'),
        ]);
        $customer->roles()->attach(Role::where('name', 'User')->first()->id);

        $response = $this->actingAs($customer)->post(route('admin.categories.store'), [
            'name' => 'Customer Category',
        ]);

        $response->assertStatus(302); // Redirected back/unauthorized
    }

    /**
     * Test unique slug generation and duplicate slug handling.
     */
    public function test_unique_slug_generation_and_duplicate_slug_handling(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin_test_' . uniqid() . '@gmail.com',
            'password' => bcrypt('Password123!'),
        ]);
        $admin->roles()->attach(Role::where('name', 'Admin')->first()->id);

        // First creation
        $this->actingAs($admin)->post(route('admin.categories.store'), [
            'name' => 'Toys Games',
        ]);
        $this->assertDatabaseHas('categories', ['slug' => 'toys-games']);

        // Second creation with name that maps to same slug
        $this->actingAs($admin)->post(route('admin.categories.store'), [
            'name' => 'Toys & Games',
        ]);
        $this->assertDatabaseHas('categories', ['slug' => 'toys-games-1']);
    }

    /**
     * Test category deletion blocked when any record is assigned.
     */
    public function test_category_deletion_blocked_when_record_assigned(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin_test_' . uniqid() . '@gmail.com',
            'password' => bcrypt('Password123!'),
        ]);
        $admin->roles()->attach(Role::where('name', 'Admin')->first()->id);

        $category = Category::create([
            'name' => 'Deletable Category',
            'slug' => 'deletable-category',
            'is_storefront_category' => true,
        ]);

        // Assign a product
        Course::create([
            'name' => 'Test Product',
            'slug' => 'test-product',
            'description' => 'Test Description',
            'category_id' => $category->id,
            'is_storefront_visible' => true,
            'product_kind' => 'retail',
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.categories.destroy', $category->id));
        $response->assertRedirect(route('admin.categories.index'));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }

    /**
     * Test empty category page returns 200 and dynamic display.
     */
    public function test_empty_category_page_returns_200(): void
    {
        $category = Category::create([
            'name' => 'Empty Storefront Cat',
            'slug' => 'empty-storefront-cat',
            'is_storefront_category' => true,
        ]);

        // Access via storefront shop filter
        $response = $this->get(route('polani.collection') . '?category=' . $category->slug);
        $response->assertStatus(200);
        $response->assertSee('No products available in this category yet');
    }

    /**
     * Test product counts exclude hidden/course products.
     */
    public function test_product_counts_exclude_hidden_and_course_products(): void
    {
        $category = Category::create([
            'name' => 'Count Test Cat',
            'slug' => 'count-test-cat',
            'is_storefront_category' => true,
        ]);

        // 1. Retail storefront visible product (should count)
        Course::create([
            'name' => 'Retail Visible Product',
            'slug' => 'retail-visible',
            'category_id' => $category->id,
            'is_storefront_visible' => true,
            'product_kind' => 'retail',
        ]);

        // 2. Hidden retail product (should not count)
        Course::create([
            'name' => 'Retail Hidden Product',
            'slug' => 'retail-hidden',
            'category_id' => $category->id,
            'is_storefront_visible' => false,
            'product_kind' => 'retail',
        ]);

        // 3. Course product (should not count)
        Course::create([
            'name' => 'Course Product',
            'slug' => 'course-prod',
            'category_id' => $category->id,
            'is_storefront_visible' => true,
            'product_kind' => 'course',
        ]);

        // Access shop page and check counts
        $response = $this->get(route('polani.collection'));
        $response->assertStatus(200);
        
        // Count in filter should be 1
        $response->assertSee('(1)');
        $response->assertDontSee('(3)');
    }
}
