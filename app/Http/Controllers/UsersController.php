<?php

namespace App\Http\Controllers;

// import model user
use App\Models\User;

// import resource
use App\Http\Resources\UserResource;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;

class UsersController extends BaseController
{
    public function index()
    {
        // get all users
        $users = User::all();

        // return response
        return $this->sendResponse('All users retrieved successfully.', UserResource::collection($users));
    }

    public function show($id)
    {
        // find user by id
        $user = User::find($id);

        // check if user not found
        if (!$user) {
            return $this->sendError('User not found.', 404);
        }

        // return response
        return $this->sendResponse('User retrieved successfully.', new UserResource($user));
    }

    public function store(Request $request)
    {
        // define validation rules
        $validator = Validator::make($request->all(), [
            'name'      => 'required|max:50',
            'role'      => 'in:admin,user',
            'email'     => 'required|email|unique:users,email|max:50',
            'password'  => 'required|min:5|max:20',
        ]);

        // check if validation error
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', 422, $validator->errors());
        }

        try {
            // insert
            $user = User::create([
                'name'      => $request->name,
                'role'      => $request->role,
                'email'     => $request->email,
                'password'  => $request->password,
            ]);
            return $this->sendResponse('User added successfully.', new UserResource($user));
        } catch (QueryException $e) {
            return $this->sendError('Error.', 400);
        }
    }

    public function update(Request $request, $id)
    {        
        // find user by id
        $user = User::find($id);

        // check if user not found
        if (!$user) {
            return $this->sendError('User not found.', 404);
        }

        // define validation rules
        $validator = Validator::make($request->all(), [
            'name'      => 'max:50',
            'role'      => 'in:admin,user',
            'email'     => 'email|unique:users,email|max:50',
            'password'  => 'min:5|max:20',
        ]);

        // check if validation error
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', 422, $validator->errors());
        }

        try {
            // update
            $data = [];
            $request->has('name') ? $data['name'] = $request->name : '';
            $request->has('role') ? $data['role'] = $request->role : '';
            $request->has('email') ? $data['email'] = $request->email : '';
            $request->has('password') ? $data['password'] = $request->password : '';
            $user->update($data);
            return $this->sendResponse('User updated successfully.', new UserResource($user));
        } catch (QueryException $e) {
            return $this->sendError('Error.', 400);
        }
    }

    public function destroy($id)
    {
        // find user by id
        $user = User::find($id);

        // check if user not found
        if (!$user) {
            return $this->sendError('User not found.', 404);
        }

        try {
            // soft delete
            $user->delete();
            return $this->sendResponse('User deleted successfully.', new UserResource($user));
        } catch (QueryException $e) {
            return $this->sendError('Error.', 400);
        }
    }
}
