<?php

namespace App\Http\Controllers\Web\Ngo;

use App\Helpers\AuthHelper as A;
use App\Http\Controllers\Controller;
use App\Http\Requests\Ngo\SaveNgoRequest;
use App\Repositories\Ngo\NgoRepository;
use App\Services\Ngo\NgoService;

class NgoController extends Controller
{
    public function __construct(
        private NgoService $service,
        private NgoRepository $repo,
    ) {}

    public function index()
    {
        A::require('list users.ngo');

        $ngos = $this->repo->getPaginated();

        return view('ngo.index', compact('ngos'));
    }

    public function create()
    {
        A::require('create users.ngo');

        return view('ngo.edit', ['ngo' => null]);
    }

    public function store(SaveNgoRequest $request)
    {
        A::require('create users.ngo');

        $result = $this->service->save($request);

        if (! $result['status']) {
            return $this->errorRedirect($result['message']);
        }

        return $this->successRedirect('ngos.index', $result['message']);
    }

    public function edit(Ngo $ngo)
    {
        A::require('update users.ngo');

        return view('ngo.edit', compact('ngo'));
    }

    public function update(SaveNgoRequest $request, Ngo $ngo)
    {
        A::require('update users.ngo');

        $result = $this->service->save($request, $ngo);

        if (! $result['status']) {
            return $this->errorRedirect($result['message']);
        }

        return $this->successRedirect('ngos.index', $result['message']);
    }

    public function destroy(Ngo $ngo)
    {
        A::require('delete users.ngo');

        $result = $this->service->delete($ngo);

        if (! $result['status']) {
            return $this->errorRedirect($result['message']);
        }

        return $this->successRedirect('ngos.index', $result['message']);
    }
}
