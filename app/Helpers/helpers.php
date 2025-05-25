<?php

if (!function_exists('getStatusBadge')) {
    /**
     * Get the status badge HTML based on the status code.
     *
     * @param int $status
     * @return string
     */
    function getStatusBadge($status)
    {
        $badges = [
            0 => '<span class="badge bg-info bg-opacity-10 text-danger mb-0 ms-2 text-capitalize">Draft</span>',
            1 => '<span class="badge bg-info bg-opacity-10 text-warning">Pending</span>',
            2 => '<span class="badge bg-success bg-opacity-10 text-success">Completed</span>',
            3 => '<span class="badge bg-info bg-opacity-10 text-primary">Partial Payment</span>',
            5 => '<span class="badge bg-info bg-opacity-10 text-success">LPO Approved</span>',
            6 => '<span class="badge bg-info bg-opacity-10 text-success">Delivered</span>',
            7 => '<span class="badge bg-info bg-opacity-10 text-primary">Partially Delivered</span>',
            8 => '<span class="badge bg-info bg-opacity-10 text-success">Invoiced</span>',
            9 => '<span class="badge bg-info bg-opacity-10 text-primary">Partially Invoiced</span>',
            10 => '<span class="badge bg-info bg-opacity-10 text-warning">Waiting For Payment Approval</span>',
            11 => '<span class="badge bg-success bg-opacity-10 text-success">Payment Approved</span>',
            'default' => '<span class="badge bg-danger bg-opacity-10 text-white">Cancelled</span>',
        ];

        return $badges[$status] ?? $badges['default'];
    }
}
