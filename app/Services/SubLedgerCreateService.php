<?php

namespace App\Services;
use App\Models\Account\LedgerAccount;
use App\Models\Account\SubAccount;

class SubLedgerCreateService {

    public function create_ledger($input){

        $ledger_account = LedgerAccount::find( $input['ledger_id']);
        $last_code = $ledger_account;

        if ($last_code) {
            $client_code=$last_code->client_code+1;
        }
        else
        {
            $client_code=$last_code->account->client_start_code;
        }
    
        $payload['client_code'] = $client_code;
        $payload['code'] = $ledger_account->code;
        $payload["tag"] = 'sub';
        $payload["self_ledger_id"] =$ledger_account->id;
        $payload["sub_account_id"] = $ledger_account->sub_account_id;
        if( $this->check_key('name', $input ) && $input['name']){
            $payload["name"] = $input['name'];
        }
        if($this->check_key('type', $input ) && $input['type'] == "supplier"){
            $payload["supplier_id"] = $input['id'];
        }
        if($this->check_key('type', $input ) && $input['type'] == "customer"){
            $payload["customer_id"] = $input['id'];
        }

        LedgerAccount::create($payload);

        return 'ledger created succesfully';

    }

    private function check_key($key, $array){
      return array_key_exists($key , $array);
    }

}
