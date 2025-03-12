<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MetricsResource extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'risparmio_complessivo'=>$this->risparmio_giornaliero_da_spreco+$this->risparmio_giornaliero_da_eccesso,
            'efficienza'=>(($this->risparmio_giornaliero_da_spreco+$this->risparmio_giornaliero_da_eccesso)/($this->consumo_giornaliero))*100,
            'cicli_di_ricarica_risparmiati'=>($this->risparmio_giornaliero_da_spreco+$this->risparmio_giornaliero_da_eccesso)*0.01,
            'litri_di_acqua_risparmiati'=>($this->risparmio_giornaliero_da_spreco+$this->risparmio_giornaliero_da_eccesso)*0.0005,
            'jackpot_guadagnato'=>($this->risparmio_giornaliero_da_spreco+$this->risparmio_giornaliero_da_eccesso)*0.25,
            'spreco_complessivo'=>$this->spreco_giornaliero+$this->eccsso_giornaliero,
            'inefficienza'=>100-((($this->risparmio_giornaliero_da_spreco+$this->risparmio_giornaliero_da_eccesso)/($this->consumo_giornaliero))*100),
            'cicli_di_ricarica_sprecati'=>($this->spreco_giornaliero+$this->eccesso_giornaliero)*0.01,
            'litri_di_acqua_sprecati'=>($this->spreco_giornaliero+$this->eccesso_giornaliero)*0.0005,
            'jackpot_spreacto'=>($this->spreco_giornaliero+$this->eccesso_giornaliero)*0.25,
            'consumo_progressvo_giornaliero'=>$this->consumo_progressivo_giornaliero,
        ];
    }
}
