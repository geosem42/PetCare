<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\User;

class AppointmentService
{
    public function createAppointment($data)
    {
        return Appointment::create($data);
    }

    public function getAllAppointments()
    {
        return Appointment::with('user')->get();
    }

    public function getAppointmentById($id)
    {
        return Appointment::find($id);
    }

    public function updateAppointment($id, $data)
    {
        $appointment = $this->getAppointmentById($id);
        $appointment->update($data);

        return $appointment;
    }

    public function deleteAppointment($id)
    {
        $appointment = $this->getAppointmentById($id);
        $appointment->delete();

        return $appointment;
    }

    public function getAllUsers($limit = 10)
    {
        return User::orderBy('id')->limit($limit)->get();
    }

    public function searchUsers($query, $limit = 10)
    {
        return User::where('name', 'like', '%' . $query . '%')->limit($limit)->get();
    }
}