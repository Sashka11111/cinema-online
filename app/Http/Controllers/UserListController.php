<?php

namespace Liamtseva\Cinema\Http\Controllers;

use Liamtseva\Cinema\Http\Requests\StoreUserListRequest;
use Liamtseva\Cinema\Http\Requests\UpdateUserListRequest;
use Liamtseva\Cinema\Models\UserList;

class UserListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserListRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(UserList $userList)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserList $userList)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserListRequest $request, UserList $userList)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserList $userList)
    {
        //
    }
}
