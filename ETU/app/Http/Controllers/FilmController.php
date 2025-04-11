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

    public function update(int $id, Request $request){
        try
        {
            $film = Film::findOrFail($id);
    
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'release_year' => 'required|integer|digits:4',
                'language_id' => 'required|integer',
                'length' => 'required|integer',
                'special_features' => 'nullable|string',
                'image' => 'nullable|string',
            ]);
    
            return (new FilmResource($this->filmRepository->update($id, $validated)))
            ->response()
            ->setStatusCode(OK);
        }

        catch(Exception $ex)
        {
            abort(SERVOR_ERROR, 'server error');
        }
    }

    public function delete(int $id)
    {
        try {
            if(!Film::findOrFail($id)){
                return response()->setStatusCode(404);
            }
            $this->filmRepository->delete($id);
            return response()->noContent();
        } catch (\Exception $ex) {
            abort(500, 'Server error');
        }
    }
}