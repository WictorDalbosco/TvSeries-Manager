<?php

namespace App\Repositories;
use App\Http\Requests\SeriesFormRequest;
use Illuminate\Support\Facades\DB;
use App\Models\Series;
use App\Models\Season;
use App\Models\Episode;

class EloquentSeriesRepository implements SeriesRepository {

    public function add(SeriesFormRequest $request): Series{
        return DB::transaction(function () use($request) {
            $series = Series::create([
                'nome' => $request->nome,
                'cover' => $request->coverPath,
            ]);

            $seasons = [];

            for ($i=1; $i <= $request->seasonsQty ; $i++) {
                $seasons[] = [
                    'series_id' => $series->id,
                    'number' => $i,
                ];
            }

            Season::insert($seasons);

            $episodes = [];

            foreach ($series->seasons as $seasons) {
                for ($j=1; $j <= $request->episodesPerSeason ; $j++) {
                    $episodes[] = [
                        'season_id' => $seasons->id,
                        'number' => $j,
                    ];
                }

            }

            Episode::insert($episodes);

            return $series;

        });
    }

}
