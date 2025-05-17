<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SettingController;

// For Approvals
Route::any('/erp-settings/approvals', [SettingController::class, 'viewApprovals'])->name('flex.erp.approvals');
Route::any('/erp-settings/save-approvals', [SettingController::class, 'saveApprovals'])->name('erp.saveApprovals');
Route::any('/erp-settings/edit-approval/{id}', [SettingController::class, 'editApproval'])->name('erp.editApproval');
Route::any('/erp-settings/update-approval', [SettingController::class, 'updateApproval'])->name('erp.updateApproval');
Route::any('/erp-settings/delete-approval/{id}', [SettingController::class, 'deleteApproval'])->name('erp.deleteApproval');

// For Approval Levels
Route::any('/erp-settings/approval_levels/{id}', [SettingController::class, 'viewApprovalLevels'])->name('erp.approval-levels');
Route::any('/erp-settings/save-approval-level', [SettingController::class, 'saveApprovalLevel'])->name('erp.saveApprovalLevel');
Route::any('/erp-settings/edit-approval/{id}', [SettingController::class, 'editApproval'])->name('erp.editApproval');
Route::any('/erp-settings/update-approval', [SettingController::class, 'updateApproval'])->name('erp.updateApproval');
Route::any('/erp-delete-approval-level/{id}', [SettingController::class, 'deleteApprovalLevel'])->name('erp.deleteApprovalLevel');
