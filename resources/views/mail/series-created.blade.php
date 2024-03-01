<x-mail::message>

# {{ $nomeSerie }} criada

A série {{ $nomeSerie }} com {{ $qtdTemporadas }} temporadas e {{ $episodiosPorTemporada }} episódios por temporada foi criada.

Acesse aqui:

<x-mail::button :url="route('seasons.index', $idSerie)">
    Ver série
</x-mail::button>

</x-mail::message>
