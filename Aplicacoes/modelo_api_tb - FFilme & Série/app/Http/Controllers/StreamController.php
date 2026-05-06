<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class StreamController extends Controller
{
    private $apiKey = 'ca2223bfd7647b65c24cdc54bd2e8e1f';
    private $baseUrl = "https://api.themoviedb.org/3";

    public function index(Request $request)
    {
        $search = $request->input('search');

        try {
            $params = [
                'api_key'  => $this->apiKey,
                'language' => 'pt-BR',
                'page'     => 1
            ];

            // SE EXISTIR BUSCA
            if ($search) {
                $searchResponse = Http::get("{$this->baseUrl}/search/multi", array_merge($params, ['query' => $search]));
                $resultados = $searchResponse->json()['results'] ?? [];
                
                // Retorna uma view simplificada de busca ou a mesma welcome com os resultados
                return view('welcome', [
                    'filmes' => $resultados,
                    'isSearch' => true,
                    'searchTerm' => $search,
                    'destaque' => null // Sem banner no modo busca para focar nos resultados
                ]);
            }

            // SE NÃO EXISTIR BUSCA (HOME NORMAL COM 6 SEÇÕES)
            
            // 1. Populares (Banner e Seção 1)
            $filmes = Http::get("{$this->baseUrl}/movie/popular", $params)->json()['results'] ?? [];
            
            // 2. Séries Populares
            $series = Http::get("{$this->baseUrl}/tv/popular", $params)->json()['results'] ?? [];

            // 3. Ação (Gênero 28)
            $acao = Http::get("{$this->baseUrl}/discover/movie", array_merge($params, ['with_genres' => 28]))->json()['results'] ?? [];

            // 4. Terror (Gênero 27)
            $terror = Http::get("{$this->baseUrl}/discover/movie", array_merge($params, ['with_genres' => 27]))->json()['results'] ?? [];

            // 5. Ficção Científica (Gênero 878)
            $scifi = Http::get("{$this->baseUrl}/discover/movie", array_merge($params, ['with_genres' => 878]))->json()['results'] ?? [];

            // 6. Mais Votados
            $topRated = Http::get("{$this->baseUrl}/movie/top_rated", $params)->json()['results'] ?? [];

            $destaque = !empty($filmes) ? $filmes[0] : null;

            return view('welcome', compact('filmes', 'series', 'acao', 'terror', 'scifi', 'topRated', 'destaque'))->with('isSearch', false);

        } catch (\Exception $e) {
            Log::error("Erro TMDB: " . $e->getMessage());
            return "Erro ao carregar dados.";
        }
    }

    public function seriesIndex(Request $request)
    {
        $search = $request->input('search');

        try {
            $params = [
                'api_key'  => $this->apiKey,
                'language' => 'pt-BR',
                'page'     => 1
            ];

            if ($search) {
                // Busca de Séries
                $response = Http::get("{$this->baseUrl}/search/tv", array_merge($params, ['query' => $search]));
                $series = $response->json()['results'] ?? [];
                $tituloPagina = "Resultados para: " . $search;
            } else {
                // Séries Populares (Padrão)
                $response = Http::get("{$this->baseUrl}/tv/popular", $params);
                $series = $response->json()['results'] ?? [];
                $tituloPagina = "Séries em Alta";
            }

            // Certifique-se de que o arquivo se chama resources/views/series.blade.php
            return view('series', [
                'series' => $series,
                'tituloPagina' => $tituloPagina
            ]);

        } catch (\Exception $e) {
            Log::error("Erro TMDB Séries: " . $e->getMessage());
            return "Erro ao carregar séries.";
        }
    }
}