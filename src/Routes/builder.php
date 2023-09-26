<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Route::post("build_element",function(Request $request){
    foreach ($request->form_element as $key => $value) {
        if ($value == 1) {
            $make = "controller";
        }elseif ($value == 2) {
            $make = "model";
        }elseif ($value == 3) {
            $make = "view";
        }
        if ($request->dbms == 1) {
            $command = "build:crud";
            if ($value == 1) {
                $param = [
                    'name' => $request->table_name,
                    '--schema' => $request->schema_name,
                    '--make' => $make,
                    '--routes' => true,
                    '--breadcrumbs' => false,
                ];
            }else {
                $param = [
                    'name' => $request->table_name,
                    '--schema' => $request->schema_name,
                    '--make' => $make,
                    '--routes' => false,
                    '--breadcrumbs' => false,
                ];
            }
        }else {
            $command = "postgres:crud";
            if ($value == 1) {
                $param = [
                    'name' => $request->table_name,
                    '--schema' => $request->schema_name,
                    '--make' => $make,
                    '--routes' => true,
                    '--breadcrumbs' => false,
                ];
            }else {
                $param = [
                    'name' => $request->table_name,
                    '--schema' => $request->schema_name,
                    '--make' => $make,
                    '--routes' => false,
                    '--breadcrumbs' => false,
                ];
            }
        }
        Artisan::call($command, $param);
    }
    return response()->json([
        "code"      => 200,
        "message"   => "Form element berhasil dibuat",
        "response"  => [
            "url"   => url($request->table_name)
        ]
    ]);
});

Route::get("get_schema",function(){
    $schemaNames = DB::table('information_schema.schemata')
    ->pluck('schema_name');
    return response()->json([
        "code"      => 200,
        "message"   => "OK",
        "response"  => $schemaNames
    ]);
});

Route::post("get_table",function(Request $request){
    $tableNames = DB::table('information_schema.tables')
        ->where('table_schema', $request->schema)
        ->pluck('table_name');

    return response()->json([
        "code"      => 200,
        "message"   => "OK",
        "response"  => $tableNames
    ]);
});