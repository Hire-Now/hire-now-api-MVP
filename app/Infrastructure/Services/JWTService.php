<?php

namespace App\Infrastructure\Services;

use Carbon\Carbon;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Str;
use App\Domain\Entities\User;
use Illuminate\Support\Facades\Storage;
use App\Domain\Contracts\JWTServiceInterface;
use App\Domain\Entities\JwtToken;
use App\Domain\Repositories\JwtTokenRepositoryInterface;
use Illuminate\Validation\UnauthorizedException;

class JWTService implements JWTServiceInterface
{
    private ?string $privateKey;
    private ?string $publicKey;

    public function __construct(private JwtTokenRepositoryInterface $jwtTokenRepository)
    {
        $this->privateKey = Storage::disk('local')->get('keys/' . config('app.user_auth.private_key_path'));
        $this->publicKey = Storage::disk('local')->get('keys/' . config('app.user_auth.public_key_path'));
    }


    //     CREATE TABLE jwt_tokens ( //TODO: CREAR TABLA
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

            $jwt = JWT::encode(
                $payload,
                openssl_pkey_get_private($this->privateKey, config('app.user_auth.private_key_passphrase')),
                'RS256'
            );

            $this->jwtTokenRepository->create(new JwtToken(null, $user->getId(), $jwtId, config('app.user_auth.jwt_validity_time'), config('app.user_auth.jwt_type_time')));

            return $jwt;
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

    public function validateToken(string $token): User
    {
        try {
            $decoded = JWT::decode($token, new Key($this->publicKey, 'RS256'));

            if ($decoded->iss !== config('app.url')) {
                throw new UnauthorizedException('Invalid token issuer.');
            }

            $jtiIsValid = $this->jwtTokenRepository->findByJtiAndUserId($decoded->jti, $decoded->sub, 'valid');

            if (!$jtiIsValid) {
                throw new UnauthorizedException('Invalid JWT jti, sub or JWT is invalid.');
            }

            return $jtiIsValid;
        } catch (\Throwable $th) {
            throw new \RuntimeException('JWT validation failed!', 0, $th);
        }
    }
}
