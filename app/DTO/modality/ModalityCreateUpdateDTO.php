<?php declare(strict_types=1);

namespace App\DTO\modality;

use App\Http\Requests\ModalityRequest;

class ModalityCreateUpdateDTO
{
    
    public function __construct(
        public string $description,
    ) {
        //
    }

    public static function make(ModalityRequest $modalityRequest): self
    {
        return new self(
            $modalityRequest->description,
        );
    }

    public function toArray(): array
    {
        return [
            'description' => $this->description,
        ];
    }
}