<?php

namespace App\Http\Controllers;

use App\Events\SeriesCreated as EventsSeriesCreated;
use App\Http\Requests\SeriesFormRequest;
use App\Jobs\DeleteSeriesCover;
use App\Models\Series;
use App\Repositories\SeriesRepository;
use Illuminate\Http\Request;


class SeriesController extends Controller {

    public function __construct(private SeriesRepository $repository) {
        $this->middleware('autenticador')->except('index');
    }

    public function index(Request $request){

        $series = Series::all();
        $mensagemSucesso = session('mensagem.sucesso');

        return view('series.index')->with('series',$series)
            ->with('mensagemSucesso',$mensagemSucesso);
    }

    public function create() {
        return view('series.create');
    }

    public function store(SeriesFormRequest $request)
    {
        //Verifica se existe uma imagem inserida no input, se o retorno for positivo ele cria a série com a capa selecionada
        //se for negativo ele utiliza o arquivo padrão "series_cover.png" que fica armazenado em storage/app/public/series_cover.

        //dd($request);

        if($request->hasFile('cover')){
            $coverPath = $request->file('cover')->store('series_cover', 'public');
            $request->coverPath = $coverPath;
        }else{
            $request->coverPath = 'series_cover/default/no-cover.jpg';
        }

        $serie = $this->repository->add($request);

        EventsSeriesCreated::dispatch(
          $serie->nome,
          $serie->id,
          $request->seasonsQty,
          $request->episodesPerSeason,
          $request->coverPath,
        );

        return to_route('series.index')
            ->with('mensagem.sucesso', "Série '{$serie->nome}' adicionada com sucesso");
    }

    public function destroy(Series $series) {

        if ($series->cover) {
            // Se a série tiver uma capa, despache o trabalho para excluí-la
            DeleteSeriesCover::dispatch($series->cover);
        }

        // Exclua a série
        $series->delete();
        return to_route('series.index')
            ->with('mensagem.sucesso',"Série \"{$series->nome}\" removida com sucesso");
    }

    public function edit(Series $series) {

        return view('series.edit')->with('serie', $series);

    }

    public function update(Series $series, SeriesFormRequest $request) {

        $series->fill($request->all());
        $series->save();

        return to_route('series.index')
        ->with('mensagem.sucesso',"Série \"{$series->nome}\" alterada com sucesso");
    }
}

