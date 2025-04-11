<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Critic;
use App\Http\Resources\CriticResource;
use App\Repository\CriticRepositoryInterface;


class CriticController extends Controller
{   
    private CriticRepositoryInterface $criticRepository;

    public function __construct(CriticRepositoryInterface $criticRepository)
    {
       $this->criticRepository = $criticRepository;
    }

    public function create(Request $request)
    {
        try
        {
            return (new CriticResource($this->criticRepository->create($request->all())))
            ->response()
            ->setStatusCode(CREATED);
        }

        catch(Exception $ex)
        {
            return response()->json([
                'error' => 'Server error',
                'message' => $ex->getMessage()
            ], 500);
        }
    }

}