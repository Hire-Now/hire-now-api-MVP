<?php

namespace App\Infrastructure\Services;

use App\Domain\Contracts\JWTServiceInterface;
use App\Domain\Entities\User;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class JWTService implements JWTServiceInterface
{
    private ?string $privateKey;
    private ?string $publicKey;

    public function __construct()
    {
        $this->privateKey = Storage::disk('local')->get('keys/' . config('app.user_auth.private_key_path'));
        $this->publicKey = Storage::disk('local')->get('keys/' . config('app.user_auth.public_key_path'));
    }


    //     CREATE TABLE jwt_tokens (
    //     id INT AUTO_INCREMENT PRIMARY KEY,
    //     user_id INT NOT NULL,
    //     jti VARCHAR(255) NOT NULL,
    //     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    //     FOREIGN KEY (user_id) REFERENCES users(id)
    // );

    public function generateToken(User $user): string
    {
        try {
            $issuedAt = Carbon::now()->timestamp;
            $expirationTime = Carbon::now()->add((int) config('app.user_auth.jwt_validity_time'), config('app.user_auth.jwt_type_time'))->timestamp;
            $jwtId = Str::uuid()->toString();

            $payload = [
                'sub'   => $user->getId(),
                'roles' => $this->userRoles($user->getRoles()),
                'iat'   => $issuedAt,
                'exp'   => $expirationTime,
                'aud'   => '',//frontent or backend
                'iss'   => config('app.url'),
                'jti'   => $jwtId,
            ];

            return JWT::encode(
                $payload,
                openssl_pkey_get_private($this->privateKey, config('app.user_auth.private_key_passphrase')),
                'RS256'
            );
        } catch (\Throwable $th) {
            throw new \RuntimeException('JWT could not be generated!', 0, $th);
        }
    }

    private function userRoles(array $roles): array
    {
        $rolesArray = [];

        foreach ($roles as $role) {
            $rolesArray[] = $role['name'];
        }

        return $rolesArray;
    }

    public function validateToken(string $token): ?User
    {
        return null;
    }
}
