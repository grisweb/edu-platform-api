<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetCoursesRequest;
use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Http\Resources\CourseCollection;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CourseController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
        //$this->middleware('role:admin')->only(['']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  GetCoursesRequest  $request
     * @return JsonResponse
     */
    public function index(GetCoursesRequest $request): JsonResponse
    {
        $user = Auth::user();
        $courses = $user->courses();

        if ($request->has('search')) {
            $search = $request->query('search');
            $courses = $courses->where('name', 'LIKE', '%'.$search.'%');
        }

        $perPage = $request->query('perPage');
        $courses = $courses->paginate($perPage);

        return $this->handleResponse(new CourseCollection($courses));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreCourseRequest  $request
     * @return JsonResponse
     */
    public function store(StoreCourseRequest $request): JsonResponse
    {
        $course = Course::create([
            'uuid' => Str::uuid(),
            'name' => $request->input('name'),
            'description' => $request->has('description') ? $request->input('description') : null,
            'user_id' => Auth::user()->id
        ]);

        return $this->handleResponse(new CourseResource($course), 'New course created successfully!', 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Course  $course
     * @return JsonResponse
     */
    public function show(Course $course): JsonResponse
    {
        return $this->handleResponse(new CourseResource($course));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateCourseRequest  $request
     * @param  Course  $course
     * @return Response
     */
    public function update(UpdateCourseRequest $request, Course $course)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Course  $course
     * @return JsonResponse
     */
    public function destroy(Course $course): JsonResponse
    {
        $course->delete();
        return $this->handleResponse(new CourseResource($course));
    }
}
