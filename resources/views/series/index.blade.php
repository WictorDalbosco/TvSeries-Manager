<x-layout title="Séries" :mensagem-sucesso="$mensagemSucesso">
    @auth
        <a href="{{ route('series.create') }}" class="btn btn-dark mb-2">Adicionar série</a>
    @endauth

    <ul class="list-group">
        @foreach ($series as $serie)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <div class="d-flex flex-column align-items-center">
                    <div>
                        <img src="{{ asset('storage/' . $serie->cover) }}"
                            width="100"
                            class="img-thumbnail"
                            alt="Thumb da Série">
                    </div>

                    <div>
                        @auth<a href="{{ route('seasons.index', $serie->id) }}">@endauth{{ $serie->nome }}@auth</a>@endauth
                    </div>

                </div>

                @auth
                <span class="d-flex">
                    <a href="{{ route('series.edit', $serie->id) }}" class="btn btn-primary btn-sm" method="POST">
                        Edit
                    </a>

                    <form action="{{ route('series.destroy', $serie->id) }}" method="POST" class="ms-2">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm ">X</button>
                    </form>
                </span>
                @endauth

            </li>
        @endforeach
    </ul>


</x-layout>
