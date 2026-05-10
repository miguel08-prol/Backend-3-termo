<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class ImageHelper
{
    /**
     * Serve uma imagem diretamente do storage sem precisar de link simbólico
     */
    public static function serveImage($path)
    {
        $fullPath = storage_path('app/public/' . $path);
        
        if (!file_exists($fullPath)) {
            // Retornar imagem padrão
            $defaultPath = public_path('images/default-pokemon.png');
            if (file_exists($defaultPath)) {
                return Response::file($defaultPath);
            }
            // Se não tiver imagem padrão, retornar 404
            abort(404);
        }
        
        // Detectar o tipo MIME
        $extension = pathinfo($fullPath, PATHINFO_EXTENSION);
        $mimeTypes = [
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
        ];
        
        $mimeType = $mimeTypes[strtolower($extension)] ?? 'application/octet-stream';
        
        // Retornar a imagem com headers corretos
        return Response::make(file_get_contents($fullPath), 200, [
            'Content-Type' => $mimeType,
            'Cache-Control' => 'public, max-age=86400',
            'Access-Control-Allow-Origin' => '*'
        ]);
    }
    
    /**
     * Salva uma imagem no storage
     */
    public static function saveImage($imageContent, $directory = 'pokemons')
    {
        // Garantir que o diretório existe
        $fullStoragePath = storage_path('app/public/' . $directory);
        if (!file_exists($fullStoragePath)) {
            mkdir($fullStoragePath, 0777, true);
        }
        
        // Gerar nome único
        $filename = $directory . '/' . time() . '_' . uniqid() . '.png';
        $fullPath = storage_path('app/public/' . $filename);
        
        // Salvar o arquivo
        file_put_contents($fullPath, $imageContent);
        
        return $filename;
    }
    
    /**
     * Salva uma imagem Base64
     */
    public static function saveBase64Image($base64Image, $directory = 'pokemons')
    {
        $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $base64Image);
        $imageData = str_replace(' ', '+', $imageData);
        $imageBinary = base64_decode($imageData);
        
        if ($imageBinary !== false) {
            $fullStoragePath = storage_path('app/public/' . $directory);
            if (!file_exists($fullStoragePath)) {
                mkdir($fullStoragePath, 0777, true);
            }
            
            $filename = $directory . '/' . time() . '_' . uniqid() . '.png';
            $fullPath = storage_path('app/public/' . $filename);
            
            file_put_contents($fullPath, $imageBinary);
            
            return $filename;
        }
        
        return null;
    }
}