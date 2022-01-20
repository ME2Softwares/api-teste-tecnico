<?php

namespace App\Http\Controllers;

use App\Http\Responses\ApiResponse;
use App\Http\Validators\QuoteRequest;
use App\Services\QuoteService;

class QuoteController extends Controller
{
    protected QuoteService $quoteService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(QuoteService $quoteService)
    {
        $this->quoteService = $quoteService;
    }

    public function index()
    {
        return ApiResponse::success($this->quoteService->getAll());
    }

    public function show(int $id)
    {
        return ApiResponse::success($this->quoteService->get($id));
    }

    public function store(QuoteRequest $request)
    {
        return ApiResponse::success(
            $this->quoteService->create($request->validateStore()),
            201
        );
    }

    public function update(int $id, QuoteRequest $request)
    {
        return ApiResponse::success(
            $this->quoteService->update($id, $request->validateStore())
        );
    }

    public function destroy(int $id)
    {
        return ApiResponse::success(
            $this->quoteService->delete($id)
        );
    }
}
