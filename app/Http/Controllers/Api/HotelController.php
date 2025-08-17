<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\HotelRequest;
use App\Services\HotelService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class HotelController extends Controller
{
    protected $service;

    public function __construct(HotelService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $hotels = $this->service->listHotels(
            $request->get('per_page', 15),
            $request->only(['label', 'city'])
        );

        return response()->json($hotels);
    }

    public function store(HotelRequest $request)
    {
        $hotel = $this->service->createHotel($request->validated());
        Log::channel('hotel')->info('Hôtel créé avec succès. : '. $hotel->label . 'par '. Auth::user()->name);
        return response()->json([
            'message' => 'Hôtel créé avec succès.',
            'data' => $hotel,
        ], 201);
    }

    public function show($id)
    {
        try {
            $hotel = $this->service->getHotel($id);
            return response()->json([
                'message' => 'Succès',
                'data' => $hotel,
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function update(HotelRequest $request, $id)
    {
        try {
            $hotel = $this->service->updateHotel($id, $request->validated());
            return response()->json([
                'message' => 'Hôtel mis à jour avec succès.',
                'data' => $hotel,
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function destroy($id)
    {
        try {
            $this->service->deleteHotel($id);
            Log::channel('hotel')->info('Hôtel supprimé avec succès. ID: '. $id . ' par '. Auth::user()->name);
            return response()->json(['message' => 'Hôtel supprimé avec succès.']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }
}
