<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use Illuminate\Http\Request;


class ServicePurchaseController extends Controller
{


    private static function get_tabs()
    {
        // return "services::Services,services::Schedules,services::Quotations,services::Profomas,services::Delivery,services::Providers";
        // return "services::Services,services::Schedules,services::Providers";
        return "services::Services,services::Providers";
    }
    public function index()
    {
        $_tabs = ServicePurchaseController::get_tabs();
        $providers = Provider::all();
        $data = [
            'tabs' => $_tabs,
            'providers' => $providers
        ];

        return view('services.index', $data);
    }

    public function providers()
    {
        $_tabs = ServicePurchaseController::get_tabs();
        $providers = Provider::orderBy('updated_at', 'desc')->paginate();
        $data = [
            'tabs' => $_tabs,
            'items' => $providers,
            'itemsTableHeaders' => "Picture,Code, Name, Phone, Email,Location,Category,Contract(s)"
        ];

        return view('services.providers', $data);
    }

    public function viewProviderContract($id)
    {

        // return 'am here';
        $_tabs = ServicePurchaseController::get_tabs();
        $contracts = ProviderContract::find($id);
        $provider_id = $contracts->provider_id;
        $data = [
            'tabs' => $_tabs,
            'provider_id' => $provider_id,
            'contracts' => $contracts
        ];

        return view('services.view_contract', $data);
    }

    public function providerDetails($id)
    {

        $_tabs = ServicePurchaseController::get_tabs();
        $currencies = Currency::all();
        $provider = Provider::find($id);
        $contracts = ProviderContract::where('provider_id', $id)->get();
        $data = [
            'tabs' => $_tabs,
            'provider' => $provider,
            'contracts' => $contracts,
            'currencies' => $currencies,
            'period' => Period::orderBy('created_at', 'desc')->get()
        ];

        return view('services.provider', $data);
    }


    public function addProvider(Request $request)
    {
        $provider = new Provider();
        $provider->code = $this->generateCode();
        $provider->name = $request->name;
        $provider->phone = $request->phone;
        $provider->email = $request->email;
        $provider->description = $request->description;
        $provider->address = $request->address;
        if ($request->image == null) {
            $provider->image = "image.jpg";
        } else {
            $provider->image = $request->image;
        }
        $provider->type = $request->type;
        $provider->save();

        $provider_name =  $provider->name;
        if ($provider->save()) {
            SettingsController::saveAuditTrail('Successful create a provider titled "' . $provider_name . '"', 0);
        }

        return redirect()->route('services::providers');
    }

