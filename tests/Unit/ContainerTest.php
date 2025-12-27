<?php

namespace Tests\Unit;

use App\Models\Container;
use App\Models\ShippingLine;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContainerTest extends TestCase
{
    use RefreshDatabase;

    public function test_container_can_be_created(): void
    {
        $shippingLine = ShippingLine::create([
            'name' => 'Test Shipping Line',
            'code' => 'TSL',
            'email' => 'test@shipping.com',
        ]);

        $container = Container::create([
            'container_number' => 'TEST1234567',
            'type' => '40ft',
            'shipping_line_id' => $shippingLine->id,
            'status' => 'pending',
        ]);

        $this->assertDatabaseHas('containers', [
            'container_number' => 'TEST1234567',
            'type' => '40ft',
        ]);

        $this->assertEquals('pending', $container->status);
    }

    public function test_container_belongs_to_shipping_line(): void
    {
        $shippingLine = ShippingLine::create([
            'name' => 'Test Shipping Line',
            'code' => 'TSL',
            'email' => 'test@shipping.com',
        ]);

        $container = Container::create([
            'container_number' => 'TEST1234567',
            'type' => '40ft',
            'shipping_line_id' => $shippingLine->id,
            'status' => 'pending',
        ]);

        $this->assertInstanceOf(ShippingLine::class, $container->shippingLine);
        $this->assertEquals('Test Shipping Line', $container->shippingLine->name);
    }
}
