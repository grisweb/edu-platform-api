<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetUsersRequest;
use App\Http\Requests\StoreUsersRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\MsGraphService;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Client\HttpClientException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class UserController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
        //$this->middleware('role:admin')->only(['']);
    }

    /**
     * @return JsonResponse
     */
    public function me(): JsonResponse
    {
        $user = Auth::getUser();
        return $this->handleResponse($user);
    }

    /**
     * @param  MsGraphService  $msGraph
     * @param  Request  $request
     * @return JsonResponse
     * @throws ValidationException
     * @throws Exception
     */
    public function autocompleteSearch(MsGraphService $msGraph, Request $request): JsonResponse
    {
        validator($request->query(), [
            'q' => 'required'
        ])->validate();

        $msUsers = $msGraph->findUsers($request->query('q'));

        if ($msUsers) {
            $msUsersIds = array_column($msUsers, 'id');
            $usersIds = User::whereIn('ms_id', $msUsersIds)->addSelect('ms_id')->get()->pluck('ms_id');

            $msUsers = collect($msUsers)->filter(function ($value) use ($usersIds) {
                return !$usersIds->contains($value['id']);
            })->values()->all();
        }

        return $this->handleResponse($msUsers);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  GetUsersRequest  $request
     * @return JsonResponse
     */
    public function index(GetUsersRequest $request): JsonResponse
    {
        $perPage = $request->query('perPage');
        $users = User::where('role', $request->query('role'));

        if ($request->has('search')) {
            $search = $request->query('search');
            $users = $users->where('name', 'LIKE', '%'.$search.'%')->orWhere('email', 'LIKE',
                '%'.$search.'%');
        }

        $users = $users->where('role', $request->query('role'))->whereNot('id', Auth::user()->id)->paginate($perPage);

        return $this->handleResponse(new UserCollection($users));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreUsersRequest  $request
     * @param  MsGraphService  $msGraph
     * @return JsonResponse
     * @throws HttpClientException
     */
    public function store(StoreUsersRequest $request, MsGraphService $msGraph): JsonResponse
    {
        $role = $request->input('role');
        $currentUserRole = Auth::user()->role;

        if ($currentUserRole === $role && $role === 'teacher') {
            abort(403, 'Access denied');
        }

        $users = [];
        $request->collect('users')->each(function ($userId) use (&$users, $msGraph, $role) {
            $msUser = $msGraph->getUserById($userId);
            $date = Carbon::now();

            $users[] = [
                'ms_id' => $msUser['id'],
                'name' => $msUser['displayName'],
                'email' => $msUser['mail'],
                'role' => $role,
                'created_at' => $date,
                'updated_at' => $date,
            ];
        });

        User::insert($users);

        return $this->handleResponse([], 'New users added successfully!', 201);
    }

//    /**
//     * Display the specified resource.
//     *
//     * @param  User  $user
//     * @return Response
//     */
//    public function show(User $user)
//    {
//        //
//    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return JsonResponse
     */
    public function destroy(User $user): JsonResponse
    {
        $user->delete();

        return $this->handleResponse(new UserResource($user), 'User removed successfully!');
    }
}
