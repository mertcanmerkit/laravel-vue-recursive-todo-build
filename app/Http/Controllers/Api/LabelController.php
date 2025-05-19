<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLabelRequest;
use App\Http\Requests\UpdateLabelRequest;
use App\Http\Resources\LabelResource;
use App\Services\LabelService;
use Illuminate\Http\JsonResponse;

class LabelController extends Controller
{
    public function __construct(protected LabelService $service) {}

    public function index(): JsonResponse
    {
        $labels = $this->service->getUserLabels();
        return response()->json(LabelResource::collection($labels));
    }

    public function store(StoreLabelRequest $request): JsonResponse
    {
        $label = $this->service->create($request->validated());
        return response()->json(new LabelResource($label), 201);
    }

    public function show($id): JsonResponse
    {
        $label = $this->service->findUserLabel($id);
        return response()->json(new LabelResource($label));
    }

    public function update(UpdateLabelRequest $request, $id): JsonResponse
    {
        $label = $this->service->update($id, $request->validated());
        return response()->json(new LabelResource($label));
    }

    public function destroy($id): JsonResponse
    {
        $this->service->delete($id);
        return response()->json(null, 204);
    }
}
