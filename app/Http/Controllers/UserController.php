<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Resources\User as UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Psy\Util\Json;

class UserController extends Controller
{
    /**
     * @OA\Get(
     *     path="/users",
     *     tags={"Users"},
     *     summary="Fetch a list of all users.",
     *     @OA\Response(response=200, description="Users were found and returned."),
     *     @OA\Response(response=404, description="No users exist."),
     * )
     *
     * Display a listing of all users.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $users = User::all();

        if (true === $users->isEmpty())
        {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }

        return (new UserResource($users))->response();
    }

    /**
     * @OA\Post(
     *     path="/users",
     *     tags={"Users"},
     *     summary="Store a newly created user in storage.",
     *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/User")),
     *     @OA\Response(response=201, description="The user has been created successfully."),
     *     @OA\Response(response=400, description="Bad request. Indicates missing parameters."),
     *     @OA\Response(response=409, description="The user you are trying to create already exists."),
     * )
     *
     * Store a newly created user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        return new JsonResponse();
    }

    /**
     * @OA\Get(
     *     path="/users/{id}",
     *     tags={"Users"},
     *     summary="Fetch the specified user.",
     *     @OA\Parameter(name="id", in="path", required=true),
     *     @OA\Response(response="200", description="User found and returned."),
     *     @OA\Response(response="404", description="User not found."),
     * )
     *
     * Return the specified user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $user = User::all()->find($id);

        if (null === $user)
        {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($user->toJson());
    }

    /**
     * @OA\Put(
     *     path="/users/{id}",
     *     tags={"Users"},
     *     summary="Update the specified user in storage.",
     *     @OA\Parameter(name="id", in="path", required=true),
     *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/User")),
     *     @OA\Response(response=201, description="The user has been created successfully."),
     *     @OA\Response(response=400, description="Bad request. Indicates missing parameters."),
     *     @OA\Response(response=404, description="User not found."),
     * )
     *
     * Update the specified user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * @OA\Delete(
     *     path="/users/{id}",
     *     tags={"Users"},
     *     summary="Remove the specified user from storage.",
     *     @OA\Parameter(name="id", in="path", required=true),
     *     @OA\Response(response="204", description="User succesfully deleted."),
     *     @OA\Response(response="404", description="User not found."),
     * )
     * 204 - success
     *
     * Remove the specified user from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
