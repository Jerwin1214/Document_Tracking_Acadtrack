<?php

namespace App\Http\Controllers;

use App\Models\Stundet;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        // TODO: implement the index method
        return view('pages.students.dashboard');
    }

    public function create()
    {
        // TODO: implement the create method
    }

    public function store()
    {
        // TODO: implement the store method
    }

    public function show(Stundet $stundet)
    {
        // TODO: implement the show method
    }

    public function edit(Stundet $stundet)
    {
        // TODO: implement the edit method
    }

    public function update(Stundet $stundet)
    {
        // TODO: implement the update method
    }

    public function destroy(Stundet $stundet)
    {
        // TODO: implement the destroy method
    }
}