    public function addContract(Request $request)
    {


        //     if($request->hasFile('contract_file')){
        //         //get file name
        //         $filenameWithExt = $request->file('contract_file')->getClientOriginalName();
        //         //get just filename
        //         $filename = pathInfo($filenameWithExt,PATHINFO_FILENAME);
        //         //get just ext
        //         $extension= $request->file('contract_file')->getClientOriginalExtension();
        //         //filename to store
        //         $fileNameToStore = $filename.'_'.time().'.'.$extension;
        //         //upload image
        //         $path = $request->file('contract_file')->storeAs('public/uploads', $fileNameToStore);


        // }else{
        //     $fileNameToStore = null;
        // }

        if ($request->email_four != null) {
            $supplier_email = $request->email_four;
            $title = $request->title;
            $contractCheck = ProviderContract::where('email_four', $supplier_email)->where('title', $title)->first();
            if ($contractCheck != null) {
                return redirect()->back()->with('error', 'Sorry!, Contract with title "' . $title . '" has already exist for this supplier!.');
            }
        }
        if ($request->email_one == null) {
            return redirect()->back()->with('error', 'Please insert "1st Email" in the field below.');
        }
        if ($request->email_two == null) {
            return redirect()->back()->with('error', 'Please insert "2nd Email" in the field below.');
        }
        if ($request->email_three == null) {
            return redirect()->back()->with('error', 'Please insert "3rd Email" in the field below.');
        }

        $email_one = $request->email_one;
        $email_two = $request->email_two;
        $email_three = $request->email_three;
        $email_four = $request->email_four;
        // return $email_one;


        $contract = new ProviderContract();
        $contract->title = $request->title;
        $contract->description = $request->description;
        $contract->file = $request->file;
        $contract->provider_id = $request->provider_id;
        // check email if inserted and save to database;

        if ($email_one != null) {
            $contract->sign_one_position = $request->sign_one_position;
        }
        if ($email_two != null) {
            $contract->sign_two_position = $request->sign_two_position;
        }
        if ($email_three != null) {
            $contract->sign_three_position = $request->sign_three_position;
        }
        if ($email_four != null) {
            $contract->sign_four_position = "Service Provider";
        }

        $contract->email_one = $email_one;
        $contract->email_two = $email_two;
        $contract->email_three = $email_three;
        $contract->email_four = $email_four;

        $contract->file = $request->file;
        $contract->save();

        $providerContractExtraInfo = new ProviderContractExtraInfo();
        $providerContractExtraInfo->contractor_id = $contract->id;
        $providerContractExtraInfo->start = $request->start;
        $providerContractExtraInfo->end = $request->end;
        $providerContractExtraInfo->response_time_category = $request->response_time_category;
        $providerContractExtraInfo->response_time = $request->response_time;
        $providerContractExtraInfo->cost = $request->cost;
        $providerContractExtraInfo->cost_currency = $request->cost_currency;
        $providerContractExtraInfo->save();


        // sending emails to signatories;
        $vendor = Provider::find($contract->provider_id);

        if ($email_one != null) {
            $secret_one = Crypt::encryptString($contract->id . '::signature_one::' . $email_one);
            $contract->secret_one = $secret_one;
            ServicePurchaseController::send_email_to_supplier_signatories($email_one, $contract, $vendor, $secret_one);
        }
        if ($email_two != null) {
            $secret_two = Crypt::encryptString($contract->id . '::signature_two::' . $email_two);
            $contract->secret_two = $secret_two;
            ServicePurchaseController::send_email_to_supplier_signatories($email_two, $contract, $vendor, $secret_two);
        }
        if ($email_three != null) {
            $secret_three = Crypt::encryptString($contract->id . '::signature_three::' . $email_three);
            $contract->secret_three = $secret_three;
            ServicePurchaseController::send_email_to_supplier_signatories($email_three, $contract, $vendor, $secret_three);
        }
        if ($email_four != null) {
            $secret_four = Crypt::encryptString($contract->id . '::signature_four::' . $email_four);
            $contract->secret_four = $secret_four;
            ServicePurchaseController::send_email_to_supplier_signatories($email_four, $contract, $vendor, $secret_four);
        }
        return redirect()->route('services::provider_details', ['id' => $request->provider_id])->with('success', 'Contract is succesful registered.');
    }

    public static function send_email_to_supplier_signatories($email_address, $contract, $vendor, $secret_key)
    {

        if (!empty($email_address)) {
            Mail::send('mail.contract', ['name' => $email_address, 'secret' => $secret_key, 'contract_name' => $contract->title, 'flex_client' => "FLEX ASSETS", 'vendor' => $vendor->name], function ($message) use ($email_address) {
                $message->to(
                    $email_address,
                    $email_address
                )->subject('Sign Contract Link');
                $message->from('tinodady33@gmail.com', 'LHRC Flex Assets ( Service Provision Contract)');
            });
        }
    }


    public function signContract($secret)
    {
        $secret_key = Crypt::decryptString($secret);
        $content = explode('::', $secret_key);
        $data = [
            'id' => $content[0],
            'signatory' => $content[1],
            'email' => $content[2]
        ];
        return view('services.sign', $data);
    }

    public static function error_to_sign()
    {
        return "Sorry, you can sign this form only onces.";
    }
    public function submitSignature(Request $request)
    {
        $contract = ProviderContract::find($request->id);
        $signatory = $request->signatory;

        if ($request->hasFile('signature')) {
            //get file name
            $filenameWithExt = $request->file('signature')->getClientOriginalName();
            //get just filename
            $filename = pathInfo($filenameWithExt, PATHINFO_FILENAME);
            //get just ext
            $extension = $request->file('signature')->getClientOriginalExtension();
            //filename to store
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;
            //upload image
            $path = $request->file('signature')->storeAs('public/uploads', $fileNameToStore);
        } else {
            $fileNameToStore = null;
        }



        if ($signatory == "signature_one") {
            if ($contract->sign_one == null) {
                $contract->sign_one = $fileNameToStore;
                $contract->sign_one_fullname = $request->fullname;
                $contract->save();
                return view('services.signed');
            } else {
                return view('services.sign_error');
            }
        }
        if ($signatory == "signature_two") {
            if ($contract->sign_two == null) {
                $contract->sign_two = $fileNameToStore;
                $contract->sign_two_fullname = $request->fullname;
                $contract->save();
                return view('services.signed');
            } else {
                return view('services.sign_error');
            }
        }
        if ($signatory == "signature_three") {
            if ($contract->sign_three == null) {
                $contract->sign_three = $fileNameToStore;
                $contract->sign_three_fullname = $request->fullname;
                $contract->save();
                return view('services.signed');
            } else {
                return view('services.sign_error');
            }
        }
        if ($signatory == "signature_four") {
            if ($contract->sign_four == null) {
                $contract->sign_four = $fileNameToStore;
                $contract->sign_four_fullname = $request->fullname;
                $contract->save();
                return view('services.signed');
            } else {
                return view('services.sign_error');
            }
        }
    }

