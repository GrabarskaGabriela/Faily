<?php

namespace App\Services\Interfaces;


use Illuminate\Http\Request;

interface ReportServiceInterface extends ServiceInterface
{
    public function reportUser(Request $request, $userId, $reporterId);
    public function approveReport($id);
    public function rejectReport($id);
    public function countPendingReports();
}
