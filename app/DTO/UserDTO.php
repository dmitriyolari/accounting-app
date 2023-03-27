<?php

declare(strict_types=1);

namespace App\DTO;

class UserDTO
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password
    ) {
    }

    public static function checkTypes($userCredentials): array
    {
        $checkedTypes = self::fromArrayToObject($userCredentials);
        return self::fromObjectToArray($checkedTypes);
    }

    public static function fromArrayToObject(array $userCredentials): UserDTO
    {
        return new self(
            $userCredentials['name'],
            $userCredentials['email'],
            $userCredentials['password'],
        );
    }

    public static function fromObjectToArray(UserDTO $userDTO): array
    {
        return [
            'name' => $userDTO->name,
            'email' => $userDTO->email,
            'password' => $userDTO->password
        ];
    }
}
