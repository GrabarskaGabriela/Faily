<?php

namespace App\Repositories\Interfaces;

interface ReportRepositoryInterface extends RepositoryInterface
{
    public function findPending();
    public function findByUserAndStatus($reporterId, $reportedUserId, $status);
    public function updateStatus($id, $status);
    public function countPending();
}
