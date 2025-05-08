<?php

namespace App\Http\Controllers;

use App\Services\Interfaces\ReportServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    protected $reportService;

    public function __construct(ReportServiceInterface $reportService)
    {
        $this->middleware('auth');
        $this->reportService = $reportService;
    }

    public function reportUser(Request $request, $id)
    {
        $validated = $request->validate([
            'reason' => 'required|string|min:10|max:500',
        ]);

        try {
            $this->reportService->reportUser($request, $id, Auth::id());
            return back()->with('success', 'Użytkownik został zgłoszony. Dziękujemy za pomoc w utrzymaniu jakości naszej społeczności.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
