<?php declare(strict_types=1);

namespace App\DTO;

use App\Http\Requests\UserAdmRequest;

class UserUpdateDTO
{
    public function __construct(
        public int    $id,
        public string $name,
        public string $email,
        public string $adm,
        public string $status,
    ) {
        //
    }

    public static function make(UserAdmRequest $request): self
    {
        return new self(
                $request->id,
                $request->name,
                $request->email,
                $request->adm,
                $request->status,
            );
    }

    public function toArray(): array
    {
        return [
            'id'     => $this->id,
            'name'   => $this->name,
            'email'  => $this->email,
            'adm'    => $this->adm,
            'status' => $this->status,
        ];
    }
}