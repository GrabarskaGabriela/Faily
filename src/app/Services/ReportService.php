<?php

namespace App\Services;

use App\Repositories\Interfaces\ReportRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\Interfaces\ReportServiceInterface;
use Illuminate\Http\Request;

class ReportService extends BaseService implements ReportServiceInterface
{
    protected $reportRepository;
    protected $userRepository;

    public function __construct(
        ReportRepositoryInterface $reportRepository,
        UserRepositoryInterface $userRepository
    )
    {
        $this->reportRepository = $reportRepository;
        $this->userRepository = $userRepository;
        $this->repository = $reportRepository;
    }

    public function reportUser(Request $request, $userId, $reporterId)
    {
        $user = $this->userRepository->find($userId);

        if (!$user) {
            throw new \Exception('Użytkownik nie istnieje.');
        }

        if ($user->role === 'admin') {
            throw new \Exception('Nie można zgłosić administratora.');
        }

        if ($userId == $reporterId) {
            throw new \Exception('Nie możesz zgłosić samego siebie.');
        }

        $existingReport = $this->reportRepository->findByUserAndStatus($reporterId, $userId, 'pending');

        if ($existingReport) {
            throw new \Exception('Już zgłosiłeś tego użytkownika. Poczekaj na weryfikację zgłoszenia.');
        }

        return $this->reportRepository->create([
            'reporter_id' => $reporterId,
            'reported_user_id' => $userId,
            'reason' => $request->reason,
        ]);
    }

    public function approveReport($id)
    {
        $report = $this->reportRepository->find($id);

        if (!$report) {
            throw new \Exception('Zgłoszenie nie istnieje.');
        }

        $this->reportRepository->updateStatus($id, 'reviewed');

        $user = $this->userRepository->incrementReportCount($report->reported_user_id);

        if ($user->reports_count >= 3 && $user->status !== 'banned' && $user->role !== 'admin') {
            $this->userRepository->updateStatus($user->id, 'banned');
        }

        return $report;
    }

    public function rejectReport($id)
    {
        $report = $this->reportRepository->find($id);

        if (!$report) {
            throw new \Exception('Zgłoszenie nie istnieje.');
        }

        $this->reportRepository->updateStatus($id, 'reviewed');

        return $report;
    }

    public function countPendingReports()
    {
        return $this->reportRepository->countPending();
    }
}
