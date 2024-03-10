<?php declare(strict_types=1);

namespace App\Repositories;

use App\Models\Modality;
use Illuminate\Database\Eloquent\Collection;

class ModalityRepository
{
    public function __construct(
        protected Modality $modality,
    ) {
    }

    public function allModalities(): Collection|null
    {
        $modalities = $this->modality->all();
        
        return $modalities->isNotEmpty()
                ? $modalities
                : null;
    }
}