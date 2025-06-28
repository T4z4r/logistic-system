<?php
namespace App\Services;

use App\Models\Approval;

class ApprovalProcessService
{
    /**
     * Ensure the approval process exists or create it with default settings.
     *
     * @param string $processName
     * @param int $escalation (optional) Default escalation value
     * @param int $escalationTime (optional) Default escalation time value
    * @return Approval
     */
    public function ensureProcessExists(string $processName, int $escalation = 0, int $escalationTime = 1): Approval
    {
        // Try to find the process by name
        $process = Approval::where('process_name', $processName)->first();

        // If not found, create it
        if (!$process) {
            $process = new Approval();
            $process->process_name = $processName;
            $process->escallation = $escalation;
            $process->escallation_time = $escalationTime;
            $process->save();
        }

        return $process;
    }
}
