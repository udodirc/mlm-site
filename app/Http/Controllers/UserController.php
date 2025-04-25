<?php
namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(
        protected UserService $userService
    ) {}

    public function index()
    {
        return response()->json($this->userService->getAll());
    }

    public function show($id)
    {
        return response()->json($this->userService->getById($id));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);
        $data['password'] = bcrypt($data['password']);

        return response()->json($this->userService->create($data));
    }

    public function update(Request $request, $id)
    {
        $data = $request->only(['name', 'email']);
        return response()->json(['updated' => $this->userService->update($id, $data)]);
    }

    public function destroy($id)
    {
        return response()->json(['deleted' => $this->userService->delete($id)]);
    }
}
