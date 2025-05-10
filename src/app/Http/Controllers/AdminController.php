<?php

namespace App\Http\Controllers;

use App\Services\ReportService;
use App\Services\UserService;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    protected $userService;
    protected $reportService;

    public function __construct(UserService $userService, ReportService $reportService)
    {
        $this->middleware('auth');
        $this->middleware('admin');
        $this->userService = $userService;
        $this->reportService = $reportService;
    }

    public function dashboard()
    {
        $stats = [
            'users_count' => $this->userService->all()->count(),
            'banned_users' => $this->userService->countBannedUsers(),
            'pending_reports' => $this->reportService->countPendingReports(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    public function users()
    {
        $users = $this->userService->paginate(10);
        return view('admin.users', compact('users'));
    }

    public function banUser($id)
    {
        try {
            $this->userService->banUser($id);
            return back()->with('success', 'User has been banned.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function unbanUser($id)
    {
        try {
            $this->userService->unbanUser($id);
            return back()->with('success', 'User has been unbanned.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function makeAdmin($id)
    {
        try {
            $this->userService->makeAdmin($id);
            return back()->with('success', 'User has been made admin.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function removeAdmin($id)
    {
        try {
            $this->userService->removeAdmin($id, Auth::id());
            return back()->with('success', 'Admin has been removed.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function reports()
    {
        $reports = $this->reportService->findPending();
        return view('admin.reports', compact('reports'));
    }

    public function approveReport($id)
    {
        try {
            $this->reportService->approveReport($id);
            return back()->with('success', 'Notification approved.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function rejectReport($id)
    {
        try {
            $this->reportService->rejectReport($id);
            return back()->with('success', 'Application rejected.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
