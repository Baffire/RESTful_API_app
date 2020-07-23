<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FilesController extends Controller
{
    public function show()
    {
        $pathToFile = storage_path('app\Garri_Potter_i_metody_ratsionalnogo_myshlenia.fb2');
        $name = 'Garri_Potter_i_metody_ratsionalnogo_myshlenia';

        return response()->download($pathToFile, $name);
    }

    public function create(Request $request)
    {
        $pathToFile = $request->file('photo')->store('testing');

        return response()->json(['path' => $pathToFile], 200);
    }
}
