<?php declare(strict_types=1);

namespace App\DTO;

use App\Http\Requests\UserAdmRequest;

class UserUpdateDTO
{
    public function __construct(
        public string $id,
        public string $name,
        public string $email,
        public string $adm,
        public string $status,
    ) {
    }

    public static function userDTO(UserAdmRequest $request): array
    {
        return (array) new self(
                $request->id,
                $request->name,
                $request->email,
                $request->adm,
                $request->status,
            );
    }
}