<?php

namespace App\Helpers;

use App\Models\SystemLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class SystemLogHelper
{
    public static function logSystemActivity($processName, $userId, $narration)
    {
        SystemLog::create([
            'process_name' => $processName,
            'user_id' => $userId,
            'narration' => $narration,
        ]);
    }




    function filter_assets_table(Request $request)
    {

        //     // {tag:tag,category:category,'_token':token(),manufacturer:manufacturer,location:location,status:status,from:from,to:to}
        // var_dump('tag'.$request->tag);
        // var_dump('category'.$request->category);
        // var_dump('manufacturer'.$request->manufacturer);
        // var_dump('location'.$request->location);
        // var_dump('status'.$request->status);
        // var_dump('from'.$request->from);
        // var_dump('to'.$request->to);

        // var_dump('supplier'.$request->supplier);
        // return false;

        //      if($request->tag != 'all' && $request->tag != NULL){
        //     var_dump('tag here'.$request->tag);
        // }
        //
        $request_data = $request;
        $start_date = $request_data->start_date;
        $end_date = $request_data->end_date;

        $assets = DB::table('assets')->join('flexassets_categories', 'assets.category_id', '=', 'flexassets_categories.id')
            ->join('asset_provider', 'asset_provider.asset_id', '=', 'assets.id')
            ->join('providers', 'providers.id', '=', 'asset_provider.provider_id')
            ->join('asset_movement', 'assets.id', '=', 'asset_movement.asset_id')
            ->join('movements', 'movements.id', '=', 'asset_movement.movement_id')
            ->join('sites', 'sites.id', '=', 'movements.site_id')
            ->join('locations', 'sites.location_id', '=', 'locations.id')
            ->join('statuses', 'statuses.id', '=', 'assets.status')
            ->join('flexassets_current_asset_prices', 'flexassets_current_asset_prices.asset_id', '=', 'assets.id')
            ->join('currencies', 'currencies.id', '=', 'flexassets_current_asset_prices.currency')
            ->orderBy('flexassets_current_asset_prices.id', 'ASC')
            ->join('flexassets_current_asset_prices as current_asset_value', 'current_asset_value.asset_id', '=', 'assets.id')
            ->orderBy('current_asset_value.id', 'DESC')
            ->where(function ($query) use ($request_data) {


                if ($request_data->tag != 'all' && $request_data->tag != NULL) {
                    $query->where('tag', '=', $request_data->tag);
                }

                if ($request_data->supplier != 'all' && $request_data->supplier != NULL) {
                    $query->where('providers.id', '=', $request_data->supplier);
                }
                if ($request_data->category != 'all' && $request_data->category != NULL) {
                    $query->where('category_id', '=', $request_data->category);
                }

                if ($request_data->location != 'all'  && $request_data->location != NULL) {

                    $query->where('locations.location_id', '=', $request_data->location);
                }
                if ($request_data->status != 'all' && $request_data->status != NULL) {
                    $query->where('assets.status', '=', $request_data->status);
                }
                if ($request_data->start_date != NULL) {

                    $query->whereDate('assets.created_at', '>=', $request_data->start_date);
                }

                if ($request_data->end_date != NULL) {

                    $query->whereDate('assets.created_at', '<=', $request_data->end_date);
                }
            })->select('flexassets_categories.name as category_name', 'providers.name as provider_name', 'assets.*', 'sites.name as site_name', 'locations.name as location_name', 'statuses.name as status_name', 'current_asset_value.current_price', 'current_asset_value.current_price as book_value', 'currencies.code')->get();
        // dd($assets);
        return  $assets;
    }

}
