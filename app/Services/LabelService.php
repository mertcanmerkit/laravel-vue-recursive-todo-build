<?php

namespace App\Services;

use App\Repositories\LabelRepository;
use Illuminate\Support\Facades\Auth;

class LabelService
{
    public function __construct(protected LabelRepository $repo) {}

    public function getUserLabels()
    {
        return $this->repo->getByUser(Auth::id());
    }

    public function create(array $data)
    {
        return $this->repo->create(Auth::id(), $data);
    }

    public function findUserLabel($id)
    {
        return $this->repo->findOrFailOwned($id, Auth::id());
    }

    public function update($id, array $data)
    {
        $label = $this->repo->findOrFailOwned($id, Auth::id());
        return $this->repo->update($label, $data);
    }

    public function delete($id)
    {
        $label = $this->repo->findOrFailOwned($id, Auth::id());
        return $this->repo->delete($label);
    }
}