    public function uploadCsv(Request $request)
    {
        if ($request->file('file')) {

            if (env('APP_ENV') == "production") {
                $folderPath = 'public/storage/uploads/';
            } else {
                $folderPath = 'storage/uploads/';
            }
            $extension = $request->file('file')->getClientOriginalExtension();
            $fileName = $folderPath . uniqid() . '.' . $extension;

            //$path = $request->file('file')->storePubliclyAs('/public/uploads', $imageName);

            move_uploaded_file($request->file('file'), $fileName) or die("file error");

            $array = array('delimiter' => ',');


            $file_handle = fopen($fileName, 'r');


            while (!feof($file_handle)) {

                $line_of_text[] = fgetcsv($file_handle, 0, $array['delimiter']);
            }
            fclose($file_handle);

            $data = [
                'filename' => $fileName,
                'content' => $line_of_text[0]
            ];

            return json_encode($data);
        }

        return json_encode("No File Found");
    }

    public function importCsv(Request $request)
    {
        dd($request->all());
        $filename = $request->file_name;
        $product_name = $request->product_name;
        $product_category  = $request->product_category;
        $product_description = $request->product_description;
        $product_image = $request->product_image;
        $product_price = $request->product_price;
        $product_currency = $request->product_currency;
        $product_code = $request->product_code;

        $array = array('delimiter' => ',');

        $file_handle = fopen($filename, 'r');

        while (!feof($file_handle)) {
            $line_of_text[] = fgetcsv($file_handle, 0, $array['delimiter']);
        }

        fclose($file_handle);


        $header = $line_of_text[0];

        $named_indices = array();

        $name_index = array_search($product_name, $header);
        $category_index = array_search($product_category, $header);
        $description_index = array_search($product_description, $header);
        $image_index = array_search($product_image, $header);
        $price_index = array_search($product_price, $header);
        $currency_index = array_search($product_currency, $header);
        $code_index = array_search($product_code, $header);


        if ($name_index !== false) {
            array_push($named_indices, $name_index);
        }
        if ($category_index !== false) {
            array_push($named_indices, $category_index);
        }
        if ($code_index !== false) {
            array_push($named_indices, $code_index);
        }
        if ($description_index !== false) {
            array_push($named_indices, $description_index);
        }
        if ($image_index !== false) {
            array_push($named_indices, $image_index);
        }
        if ($price_index !== false) {
            array_push($named_indices, $price_index);
        }
        if ($currency_index !== false) {
            array_push($named_indices, $currency_index);
        }

        for ($i = 1; $i < count($line_of_text) - 1; $i++) {

            $item = new Item();
            $item->name = $line_of_text[$i][$name_index];
            $item->code = $line_of_text[$i][$code_index];
            $item->description = $line_of_text[$i][$description_index];
            $item->average_price = $line_of_text[$i][$price_index];
            $item->average_price_currency_id = $this->getCurrencyId($line_of_text[$i][$currency_index]);

            if ($image_index === false) {
                if (env('APP_ENV') == "production") {
                    $item->image = asset('public/storage/images/image.jpg');
                } else {
                    $item->image = asset('storage/images/image.jpg');
                }
            } else {
                $item->image = $line_of_text[$i][$image_index];
            }

            $item->category_id = $this->getCategoryId($line_of_text[$i][$category_index]);

            $item->save();


            foreach ($header as $key => $value) {
                $check_index = in_array($key, $named_indices);

                if ($check_index === false) {
                    $item_attribute = new AttributeItem();
                    $item_attribute->name = $value;
                    $item_attribute->value = $line_of_text[$i][$key];
                    $item_attribute->data_type = 'text';
                    $item_attribute->item_id = $item->id;
                    $item_attribute->save();
                }
            }
        }
        return json_encode($line_of_text);
    }


    function getCategoryId($category_name)
    {
        $category = Category::where('name', $category_name)->first();

        if ($category == null) {
            $category = new Category();
            $category->name = $category_name;
            $category->code = $this->generateCategoryCode($category_name);
            $category->description = $category_name;

            $category->save();
        }

        return $category->id;
    }

    function getCurrencyId($iso)
    {
        $currency = Currency::where('code', $iso)->first();

        if ($currency == null) {
            $currency = new Currency();
            $currency->name = $iso;
            $currency->code = $iso;
            $currency->value = 0;

            $currency->save();
        }

        return $currency->id;
    }

