<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\User;
use App\Http\Resources\UserCollection;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

/**
 * Class UserController
 * @package App\Http\Controllers
 */
class UserController extends Controller
{
    use ValidatesRequests;

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
     * @return \Illuminate\Http\JsonResponse|UserCollection
     */
    public function index()
    {
        $users = User::all();

        if (true === $users->isEmpty())
        {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }

        return new UserCollection($users);
    }

    /**
     * @OA\Post(
     *     path="/users",
     *     tags={"Users"},
     *     summary="Store a newly created user in storage.",
     *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/User")),
     *     @OA\Response(response=201, description="The user has been created successfully."),
     *     @OA\Response(response=400, description="Bad request. Indicates invalid properties int he request body."),
     *     @OA\Response(response=409, description="The user with the same email already exists."),
     * )
     *
     * Store a newly created user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse|UserResource
     */
    public function store(Request $request)
    {
        try
        {
            $request->validate([
                'firstname' => 'string|required',
                'lastname' => 'string|required',
                'email' => 'email|required',
            ]);
        }
        catch (ValidationException $e)
        {
            return new JsonResponse(null, Response::HTTP_BAD_REQUEST);
        }

        try
        {
            $request->validate(['email' => 'unique:users,email']);
        }
        catch (ValidationException $e)
        {
            return new JsonResponse(null, Response::HTTP_CONFLICT);
        }

        $user = new User($request->json()->all());

        $user->save();

        return new UserResource($user);
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
     * @return \Illuminate\Http\JsonResponse|UserResource
     */
    public function show($id)
    {
        $user = User::find($id);

        if (null === $user)
        {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }

        return new UserResource($user);
    }

    /**
     * @OA\Patch(
     *     path="/users/{id}",
     *     tags={"Users"},
     *     summary="Update the specified user in storage.",
     *     @OA\Parameter(name="id", in="path", required=true),
     *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/User")),
     *     @OA\Response(response=200, description="The user has been updated successfully."),
     *     @OA\Response(response=400, description="Bad request. Indicates invalid properties in the request body."),
     *     @OA\Response(response=404, description="User not found."),
     *     @OA\Response(response=409, description="The email entered is already in use."),
     * )
     *
     * Update the specified user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse|UserResource
     */
    public function update(Request $request, $id)
    {
        try
        {
            $request->validate([
                'firstname' => 'string',
                'lastname' => 'string',
                'email' => 'email',
            ]);
        }
        catch (ValidationException $e)
        {
            return new JsonResponse(null, Response::HTTP_BAD_REQUEST);
        }

        try
        {
            $request->validate(['email' => 'unique:users,email']);
        }
        catch (ValidationException $e)
        {
            return new JsonResponse(null, Response::HTTP_CONFLICT);
        }

        /** @var User $user */
        $user = User::find($id);

        if (null === $user)
        {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }

        foreach ($request->json()->all() as $attribute => $value)
        {
            $user->setAttribute($attribute, $value);
        }

        $user->save();

        return new UserResource($user);
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        /** @var User $user */
        $user = User::find($id);

        if (null === $user)
        {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }

        $user->delete();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
