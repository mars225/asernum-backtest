<?php

namespace Tests\Feature;

use App\Models\Room;
use App\Models\Hotel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class RoomControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $hotel;
    protected $admin;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Créer un hôtel pour les tests
        $this->hotel = Hotel::factory()->create();
        // Créer un utilisateur admin pour les tests de mise à jour
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    /** @test */
    public function it_can_list_rooms_for_a_hotel()
    {
        Sanctum::actingAs($this->admin);

        Room::factory()->count(5)->create(['hotel_id' => $this->hotel->id]);
        //Room::factory()->count(3)->create(); // Autres hôtels


        $response = $this->getJson("/api/hotels/{$this->hotel->id}/rooms");


        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => [
                             'id',
                             'hotel_id',
                             'room_label',
                             'number',
                             'type',
                             'price_per_night',
                             'occupants',
                             'available'
                         ]
                     ],
                     'current_page',
                     'per_page',
                     'total'
                 ]);

        $this->assertEquals(5, $response->json('total'));
    }

    /** @test */
    public function it_can_filter_rooms_by_type()
    {
        Sanctum::actingAs($this->admin);

        Room::factory()->create([
            'hotel_id' => $this->hotel->id,
            'type' => 'single'
        ]);
        Room::factory()->create([
            'hotel_id' => $this->hotel->id,
            'type' => 'double'
        ]);
        Room::factory()->create([
            'hotel_id' => $this->hotel->id,
            'type' => 'suite'
        ]);


        $response = $this->getJson("/api/hotels/{$this->hotel->id}/rooms?type=single");


        $response->assertStatus(200);
        $this->assertEquals(1, $response->json('total'));
        $this->assertEquals('single', $response->json('data.0.type'));
    }

    /** @test */
    public function it_can_customize_pagination_per_page()
    {
        Sanctum::actingAs($this->admin);

        Room::factory()->count(25)->create(['hotel_id' => $this->hotel->id]);


        $response = $this->getJson("/api/hotels/{$this->hotel->id}/rooms?per_page=10");


        $response->assertStatus(200);
        $this->assertEquals(10, $response->json('per_page'));
        $this->assertEquals(25, $response->json('total'));
        $this->assertCount(10, $response->json('data'));
    }

    /** @test */
    public function it_can_create_a_new_room()
    {
        Sanctum::actingAs($this->admin);

        $roomData = [
            'room_label' => 'Chambre Deluxe',
            'number' => '101',
            'type' => 'double',
            'price_per_night' => 150.00,
            'occupants' => 2,
            'available' => true
        ];


        $response = $this->postJson("/api/admin/hotels/{$this->hotel->id}/rooms", $roomData);


        $response->assertStatus(201)
                 ->assertJson([
                     'message' => 'Chambre créée',
                     'data' => [
                         'hotel_id' => $this->hotel->id,
                         'room_label' => 'Chambre Deluxe',
                         'number' => '101',
                         'type' => 'double',
                         'price_per_night' => 150.00,
                         'occupants' => 2,
                         'available' => true
                     ]
                 ]);

        $this->assertDatabaseHas('rooms', [
            'hotel_id' => $this->hotel->id,
            'room_label' => 'Chambre Deluxe',
            'number' => '101'
        ]);
    }

    /** @test */
    public function it_validates_required_fields_when_creating_room()
    {
        Sanctum::actingAs($this->admin);

        $response = $this->postJson("/api/admin/hotels/{$this->hotel->id}/rooms", []);


        $response->assertStatus(422)
                 ->assertJsonValidationErrors([
                     'room_label',
                     'number',
                     'type',
                     'price_per_night',
                     'occupants',
                     'available'
                 ]);
    }

    /** @test */
    public function it_validates_room_type_when_creating()
    {
        Sanctum::actingAs($this->admin);

        $roomData = [
            'room_label' => 'Test Room',
            'number' => '101',
            'type' => 'invalid_type',
            'price_per_night' => 100,
            'occupants' => 2,
            'available' => true
        ];


        $response = $this->postJson("/api/admin/hotels/{$this->hotel->id}/rooms", $roomData);


        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['type']);
    }


    /** @test */
    public function it_allows_same_room_number_for_different_hotels()
    {
        Sanctum::actingAs($this->admin);

        $otherHotel = Hotel::factory()->create();
        Room::factory()->create([
            'hotel_id' => $otherHotel->id,
            'number' => '101'
        ]);

        $roomData = [
            'room_label' => 'Test Room',
            'number' => '101', // Même numéro mais hôtel différent
            'type' => 'single',
            'price_per_night' => 100,
            'occupants' => 1,
            'available' => true
        ];


        $response = $this->postJson("/api/admin/hotels/{$this->hotel->id}/rooms", $roomData);


        $response->assertStatus(201);
    }

    /** @test */
    public function it_can_show_a_specific_room()
    {
        Sanctum::actingAs($this->admin);

        $room = Room::factory()->create(['hotel_id' => $this->hotel->id]);


        $response = $this->getJson("/api/rooms/{$room->id}");


        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Succès',
                     'data' => [
                         'id' => $room->id,
                         'hotel_id' => $room->hotel_id,
                         'room_label' => $room->room_label,
                         'number' => $room->number
                     ]
                 ]);
    }

    /** @test */
    public function it_returns_404_when_room_not_found()
    {
        Sanctum::actingAs($this->admin);

        $response = $this->getJson('/api/rooms/999999');


        $response->assertStatus(404)
                 ->assertJson([
                     'message' => 'Chambre non trouvée'
                 ]);
    }

    /** @test */
    public function it_can_update_a_room_when_authenticated_as_admin()
    {
        Sanctum::actingAs($this->admin);

        $room = Room::factory()->create(['hotel_id' => $this->hotel->id]);
        $updateData = [
            'room_label' => 'Chambre Mise à Jour',
            'number' => 'Updated101',
            'type' => 'suite',
            'price_per_night' => 200.00,
            'occupants' => 4
        ];


        $response = $this->putJson("/api/admin/rooms/{$room->id}", $updateData);


        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Chambre mise à jour',
                     'data' => [
                         'id' => $room->id,
                         'room_label' => 'Chambre Mise à Jour',
                         'number' => 'Updated101',
                         'type' => 'suite',
                         'price_per_night' => 200.00,
                         'occupants' => 4
                     ]
                 ]);

        $this->assertDatabaseHas('rooms', [
            'id' => $room->id,
            'room_label' => 'Chambre Mise à Jour',
            'number' => 'Updated101'
        ]);
    }

    /** @test */
    public function it_prevents_update_when_not_authenticated_as_admin()
    {

        $regularUser = User::factory()->create(['role' => 'customer']);
        Sanctum::actingAs($regularUser);
        $room = Room::factory()->create(['hotel_id' => $this->hotel->id]);
        $updateData = [
            'room_label' => 'Chambre Mise à Jour',
            'number' => 'Updated101',
            'type' => 'suite',
            'price_per_night' => 200.00,
            'occupants' => 4
        ];


        $response = $this->putJson("/api/admin/rooms/{$room->id}", $updateData);


        $response->assertStatus(403);
    }


    /** @test */
    public function it_returns_404_when_updating_non_existent_room()
    {
        Sanctum::actingAs($this->admin);

        $updateData = [
            'room_label' => 'Chambre Mise à Jour',
            'number' => 'Updated101',
            'type' => 'suite',
            'price_per_night' => 200.00,
            'occupants' => 4
        ];


        $response = $this->putJson('/api/admin/rooms/999999', $updateData);


        $response->assertStatus(404)
                 ->assertJson([
                     'message' => 'Chambre non trouvée'
                 ]);
    }

    /** @test */
    public function it_validates_room_number_uniqueness_when_updating_but_allows_same_room()
    {
        Sanctum::actingAs($this->admin);

        $room1 = Room::factory()->create(['hotel_id' => $this->hotel->id, 'number' => '101']);
        $room2 = Room::factory()->create(['hotel_id' => $this->hotel->id, 'number' => '102']);

        // Essayer de mettre à jour room2 avec le numéro de room1
        $updateData = [
            'room_label' => 'Chambre Mise à Jour',
            'number' => '101', // Numéro déjà utilisé par room1
            'type' => 'suite',
            'price_per_night' => 200.00,
            'occupants' => 4
        ];


        $response = $this->putJson("/api/admin/rooms/{$room2->id}", $updateData);


        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['number']);

        // Mais on peut mettre à jour avec le même numéro
        $updateData['number'] = '102';
        $response = $this->putJson("/api/admin/rooms/{$room2->id}", $updateData);

        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_delete_a_room()
    {
        Sanctum::actingAs($this->admin);

        $room = Room::factory()->create(['hotel_id' => $this->hotel->id]);


        $response = $this->deleteJson("/api/admin/rooms/{$room->id}");


        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Chambre supprimée'
                 ]);

        $this->assertDatabaseMissing('rooms', [
            'id' => $room->id
        ]);
    }

    /** @test */
    public function it_returns_404_when_deleting_non_existent_room()
    {
        Sanctum::actingAs($this->admin);
        $response = $this->deleteJson('/api/admin/rooms/999999');


        $response->assertStatus(404)
                 ->assertJson([
                     'message' => 'Chambre non trouvée'
                 ]);
    }

    /** @test */
    public function it_validates_price_per_night_minimum_value()
    {
        Sanctum::actingAs($this->admin);

        $roomData = [
            'room_label' => 'Test Room',
            'number' => '101',
            'type' => 'single',
            'price_per_night' => -10, // Prix négatif
            'occupants' => 1,
            'available' => true
        ];


        $response = $this->postJson("/api/admin/hotels/{$this->hotel->id}/rooms", $roomData);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['price_per_night']);
    }

    /** @test */
    public function it_validates_occupants_minimum_value()
    {
        Sanctum::actingAs($this->admin);

        $roomData = [
            'room_label' => 'Test Room',
            'number' => '101',
            'type' => 'single',
            'price_per_night' => 100,
            'occupants' => 0, // Pas d'occupants
            'available' => true
        ];


        $response = $this->postJson("/api/admin/hotels/{$this->hotel->id}/rooms", $roomData);


        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['occupants']);
    }

    /** @test */
    public function it_validates_hotel_exists_when_creating_room()
    {
        Sanctum::actingAs($this->admin);

        $roomData = [
            'room_label' => 'Test Room',
            'number' => '101',
            'type' => 'single',
            'price_per_night' => 100,
            'occupants' => 1,
            'available' => true
        ];

        $response = $this->postJson('/api/admin/hotels/999999/rooms', $roomData);


        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['hotel_id']);
    }

    /** @test */
    public function it_validates_available_field_as_boolean()
    {
        Sanctum::actingAs($this->admin);
        $roomData = [
            'room_label' => 'Test Room',
            'number' => '101',
            'type' => 'single',
            'price_per_night' => 100,
            'occupants' => 1,
            'available' => 'not_boolean'
        ];


        $response = $this->postJson("/api/admin/hotels/{$this->hotel->id}/rooms", $roomData);


        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['available']);
    }

    /** @test */
    public function it_returns_rooms_ordered_by_number()
    {
        Sanctum::actingAs($this->admin);

        Room::factory()->create(['hotel_id' => $this->hotel->id, 'number' => '103']);
        Room::factory()->create(['hotel_id' => $this->hotel->id, 'number' => '101']);
        Room::factory()->create(['hotel_id' => $this->hotel->id, 'number' => '102']);


        $response = $this->getJson("/api/hotels/{$this->hotel->id}/rooms");


        $response->assertStatus(200);
        $data = $response->json('data');

        $this->assertEquals('101', $data[0]['number']);
        $this->assertEquals('102', $data[1]['number']);
        $this->assertEquals('103', $data[2]['number']);
    }
}
