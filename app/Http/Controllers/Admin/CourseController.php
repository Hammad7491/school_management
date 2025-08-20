<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    // List courses
    public function index()
    {
        $courses = Course::latest()->get();
        return view('admin.courses.index', compact('courses'));
    }

    // Create page (form)
    public function create()
    {
        return view('admin.courses.create');
    }

    // Store new course
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:150',
            'fee'         => 'nullable|numeric',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|max:2048', // max 2MB
        ]);

        $path = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('courses', 'public'); // storage/app/public/courses
        }

        Course::create([
            'user_id'     => auth()->id(),
            'name'        => $request->name,
            'fee'         => $request->fee,
            'description' => $request->description,
            'image_path'  => $path,   // can be null
            'status'      => null,    // keep null as requested
        ]);

        return redirect()->route('courses.index')->with('success', 'Course added!');
    }

    // Edit page (reuse create view with $course)
    public function edit($id)
    {
        $course = Course::findOrFail($id);
        return view('admin.courses.create', compact('course'));
    }

    // Update course
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'        => 'required|string|max:150',
            'fee'         => 'nullable|numeric',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|max:2048',
        ]);

        $course = Course::findOrFail($id);

        $data = [
            'name'        => $request->name,
            'fee'         => $request->fee,
            'description' => $request->description,
            // 'status' stays null unless you decide later
        ];

        // Handle new image (optional)
        if ($request->hasFile('image')) {
            // delete old image if exists
            if ($course->image_path && Storage::disk('public')->exists($course->image_path)) {
                Storage::disk('public')->delete($course->image_path);
            }
            $data['image_path'] = $request->file('image')->store('courses', 'public');
        }

        $course->update($data);

        return redirect()->route('courses.index')->with('success', 'Course updated!');
    }

    // Delete course
    public function destroy($id)
    {
        $course = Course::findOrFail($id);

        // delete image file
        if ($course->image_path && Storage::disk('public')->exists($course->image_path)) {
            Storage::disk('public')->delete($course->image_path);
        }

        $course->delete();

        return redirect()->route('courses.index')->with('success', 'Course deleted!');
    }
}
