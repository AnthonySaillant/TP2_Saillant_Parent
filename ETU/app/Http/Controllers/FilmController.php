<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Film;
use App\Http\Resources\FilmResource;
use App\Repository\FilmRepositoryInterface;


class FilmController extends Controller
{   
    private FilmRepositoryInterface $filmRepository;

    public function __construct(FilmRepositoryInterface $filmRepository)
    {
       $this->filmRepository = $filmRepository;
    }

    public function create(Request $request)
    {
        try
        {
            return (new FilmResource($this->filmRepository->create($request->all())))
            ->response()
            ->setStatusCode(CREATED);
        }
        
        catch(Exception $ex)
        {
            abort(SERVER_ERROR, 'Server error');
        }       
    }

}