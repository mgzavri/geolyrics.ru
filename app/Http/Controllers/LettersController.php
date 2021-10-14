<?php

namespace App\Http\Controllers;

use App\Models\Letters;
use Illuminate\Http\Request;

class LettersController extends Controller
{
    public function index()
    {
        return  Letters::all();
    }

    public function Trans()
    {
        return  Letters::select('ka', 'ru', 'en')->get();
    }
}