    function generateCode($length = 4)
    {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';

        $available = false;

        while ($available || (strlen($randomString) === 0)) {

            $order_reqs = Provider::where('code', $randomString)->get();

            if ($order_reqs) {
                $available = false;
            } else {
                $available = true;
            }

            $new_code = '';

            for ($i = 0; $i < $length; $i++) {
                $new_code .= $characters[rand(0, $charactersLength - 1)];
            }

            $randomString = $new_code;
        }

        return $randomString;
    }

    function generateCategoryCode($length = 8)
    {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';

        $available = false;

        while ($available || (strlen($randomString) === 0)) {

            $order_reqs = Category::where('code', $randomString)->get();

            if ($order_reqs) {
                $available = false;
            } else {
                $available = true;
            }

            $new_code = '';

            for ($i = 0; $i < $length; $i++) {
                $new_code .= $characters[rand(0, $charactersLength - 1)];
            }

            $randomString = $new_code;
        }

        return $randomString;
    }

    function download_image($url)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);

        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }


    //Service functions

    public function services()
    {

        $_tabs = ServicePurchaseController::get_tabs();

        // $a = Model::where('code', '=', $code)
        //     ->where('col_a', '=' , 1);

        //     $b = Model::where('code', '=', $code)->where('col_b', '=' , 1)
        //     ->union($a)
        //     ->get();

        //     $result = $b;

        $maintenances = Maintenance::orderBy('updated_at', 'desc')->paginate();
        $repairs = Repair::orderBy('updated_at', 'desc')->paginate();

        $services = $maintenances->merge($repairs);

        // dump(count($services));
        // return false;

        // $services = $services->all();


        $data = [
            'tabs' => $_tabs,
            'services' =>  $services,
        ];

        return view('services.services', $data);
    }

    public function addService(Request $request)
    {

        if ($request->type == 'repair') {

            $service = new Repair();
            $service->title = $request->title;
            $service->description = $request->description;
            $service->start = $request->start;
            $service->end = $request->end;
            $service->comments = $request->comments;
            $service->user_id = auth()->id();
            $service->provider_id = $request->provider;
            $service->save();
        } else {

            $service = new Maintenance();
            $service->title = $request->title;
            $service->description = $request->description;
            $service->scheduled_on = $request->scheduled_on;
            $service->start = $request->start;
            $service->end = $request->start;
            $service->user_id = auth()->id();
            $service->provider_id = $request->provider;
            $service->save();
        }

        return redirect()->route('services::services', ['id' => $service->id]);
    }

    public function deleteService(Request $request)
    {

        return view('services.services', $data);
    }



    public function editService(Request $request)
    {
        $service = [
            'name' => $request->name,
        ];

        Service::whereId($request->id)->update($service);

        return redirect()->route('settings::services', ['id' => $request->id]);
    }



    //Schedules functions

    public function schedules()
    {

        $_tabs = ServicePurchaseController::get_tabs();

        // $a = Model::where('code', '=', $code)
        //     ->where('col_a', '=' , 1);

        //     $b = Model::where('code', '=', $code)->where('col_b', '=' , 1)
        //     ->union($a)
        //     ->get();

        //     $result = $b;

        $maintenances = Maintenance::all();
        $repairs = Repair::all();

        $services = $maintenances->merge($repairs);

        $services = $services->all();


        $data = [
            'tabs' => $_tabs,
            'services' =>  $services,
        ];

        return view('services.schedules', $data);
    }

    public function addSchedule(Request $request)
    {



        if ($request->type == 'repair') {

            $service = new Repair();
            $service->title = $request->title;
            $service->description = $request->description;
            $service->start = $request->start;
            $service->end = $request->end;
            $service->comments = $request->comments;
            $service->user_id = auth()->id();
            $service->provider_id = $request->provider;
            $service->save();
        } else {

            $service = new Maintenance();
            $service->title = $request->title;
            $service->description = $request->description;
            $service->scheduled_on = $request->scheduled_on;
            $service->start = $request->start;
            $service->end = $request->start;
            $service->user_id = auth()->id();
            $service->provider_id = $request->provider;
            $service->save();
        }

        return redirect()->route('services::services', ['id' => $service->id]);
    }

    public function deleteSchedule(Request $request)
    {

        return view('services.services', $data);
    }



    public function editSchedule(Request $request)
    {
        $service = [
            'name' => $request->name,
        ];

        Service::whereId($request->id)->update($service);

        return redirect()->route('services::services', ['id' => $request->id]);
    }
}