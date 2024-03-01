<?php

namespace App\Http\Controllers;

use App\Models\Episode;
use App\Models\Season;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EpisodesController{
    public function index(Season $season) {
        return view('episodes.index', [
            'episodes' => $season->episodes,
            'mensagemSucesso' => session('mensagem.sucesso'),
        ]);
    }

    public function update(Request $request, Season $season) {

        $watchedEpisodes = implode(', ', $request->episodes ?? []);

        if (empty($watchedEpisodes)) {
            DB::transaction(function () use ($season) {
                $season->episodes()->update(['watched' => 0]);
            });
            return redirect()->route('episodes.index', $season->id);
        }

        DB::transaction(function () use ($watchedEpisodes, $season) {
            $season->episodes()->update(['watched' => DB::raw("case when id in ($watchedEpisodes) then 1 else 0 end")]);
        });

        return to_route('episodes.index', $season->id)
            ->with('mensagem.sucesso', 'Episódios marcados como assistidos!');
    }
}
