<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Services\AppointmentService;
use App\Http\Requests\Appointment\AppointmentRequest;

class AppointmentController extends Controller
{
    protected $appointmentService;

    public function __construct(AppointmentService $appointmentService)
    {
        $this->appointmentService = $appointmentService;
    }

    public function index()
    {
        return Inertia::render('Appointments/Index');
    }

    public function store(AppointmentRequest $request)
    {
        $validatedData = $request->validated();
        $this->appointmentService->storeAppointment($validatedData);

        return response()->json([
            'message' => 'Event created successfully',
        ], 201);
    }    

    public function fetchAllAppointments() {
        $appointments = $this->appointmentService->fetchAllAppointments();

        return response()->json($appointments, 201);
    }

    public function fetchAllClients()
    {
        $users = $this->appointmentService->fetchAllClients();

        return response()->json($users);
    }

    public function searchClients(Request $request)
    {
        $query = $request->input('query');
        $limit = $request->input('limit', 10);
        $users = $this->appointmentService->searchClients($query, $limit);

        return response()->json($users);
    }

    public function edit($id)
    {
        $appointment = $this->appointmentService->fetchAppointmentById($id);

        return response()->json($appointment, 200);
    }

    public function update(AppointmentRequest $request, $id)
    {
        $validatedData = $request->validated();
        $this->appointmentService->updateAppointment($id, $validatedData);

        return response()->json([
            'message' => 'Appointment updated successfully',
        ], 200);
    }

    public function destroy($id)
    {
        $this->appointmentService->deleteAppointment($id);

        return response()->json([
            'message' => 'Appointment deleted successfully',
        ], 200);
    }
}