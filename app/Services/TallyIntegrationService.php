<?php

namespace App\Services;

use App\Models\Unit;
use SimpleXMLElement;
use App\Models\Godown;
use App\Models\Ledger;
use App\Models\Voucher;
use App\Models\Currency;
use App\Models\StockItem;
use App\Models\CostCenter;
use App\Models\StockGroup;
use App\Models\VoucherType;
use App\Models\AccountGroup;
use App\Models\VoucherEntry;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class TallyIntegrationService
{
    protected $host;
    protected $port;

    public function __construct()
    {
        $this->host = config('tally.host', 'localhost');
        $this->port = config('tally.port', '9000');
    }

    /**
     * Test connection to Tally server
     * @return bool
     */
    public function testConnection()
    {
        try {
            $response = Http::timeout(5)->post("http://{$this->host}:{$this->port}", [
                'xml' => '<ENVELOPE><HEADER><VERSION>1</VERSION><TALLYREQUEST>Export</TALLYREQUEST><TYPE>Data</TYPE><ID>CompanyList</ID></HEADER><BODY></BODY></ENVELOPE>'
            ]);
            if ($response->successful()) {
                $xml = simplexml_load_string($response->body());
                return $xml !== false && isset($xml->BODY->DATA);
            }
            return false;
        } catch (\Exception $e) {
            Log::error('Tally connection test failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Import ledgers from Tally
     * @param int $companyId
     * @return array
     */
    public function importLedgers($companyId)
    {
        try {
            $xmlRequest = $this->buildLedgerImportXml();
            $response = Http::post("http://{$this->host}:{$this->port}", ['xml' => $xmlRequest]);

            if ($response->successful()) {
                $xmlResponse = simplexml_load_string($response->body());
                $ledgers = $this->parseLedgerResponse($xmlResponse);

                $count = 0;
                foreach ($ledgers as $ledgerData) {
                    $group = AccountGroup::where('name', $ledgerData['parent'])->where('company_id', $companyId)->first();
                    if (!$group) {
                        continue; // Skip if group not found
                    }

                    Ledger::updateOrCreate(
                        ['name' => $ledgerData['name'], 'company_id' => $companyId],
                        [
                            'group_id' => $group->id,
                            'opening_balance' => $ledgerData['opening_balance'],
                            'contact_details' => $ledgerData['contact_details'] ?? null,
                        ]
                    );
                    $count++;
                }

                return [
                    'success' => true,
                    'message' => "Imported $count ledgers successfully",
                    'count' => $count
                ];
            }
            throw new \Exception('Failed to import ledgers: Invalid response');
        } catch (\Exception $e) {
            Log::error('Ledger import failed: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Ledger import failed: ' . $e->getMessage(),
                'count' => 0
            ];
        }
    }

    /**
     * Export ledgers to Tally
     * @param int $companyId
     * @return array
     */
    public function exportLedgers($companyId)
    {
        try {
            $ledgers = Ledger::where('company_id', $companyId)->with('group')->get();
            $xmlData = $this->buildLedgerExportXml($ledgers);

            $response = Http::post("http://{$this->host}:{$this->port}", ['xml' => $xmlData]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'message' => "Exported {$ledgers->count()} ledgers successfully",
                    'count' => $ledgers->count()
                ];
            }
            throw new \Exception('Failed to export ledgers: Invalid response');
        } catch (\Exception $e) {
            Log::error('Ledger export failed: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Ledger export failed: ' . $e->getMessage(),
                'count' => 0
            ];
        }
    }

    /**
     * Import vouchers from Tally
     * @param int $companyId
     * @return array
     */
    public function importVouchers($companyId)
    {
        try {
            $xmlRequest = $this->buildVoucherImportXml();
            $response = Http::post("http://{$this->host}:{$this->port}", ['xml' => $xmlRequest]);

            if ($response->successful()) {
                $xmlResponse = simplexml_load_string($response->body());
                $vouchers = $this->parseVoucherResponse($xmlResponse);

                $count = 0;
                foreach ($vouchers as $voucherData) {
                    $voucherType = VoucherType::where('name', $voucherData['voucher_type'])->where('company_id', $companyId)->first();
                    $currency = Currency::where('code', $voucherData['currency_code'])->where('company_id', $companyId)->first();

                    if (!$voucherType || !$currency) {
                        continue; // Skip if voucher type or currency not found
                    }

                    $voucher = Voucher::create([
                        'company_id' => $companyId,
                        'voucher_type_id' => $voucherType->id,
                        'voucher_number' => $voucherData['voucher_number'],
                        'date' => $voucherData['date'],
                        'narration' => $voucherData['narration'],
                        'amount' => $voucherData['amount'],
                        'currency_id' => $currency->id,
                    ]);

                    foreach ($voucherData['entries'] as $entry) {
                        $ledger = Ledger::where('name', $entry['ledger_name'])->where('company_id', $companyId)->first();
                        $costCenter = $entry['cost_center_name']
                            ? CostCenter::where('name', $entry['cost_center_name'])->where('company_id', $companyId)->first()
                            : null;

                        if (!$ledger) {
                            continue; // Skip if ledger not found
                        }

                        VoucherEntry::create([
                            'voucher_id' => $voucher->id,
                            'ledger_id' => $ledger->id,
                            'amount' => $entry['amount'],
                            'type' => $entry['type'],
                            'cost_center_id' => $costCenter ? $costCenter->id : null,
                        ]);
                    }
                    $count++;
                }

                return [
                    'success' => true,
                    'message' => "Imported $count vouchers successfully",
                    'count' => $count
                ];
            }
            throw new \Exception('Failed to import vouchers: Invalid response');
        } catch (\Exception $e) {
            Log::error('Voucher import failed: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Voucher import failed: ' . $e->getMessage(),
                'count' => 0
            ];
        }
    }

    /**
     * Export vouchers to Tally
     * @param int $companyId
     * @return array
     */
    public function exportVouchers($companyId)
    {
        try {
            $vouchers = Voucher::where('company_id', $companyId)->with(['voucherType', 'entries.ledger', 'entries.costCenter', 'currency'])->get();
            $xmlData = $this->buildVoucherExportXml($vouchers);

            $response = Http::post("http://{$this->host}:{$this->port}", ['xml' => $xmlData]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'message' => "Exported {$vouchers->count()} vouchers successfully",
                    'count' => $vouchers->count()
                ];
            }
            throw new \Exception('Failed to export vouchers: Invalid response');
        } catch (\Exception $e) {
            Log::error('Voucher export failed: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Voucher export failed: ' . $e->getMessage(),
                'count' => 0
            ];
        }
    }

    /**
     * Import stock items from Tally
     * @param int $companyId
     * @return array
     */
    public function importStockItems($companyId)
    {
        try {
            $xmlRequest = $this->buildStockItemImportXml();
            $response = Http::post("http://{$this->host}:{$this->port}", ['xml' => $xmlRequest]);

            if ($response->successful()) {
                $xmlResponse = simplexml_load_string($response->body());
                $stockItems = $this->parseStockItemResponse($xmlResponse);

                $count = 0;
                foreach ($stockItems as $stockItemData) {
                    $stockGroup = StockGroup::where('name', $stockItemData['stock_group'])->where('company_id', $companyId)->first();
                    $unit = Unit::where('name', $stockItemData['unit'])->where('company_id', $companyId)->first();

                    if (!$stockGroup || !$unit) {
                        continue; // Skip if stock group or unit not found
                    }

                    StockItem::updateOrCreate(
                        ['name' => $stockItemData['name'], 'company_id' => $companyId],
                        [
                            'stock_group_id' => $stockGroup->id,
                            'unit_id' => $unit->id,
                            'opening_stock' => $stockItemData['opening_stock'],
                            'rate' => $stockItemData['rate'],
                        ]
                    );
                    $count++;
                }

                return [
                    'success' => true,
                    'message' => "Imported $count stock items successfully",
                    'count' => $count
                ];
            }
            throw new \Exception('Failed to import stock items: Invalid response');
        } catch (\Exception $e) {
            Log::error('Stock item import failed: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Stock item import failed: ' . $e->getMessage(),
                'count' => 0
            ];
        }
    }

    /**
     * Export stock items to Tally
     * @param int $companyId
     * @return array
     */
    public function exportStockItems($companyId)
    {
        try {
            $stockItems = StockItem::where('company_id', $companyId)->with(['stockGroup', 'unit'])->get();
            $xmlData = $this->buildStockItemExportXml($stockItems);

            $response = Http::post("http://{$this->host}:{$this->port}", ['xml' => $xmlData]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'message' => "Exported {$stockItems->count()} stock items successfully",
                    'count' => $stockItems->count()
                ];
            }
            throw new \Exception('Failed to export stock items: Invalid response');
        } catch (\Exception $e) {
            Log::error('Stock item export failed: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Stock item export failed: ' . $e->getMessage(),
                'count' => 0
            ];
        }
    }

    /**
     * Build XML request for importing ledgers
     * @return string
     */
    private function buildLedgerImportXml()
    {
        return <<<XML
<ENVELOPE>
    <HEADER>
        <VERSION>1</VERSION>
        <TALLYREQUEST>Export</TALLYREQUEST>
        <TYPE>Data</TYPE>
        <ID>AllMasters</ID>
    </HEADER>
    <BODY>
        <DESC>
            <STATICVARIABLES>
                <SVEXPORTFORMAT>XML</SVEXPORTFORMAT>
                <SVMASTERTYPE>Ledger</SVMASTERTYPE>
            </STATICVARIABLES>
        </DESC>
    </BODY>
</ENVELOPE>
XML;
    }

    /**
     * Build XML for exporting ledgers
     * @param \Illuminate\Database\Eloquent\Collection $ledgers
     * @return string
     */
    private function buildLedgerExportXml($ledgers)
    {
        $xml = new SimpleXMLElement('<ENVELOPE></ENVELOPE>');
        $header = $xml->addChild('HEADER');
        $header->addChild('VERSION', '1');
        $header->addChild('TALLYREQUEST', 'Import');
        $header->addChild('TYPE', 'Data');
        $header->addChild('ID', 'Ledgers');

        $body = $xml->addChild('BODY');
        $data = $body->addChild('DATA');

        foreach ($ledgers as $ledger) {
            $ledgerNode = $data->addChild('LEDGER');
            $ledgerNode->addAttribute('NAME', htmlspecialchars($ledger->name));
            $ledgerNode->addChild('PARENT', htmlspecialchars($ledger->group->name));
            $ledgerNode->addChild('OPENINGBALANCE', $ledger->opening_balance);
            if ($ledger->contact_details) {
                $ledgerNode->addChild('ADDRESS', htmlspecialchars($ledger->contact_details));
            }
        }

        return $xml->asXML();
    }

    /**
     * Build XML request for importing vouchers
     * @return string
     */
    private function buildVoucherImportXml()
    {
        return <<<XML
<ENVELOPE>
    <HEADER>
        <VERSION>1</VERSION>
        <TALLYREQUEST>Export</TALLYREQUEST>
        <TYPE>Data</TYPE>
        <ID>AllVouchers</ID>
    </HEADER>
    <BODY>
        <DESC>
            <STATICVARIABLES>
                <SVEXPORTFORMAT>XML</SVEXPORTFORMAT>
            </STATICVARIABLES>
        </DESC>
    </BODY>
</ENVELOPE>
XML;
    }

    /**
     * Build XML for exporting vouchers
     * @param \Illuminate\Database\Eloquent\Collection $vouchers
     * @return string
     */
    private function buildVoucherExportXml($vouchers)
    {
        $xml = new SimpleXMLElement('<ENVELOPE></ENVELOPE>');
        $header = $xml->addChild('HEADER');
        $header->addChild('VERSION', '1');
        $header->addChild('TALLYREQUEST', 'Import');
        $header->addChild('TYPE', 'Data');
        $header->addChild('ID', 'Vouchers');

        $body = $xml->addChild('BODY');
        $data = $body->addChild('DATA');

        foreach ($vouchers as $voucher) {
            $voucherNode = $data->addChild('VOUCHER');
            $voucherNode->addAttribute('VCHTYPE', htmlspecialchars($voucher->voucherType->name));
            $voucherNode->addChild('DATE', $voucher->date->format('Ymd'));
            $voucherNode->addChild('VOUCHERNUMBER', htmlspecialchars($voucher->voucher_number));
            $voucherNode->addChild('NARRATION', htmlspecialchars($voucher->narration ?? ''));

            foreach ($voucher->entries as $entry) {
                $entryNode = $voucherNode->addChild('ALLLEDGERENTRIES.LIST');
                $entryNode->addChild('LEDGERNAME', htmlspecialchars($entry->ledger->name));
                $entryNode->addChild('ISDEEMEDPOSITIVE', $entry->type === 'debit' ? 'Yes' : 'No');
                $entryNode->addChild('AMOUNT', $entry->type === 'debit' ? $entry->amount : -$entry->amount);
                if ($entry->costCenter) {
                    $entryNode->addChild('COSTCENTRENAME', htmlspecialchars($entry->costCenter->name));
                }
            }
        }

        return $xml->asXML();
    }

    /**
     * Build XML request for importing stock items
     * @return string
     */
    private function buildStockItemImportXml()
    {
        return <<<XML
<ENVELOPE>
    <HEADER>
        <VERSION>1</VERSION>
        <TALLYREQUEST>Export</TALLYREQUEST>
        <TYPE>Data</TYPE>
        <ID>AllStockItems</ID>
    </HEADER>
    <BODY>
        <DESC>
            <STATICVARIABLES>
                <SVEXPORTFORMAT>XML</SVEXPORTFORMAT>
            </STATICVARIABLES>
        </DESC>
    </BODY>
</ENVELOPE>
XML;
    }

    /**
     * Build XML for exporting stock items
     * @param \Illuminate\Database\Eloquent\Collection $stockItems
     * @return string
     */
    private function buildStockItemExportXml($stockItems)
    {
        $xml = new SimpleXMLElement('<ENVELOPE></ENVELOPE>');
        $header = $xml->addChild('HEADER');
        $header->addChild('VERSION', '1');
        $header->addChild('TALLYREQUEST', 'Import');
        $header->addChild('TYPE', 'Data');
        $header->addChild('ID', 'StockItems');

        $body = $xml->addChild('BODY');
        $data = $body->addChild('DATA');

        foreach ($stockItems as $stockItem) {
            $stockItemNode = $data->addChild('STOCKITEM');
            $stockItemNode->addAttribute('NAME', htmlspecialchars($stockItem->name));
            $stockItemNode->addChild('PARENT', htmlspecialchars($stockItem->stockGroup->name));
            $stockItemNode->addChild('BASEUNITS', htmlspecialchars($stockItem->unit->name));
            $stockItemNode->addChild('OPENINGBALANCE', $stockItem->opening_stock);
            $stockItemNode->addChild('OPENINGRATE', $stockItem->rate);
        }

        return $xml->asXML();
    }

    /**
     * Parse ledger XML response
     * @param SimpleXMLElement $xml
     * @return array
     */
    private function parseLedgerResponse(SimpleXMLElement $xml)
    {
        $ledgers = [];
        foreach ($xml->BODY->DATA->LEDGER as $ledger) {
            $ledgers[] = [
                'name' => (string) $ledger['NAME'],
                'parent' => (string) $ledger->PARENT,
                'opening_balance' => (float) ($ledger->OPENINGBALANCE ?? 0),
                'contact_details' => (string) ($ledger->ADDRESS ?? null),
            ];
        }
        return $ledgers;
    }

    /**
     * Parse voucher XML response
     * @param SimpleXMLElement $xml
     * @return array
     */
    private function parseVoucherResponse(SimpleXMLElement $xml)
    {
        $vouchers = [];
        foreach ($xml->BODY->DATA->VOUCHER as $voucher) {
            $entries = [];
            foreach ($voucher->{'ALLLEDGERENTRIES.LIST'} as $entry) {
                $entries[] = [
                    'ledger_name' => (string) $entry->LEDGERNAME,
                    'amount' => abs((float) $entry->AMOUNT),
                    'type' => (string) $entry->ISDEEMEDPOSITIVE === 'Yes' ? 'debit' : 'credit',
                    'cost_center_name' => (string) ($entry->COSTCENTRENAME ?? null),
                ];
            }

            $vouchers[] = [
                'voucher_type' => (string) $voucher['VCHTYPE'],
                'voucher_number' => (string) $voucher->VOUCHERNUMBER,
                'date' => date('Y-m-d', strtotime((string) $voucher->DATE)),
                'narration' => (string) ($voucher->NARRATION ?? null),
                'amount' => (float) $entries[0]['amount'], // Assuming first entry amount
                'currency_code' => 'INR', // Default, adjust based on response
                'entries' => $entries,
            ];
        }
        return $vouchers;
    }

    /**
     * Parse stock item XML response
     * @param SimpleXMLElement $xml
     * @return array
     */
    private function parseStockItemResponse(SimpleXMLElement $xml)
    {
        $stockItems = [];
        foreach ($xml->BODY->DATA->STOCKITEM as $stockItem) {
            $stockItems[] = [
                'name' => (string) $stockItem['NAME'],
                'stock_group' => (string) $stockItem->PARENT,
                'unit' => (string) $stockItem->BASEUNITS,
                'opening_stock' => (float) ($stockItem->OPENINGBALANCE ?? 0),
                'rate' => (float) ($stockItem->OPENINGRATE ?? 0),
            ];
        }
        return $stockItems;
    }
}

