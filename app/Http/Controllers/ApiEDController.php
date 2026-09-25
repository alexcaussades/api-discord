<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiEDController extends Controller
{
    
    public function actionWithInfo(string $id, string $info)
    {
        return response()->json(['id' => $id, 'info' => $info, 'status' => 'success']);
    }

    public function action(string $id, string $galaxie = "")
    {
        return response()->json(['id' => $id, 'galaxie' => $galaxie ?: 'Null', 'status' => 'success']);
    }
}
