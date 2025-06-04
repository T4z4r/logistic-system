<?php

namespace App\Http\Controllers;

use App\Services\TallyIntegrationService;
use Illuminate\Http\Request;

class TallyIntegrationController extends Controller
{
    protected $tallyService;

    public function __construct(TallyIntegrationService $tallyService)
    {
        $this->tallyService = $tallyService;
    }

    public function index()
    {
        $connectionStatus = $this->tallyService->testConnection();
        return view('tally-integration.index', compact('connectionStatus'));
    }

    public function importLedgers(Request $request)
    {
        $result = $this->tallyService->importLedgers(auth()->user()->company_id);
        return redirect()->route('tally.integration')->with($result['success'] ? 'success' : 'error', $result['message']);
    }

    public function exportLedgers(Request $request)
    {
        $result = $this->tallyService->exportLedgers(auth()->user()->company_id);
        return redirect()->route('tally.integration')->with($result['success'] ? 'success' : 'error', $result['message']);
    }

    public function importVouchers(Request $request)
    {
        $result = $this->tallyService->importVouchers(auth()->user()->company_id);
        return redirect()->route('tally.integration')->with($result['success'] ? 'success' : 'error', $result['message']);
    }

    public function exportVouchers(Request $request)
    {
        $result = $this->tallyService->exportVouchers(auth()->user()->company_id);
        return redirect()->route('tally.integration')->with($result['success'] ? 'success' : 'error', $result['message']);
    }

    public function importStockItems(Request $request)
    {
        $result = $this->tallyService->importStockItems(auth()->user()->company_id);
        return redirect()->route('tally.integration')->with($result['success'] ? 'success' : 'error', $result['message']);
    }

    public function exportStockItems(Request $request)
    {
        $result = $this->tallyService->exportStockItems(auth()->user()->company_id);
        return redirect()->route('tally.integration')->with($result['success'] ? 'success' : 'error', $result['message']);
    }
}

