<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Hotel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

class HotelControllerTest extends TestCase
{
    use RefreshDatabase;
    protected $admin;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Créer un utilisateur authentifié pour les tests qui en ont besoin
        $this->user = User::factory()->create(['role' => 'customer']);
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    /** @test */
    public function can_list_hotels()
    {

        Sanctum::actingAs($this->user);


        Hotel::factory()->create([
            'label' => 'Hotel Paris',
            'city' => 'Paris',
            'code' => 'HP001'
        ]);
        Hotel::factory()->create([
            'label' => 'Hotel Lyon',
            'city' => 'Lyon',
            'code' => 'HL001'
        ]);


        $response = $this->getJson('/api/hotels');

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'label',
                        'code',
                        'city'
                    ]
                ]
            ])
            ->assertJsonCount(2, 'data');
    }

    /** @test */
    public function can_filter_hotels_by_label()
    {

        Sanctum::actingAs($this->user);

        Hotel::factory()->create(['label' => 'Grand Hotel Paris']);
        Hotel::factory()->create(['label' => 'Hotel de Lyon']);
        Hotel::factory()->create(['label' => 'Petit Hotel Marseille']);

        $response = $this->getJson('/api/hotels?label=Paris');


        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.label', 'Grand Hotel Paris');
    }

    /** @test */
    public function can_filter_hotels_by_city()
    {

        Sanctum::actingAs($this->user);

        Hotel::factory()->create(['city' => 'Paris']);
        Hotel::factory()->create(['city' => 'Lyon']);
        Hotel::factory()->create(['city' => 'Paris']);

        $response = $this->getJson('/api/hotels?city=Paris');


        $response->assertOk()
            ->assertJsonCount(2, 'data');
    }

    /** @test */
    public function can_create_hotel()
    {

        Sanctum::actingAs($this->admin);

        $hotelData = [
            'label' => 'Nouveau Hotel',
            'code' => 'NH001',
            'address' => '123 Rue de la Paix',
            'city' => 'Paris',
            'country' => 'France',
            'stars' => 4
        ];


        $response = $this->postJson('/api/admin/hotels', $hotelData);


        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Hôtel créé avec succès.',
                'data' => [
                    'label' => 'Nouveau Hotel',
                    'code' => 'NH001',
                    'city' => 'Paris',
                    'stars' => 4
                ]
            ]);

        // Vérifier en base de données
        $this->assertDatabaseHas('hotels', [
            'label' => 'Nouveau Hotel',
            'code' => 'NH001',
            'city' => 'Paris'
        ]);
    }

    /** @test */
    public function cannot_create_hotel_with_duplicate_code()
    {
        // Arrange
        Sanctum::actingAs($this->admin);
        Hotel::factory()->create(['code' => 'DUPLICATE001']);

        $hotelData = [
            'label' => 'Hotel Test',
            'code' => 'DUPLICATE001', // Code déjà existant
            'city' => 'Paris'
        ];


        $response = $this->postJson('/api/admin/hotels', $hotelData);


        $response->assertStatus(422)
            ->assertJsonValidationErrors(['code'])
            ->assertJsonPath('errors.code.0', 'Ce code est déjà utilisé par un autre hôtel.');
    }

    /** @test */
    public function cannot_create_hotel_without_required_fields()
    {
        // Arrange
        Sanctum::actingAs($this->admin);


        $response = $this->postJson('/api/admin/hotels', []);


        $response->assertStatus(422)
            ->assertJsonValidationErrors(['label', 'code']);
    }

    /** @test */
    public function cannot_create_hotel_with_invalid_stars()
    {
        // Arrange
        Sanctum::actingAs($this->admin);

        $hotelData = [
            'label' => 'Hotel Test',
            'code' => 'HT001',
            'stars' => 6 // Invalide (doit être entre 1 et 5)
        ];


        $response = $this->postJson('/api/admin/hotels', $hotelData);


        $response->assertStatus(422)
            ->assertJsonValidationErrors(['stars']);
    }

    /** @test */
    public function can_show_hotel()
    {
        // Arrange - Authentifier l'utilisateur
        Sanctum::actingAs($this->user);

        $hotel = Hotel::factory()->create([
            'label' => 'Hotel Test Show',
            'code' => 'HTS001',
            'city' => 'Paris'
        ]);


        $response = $this->getJson("/api/hotels/{$hotel->id}");


        $response->assertOk()
            ->assertJson([
                'message' => 'Succès',
                'data' => [
                    'id' => $hotel->id,
                    'label' => 'Hotel Test Show',
                    'code' => 'HTS001',
                    'city' => 'Paris'
                ]
            ]);
    }

    /** @test */
    public function cannot_show_nonexistent_hotel()
    {
        // Arrange - Authentifier l'utilisateur
        Sanctum::actingAs($this->user);

        $response = $this->getJson('/api/hotels/999999');

        $response->assertNotFound()
            ->assertJson([
                'message' => 'Hôtel non trouvé'
            ]);
    }

    /** @test */
    public function can_update_hotel()
    {
        // Arrange
        Sanctum::actingAs($this->admin);
        $hotel = Hotel::factory()->create([
            'label' => 'Hotel Original',
            'code' => 'HO001',
            'city' => 'Lyon'
        ]);

        $updateData = [
            'label' => 'Hotel Modifié',
            'code' => 'HO001', // Même code OK pour le même hôtel
            'city' => 'Paris',
            'stars' => 5
        ];


        $response = $this->putJson("/api/admin/hotels/{$hotel->id}", $updateData);


        $response->assertOk()
            ->assertJson([
                'message' => 'Hôtel mis à jour avec succès.',
                'data' => [
                    'id' => $hotel->id,
                    'label' => 'Hotel Modifié',
                    'city' => 'Paris',
                    'stars' => 5
                ]
            ]);

        // Vérifier en base de données
        $this->assertDatabaseHas('hotels', [
            'id' => $hotel->id,
            'label' => 'Hotel Modifié',
            'city' => 'Paris'
        ]);
    }

    /** @test */
    public function cannot_update_hotel_with_duplicate_code()
    {
        // Arrange
        Sanctum::actingAs($this->admin);
        $hotel1 = Hotel::factory()->create(['code' => 'CODE001']);
        $hotel2 = Hotel::factory()->create(['code' => 'CODE002']);

        $updateData = [
            'label' => 'Hotel Test',
            'code' => 'CODE001' // Code déjà utilisé par hotel1
        ];


        $response = $this->putJson("/api/admin/hotels/{$hotel2->id}", $updateData);


        $response->assertStatus(422)
            ->assertJsonValidationErrors(['code']);
    }

    /** @test */
    public function cannot_update_nonexistent_hotel()
    {
        // Arrange
        Sanctum::actingAs($this->admin);

        $updateData = [
            'label' => 'Hotel Test',
            'code' => 'HT001'
        ];


        $response = $this->putJson('/api/admin/hotels/999999', $updateData);


        $response->assertNotFound()
            ->assertJson([
                'message' => 'Hôtel non trouvé'
            ]);
    }

    /** @test */
    public function can_delete_hotel()
    {
        // Arrange
        Sanctum::actingAs($this->admin);
        $hotel = Hotel::factory()->create();


        $response = $this->deleteJson("/api/admin/hotels/{$hotel->id}");


        $response->assertOk()
            ->assertJson([
                'message' => 'Hôtel supprimé avec succès.'
            ]);

        // Vérifier que l'hôtel n'existe plus en base
        $this->assertDatabaseMissing('hotels', [
            'id' => $hotel->id
        ]);
    }

    /** @test */
    public function cannot_delete_nonexistent_hotel()
    {
        // Arrange
        Sanctum::actingAs($this->admin);


        $response = $this->deleteJson('/api/admin/hotels/999999');


        $response->assertNotFound()
            ->assertJson([
                'message' => 'Hôtel non trouvé'
            ]);
    }

    /** @test */
    public function hotels_are_ordered_by_label()
    {
        // Arrange - Authentifier l'utilisateur
        Sanctum::actingAs($this->admin);

        Hotel::factory()->create(['label' => 'Zebra Hotel']);
        Hotel::factory()->create(['label' => 'Alpha Hotel']);
        Hotel::factory()->create(['label' => 'Beta Hotel']);


        $response = $this->getJson('/api/hotels');


        $response->assertOk();

        $hotels = $response->json('data');
        $this->assertEquals('Alpha Hotel', $hotels[0]['label']);
        $this->assertEquals('Beta Hotel', $hotels[1]['label']);
        $this->assertEquals('Zebra Hotel', $hotels[2]['label']);
    }

    /** @test */
    public function can_limit_hotels_per_page()
    {
        // Arrange
        Sanctum::actingAs($this->user);
        Hotel::factory()->count(20)->create();

        $response5 = $this->getJson('/api/hotels?per_page=5');
        $response10 = $this->getJson('/api/hotels?per_page=10');


        $response5->assertOk()->assertJsonCount(5, 'data');
        $response10->assertOk()->assertJsonCount(10, 'data');
    }
}
