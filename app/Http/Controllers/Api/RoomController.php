<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\RoomService;
use App\Http\Requests\RoomRequests\RoomRequest;
use App\Http\Requests\RoomRequests\UpdateRoomRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RoomController extends Controller
{
    protected $service;

    public function __construct(RoomService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request, $hotelId)
    {
        $rooms = $this->service->listRooms($hotelId, $request->get('per_page', 15), $request->only('type'));
        return response()->json($rooms);
    }

    public function store(RoomRequest $request, $hotelId)
    {
        $data = $request->validated();
        $data['hotel_id'] = $hotelId;

        $room = $this->service->createRoom($data);
        return response()->json(['message' => 'Chambre créée', 'data' => $room], 201);
    }

    public function show($id)
    {
        try {
            $room = $this->service->getRoom($id);
            return response()->json(['message' => 'Succès', 'data' => $room]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function update(UpdateRoomRequest $request, $id)
    {
        try {
            $room = $this->service->updateRoom($id, $request->validated());
            return response()->json(['message' => 'Chambre mise à jour', 'data' => $room]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function destroy($id)
    {
        try {
            $this->service->deleteRoom($id);
            return response()->json(['message' => 'Chambre supprimée']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }
}
