<?php

namespace App\Services;

use App\Models\SettingAplikasi;
use Illuminate\Support\Facades\Http;

class BaseApiService
{
    protected $settings;
    protected $useDatabaseGabungan;
    protected $header;
    protected $baseUrl;
    protected $kodeKecamatan;
    public function __construct()
    {
        $this->settings = SettingAplikasi::whereIn('key', ['api_server_database_gabungan', 'api_key_database_gabungan', 'sinkronisasi_database_gabungan'])->pluck('value', 'key');        
        $this->useDatabaseGabungan = $this->useDatabaseGabungan();
        $this->header = [
            'Accept' => 'application/ld+json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->settings['api_key_database_gabungan'],
        ];
        $this->baseUrl = $this->settings['api_server_database_gabungan'];
        $this->kodeKecamatan = str_replace('.','',config('profil.kecamatan_id'));        
    }

    /**
     * General API Call Method
     */
    protected function apiRequest(string $endpoint, array $params = [])
    {        
        // Buat permintaan API dengan Header dan Parameter
        $response = Http::withHeaders($this->header)->get($this->baseUrl . $endpoint, $params);

        // Return JSON hasil
        return $response->json('data') ?? [];
    }    

    protected function useDatabaseGabungan()
    {
        return ($this->settings['sinkronisasi_database_gabungan'] ?? null) === '1';
    }
}
