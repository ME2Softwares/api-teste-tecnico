<?php

namespace App\Services;

use App\Models\Quote;
use Illuminate\Pagination\LengthAwarePaginator;

class QuoteService
{

    public function getAll(): LengthAwarePaginator
    {
        return Quote::orderByDesc('id')->paginate();
    }

    public function get(int $id): Quote
    {
        return Quote::findOrFail($id);
    }

    public function create(array $request): Quote
    {
        return Quote::create($request);
    }

    public function update(int $id, array $request): Quote
    {
        $quote = $this->get($id);
        $quote->update($request);

        return $quote;
    }

    public function delete(int $id): bool
    {
        return $this->get($id)->delete();
    }
}
