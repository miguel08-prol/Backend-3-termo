<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Http;

class FilmeController extends Controller
{
    private $apiKey = 'ca2223bfd7647b65c24cdc54bd2e8e1f';
    private $baseUrl = "https://api.themoviedb.org/3";

    public function index(Request $request)
    {
        $query = $request->input('search');

        if ($query) {
            // Se houver busca, filtra pelo nome
            $movies = Http::get("{$this->baseUrl}/search/movie", [
                'api_key' => $this->apiKey,
                'query' => $query,
                'language' => 'pt-BR'
            ])->json()['results'] ?? [];
            $tituloPagina = "Resultados para: " . $query;
        } else {
            // Se não, mostra os mais populares
            $movies = Http::get("{$this->baseUrl}/movie/popular", [
                'api_key' => $this->apiKey,
                'language' => 'pt-BR'
            ])->json()['results'] ?? [];
            $tituloPagina = "Filmes em Destaque";
        }

        return view('filmes', compact('movies', 'tituloPagina'));
    }
}