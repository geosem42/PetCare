<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Client;

class AppointmentService
{
    public function storeAppointment($data)
    {
        return Appointment::create($data);
    }

    public function fetchAllAppointments()
    {
        return Appointment::with('client')->get();
    }

    public function fetchAppointmentById($id)
    {
        return Appointment::with('client')->find($id);
    }

    public function updateAppointment($id, $data)
    {
        $appointment = $this->fetchAppointmentById($id);
        $appointment->update($data);

        return $appointment;
    }

    public function deleteAppointment($id)
    {
        $appointment = $this->fetchAppointmentById($id);
        $appointment->delete();

        return $appointment;
    }

    public function fetchAllClients($limit = 10)
    {
        return Client::orderBy('id')->limit($limit)->get();
    }

    public function searchClients($query, $limit = 10)
    {
        return Client::where('name', 'like', '%' . $query . '%')->limit($limit)->get();
    }
}