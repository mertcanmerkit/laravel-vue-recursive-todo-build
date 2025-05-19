<?php

namespace App\Services;

use App\Repositories\TodoListRepository;
use Illuminate\Support\Facades\Auth;

class TodoListService
{
    public function __construct(protected TodoListRepository $repo)
    {
    }

    public function getUserLists()
    {
        return $this->repo->getByUser(Auth::id());
    }

    public function create(array $data)
    {
        return $this->repo->create(Auth::id(), $data);
    }

    public function findUserList($id)
    {
        return $this->repo->findOrFailOwned($id, Auth::id());
    }

    public function update($id, array $data)
    {
        $list = $this->repo->findOrFailOwned($id, Auth::id());
        return $this->repo->update($list, $data);
    }

    public function delete($id)
    {
        $list = $this->repo->findOrFailOwned($id, Auth::id());
        return $this->repo->delete($list);
    }
}
