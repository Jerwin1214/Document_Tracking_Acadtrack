<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubjectStream;
use Illuminate\Http\Request;

class StreamController extends Controller
{
    public function index() {
        // TODO: Implement index() method.
        return view('pages.admin.stream.index');
    }

    public function create() {
        return view('pages.admin.stream.add');
    }

    public function store(Request $request) {
        // TODO: Implement store() method.
    }

    public function show(SubjectStream $subjectStream) {
        // TODO: Implement show() method.
    }

    public function edit(SubjectStream $subjectStream) {
        // TODO: Implement edit() method.
    }

    public function update(Request $request, SubjectStream $subjectStream) {
        // TODO: Implement update() method.
    }

    public function destroy(SubjectStream $subjectStream) {
        // TODO: Implement destroy() method.
    }

    public function assignSubjectsView(SubjectStream $subjectStream) {
        // TODO: Implement assignSubjectsView() method.
    }

    public function assignSubjects(Request $request, SubjectStream $subjectStream) {
        // TODO: Implement assignSubjects() method.
    }
}
