<?php

namespace App\Http\Controllers;

use App\Services\Interfaces\EventAttendeeServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserAttendancesController extends Controller
{
    protected $eventAttendeeService;

    public function __construct(EventAttendeeServiceInterface $eventAttendeeService)
    {
        $this->middleware('auth');
        $this->eventAttendeeService = $eventAttendeeService;
    }

    public function index()
    {
        $userId = Auth::id();
        $attendances = $this->eventAttendeeService->getUserAttendances($userId);

        return view('user.attendances', [
            'attendances' => $attendances
        ]);
    }
}
