<?php

namespace App\Infrastructure\Controllers;

use App\Domain\Enums\Roles;

use App\Infrastructure\Persistence\Eloquent\UserRepository;
use App\Infrastructure\Requests\CreateUserRequest;
use App\Infrastructure\Services\BcryptPasswordHasher;

use App\Application\Commands\Email\CreateEmailVerificationCommand;
use App\Application\Commands\Email\SendVerificationEmailCommand;
use App\Application\Commands\Email\VerifyEmailCommand;
use App\Application\Commands\User\CreateUserCommand;
use App\Application\Commands\User\FetchUserInformationCommand;
use App\Application\Commands\User\UpdateUserInformationCommand;
use App\Application\Handlers\User\CreateUserCommandHandler;
use App\Application\Handlers\Email\VerifyEmailCommandHandler;
use App\Application\Handlers\User\FetchUserCommandHandler;
use App\Application\Handlers\User\UpdateUserCommandHandler;
use App\Domain\Enums\ElementStatus;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Carbon\Carbon;
use Illuminate\Support\Facades\Request;

class UserController
{

    public function __construct(private CreateUserCommandHandler $createUserHandler, private VerifyEmailCommandHandler $verifyEmailHandler, private FetchUserCommandHandler $fetchUserCommandHandler, private UpdateUserCommandHandler $updateUserCommandHandler)
    {
    }

    public function store(CreateUserRequest $request)
    {
        try {
            $request = $request->validated();

            //todo: determinar de que manera identificar el rol -  Setear roles en tabla intermediara!!!!
            $command = new CreateUserCommand(
                $request['name'],
                $request['email'],
                $request['password'],
                Carbon::createFromFormat('Y/m/d', $request['birth_date']),
                Roles::CANDIDATE
            );

            $user = $this->createUserHandler->handle($command);

            $createEmailCommand = new CreateEmailVerificationCommand($user->getId(), $user->getEmail());

            $emailVerifyLink = $this->verifyEmailHandler->handleLinkGeneration($createEmailCommand);

            $sendEmailCommand = new SendVerificationEmailCommand($emailVerifyLink);

            $emailVerifyLink = $this->verifyEmailHandler->sendVerificationEmail($sendEmailCommand);

            return response()->json([
                'status'  => 'SUCCESS',
                'message' => 'User created successfully!',
                'data'    => [
                    'user'                  => $user->toArray(),
                    'email_verif_link_sent' => !empty($emailVerifyLink->getId()) ? true : false
                ]
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'An unexpected error just happened!',
                'data'    => []
            ], 500);
        }
    }

    public function verifyEmail(Request $request, string $id, string $hash)
    {
        try {
            $command = new FetchUserInformationCommand($id, null);
            $user = $this->fetchUserCommandHandler->handle($command);

            $verifyEmailCommand = new VerifyEmailCommand($user->getId(), $user->getEmail(), $hash);
            $this->verifyEmailHandler->handleHashVerification($verifyEmailCommand);

            if ($user->getStatus() !== ElementStatus::ACTIVE) {
                $command = new UpdateUserInformationCommand(
                    $user->getId(),
                    $user->getName(),
                    $user->getEmail(),
                    $user->getBirthDate(),
                    null,
                    ElementStatus::ACTIVE,
                    Carbon::now(),
                    $user->getCreatedAt()
                );

                $user = $this->updateUserCommandHandler->handle($command);

                return response()->json([
                    'status'  => 'SUCCESS',
                    'message' => 'Email verified successfully!',
                    'data'    => [
                        'user' => $user->toArray(),
                    ]
                ], 200);
            }

            return response()->json([
                'status'  => 'SUCCESS',
                'message' => 'Email already verified!',
                'data'    => [
                    'user' => $user->toArray(),
                ]
            ], 200);
        } catch (BadRequestException $th) {
            return response()->json([
                'message' => $th->getMessage(),
                'data'    => []
            ], status: 400);
        } catch (\Throwable $th) {
            dd($th);
            return response()->json([
                'message' => 'An unexpected error just happened!',
                'data'    => []
            ], 500);
        }
    }
}
