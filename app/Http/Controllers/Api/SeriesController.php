<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SeriesFormRequest;
use App\Jobs\DeleteSeriesCover;
use App\Models\Series;
use App\Repositories\SeriesRepository;
use Illuminate\Http\Request;

class SeriesController extends Controller
{

    public function __construct(private SeriesRepository $seriesRepository) {

    }
    public function index(Request $request) {
        $query = Series::query();
        if($request->has('nome')){
            $query->where('nome',$request->nome);
        }
        return $query->paginate(5);
    }

    public function store(SeriesFormRequest $request)
    {
      $coverPath = $request->input('cover');
      $coverPath = str_replace("\\", "/", $coverPath);

      return response()->json($this->seriesRepository->add($request), 201);
    }


    //Aqui eh pra fazer com eager loading e mostrar tanto a serie como seus episodios por temporada
    /*public function show(int $series) {
        $series = Series::whereId($series)
            ->with('seasons.episodes')
            ->first();

        return $series;
    }*/

    public function update(Series $series, SeriesFormRequest $request) {
        $series->fill($request->all());
        $series->save();

        return $series;
    }

    public function destroy(Series $series) {
        if ($series->cover) {
            // Se a série tiver uma capa, despache o trabalho para excluí-la
            DeleteSeriesCover::dispatch($series->cover);
        }

        // Exclua a série
        $series->delete();
        return response()->noContent();
    }

    public function show(int $series) {
        $seriesModel = Series::with('seasons.episodes')->find($series);
        if ($seriesModel === null){
            return response()->json(['message' => 'Series not found'], 404);
        }

        return $seriesModel;
    }

    public function upload(SeriesFormRequest $request)
    {

        $coverPath = null;

        if ($request->hasFile('cover')) {

            $coverPath = $request->file('cover')->store('series_cover', 'public');
        } else {
            return response()->json(['error' => 'Nenhum arquivo foi enviado.'], 400);
        }

        $request->merge(['coverPath' => $coverPath]);

        return response()->json(['file_path' => $coverPath]);
    }

}
