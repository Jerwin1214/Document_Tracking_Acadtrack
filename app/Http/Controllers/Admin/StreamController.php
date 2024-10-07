<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\SubjectStream;
use Illuminate\Http\Request;

class StreamController extends Controller
{
    public static function index() {
        // TODO: Implement index() method.
        $streams = SubjectStream::select(['id', 'stream_name'])->get();
        return view('pages.admin.stream.index', ['streams' => $streams]);
    }

    public static function create() {
        return view('pages.admin.stream.add');
    }

    public static function store(Request $request) {
        // validate the user inputs
        $request->validate([
            'stream_name' => ['required', 'string', 'max:255'],
            'stream_code' => ['string', 'max:255', 'nullable'],
            'stream_description' => ['string', 'max:255', 'nullable'],
        ]);

        // insert to table
        SubjectStream::create([
            'stream_name' => $request->stream_name,
            'stream_code' => $request->stream_code,
            'stream_description' => $request->stream_description,
        ]);

        // redirect to the index page
        return redirect()->route('admin.streams.index')->with('success', 'New Subject Stream added successfully');
    }

    public static function show(SubjectStream $subjectStream) {
        // TODO: Implement show() method.
    }

    public static function edit(SubjectStream $subjectStream) {
        // TODO: Implement edit() method.
    }

    public static function update(Request $request, SubjectStream $subjectStream) {
        // TODO: Implement update() method.
    }

    public static function destroy(SubjectStream $subjectStream) {
        // TODO: Implement destroy() method.
    }

    public static function assignSubjectsView(SubjectStream $stream) {
        $subjects = Subject::select(['id', 'name'])->get();
        return view('pages.admin.stream.assign-subjects', ['stream' => $stream, 'subjects' => $subjects]);
    }

    public static function assignSubjects(Request $request, SubjectStream $subjectStream) {
        // TODO: Implement assignSubjects() method.
    }
}
