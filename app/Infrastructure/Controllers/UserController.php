<?php

namespace App\Infrastructure\Controllers;

use App\Application\Commands\Email\CreateEmailVerificationCommand;
use App\Application\Commands\Email\SendVerificationEmailCommand;
use App\Application\Commands\User\CreateUserCommand;
use App\Application\Commands\User\VerifyEmailCommand;
use App\Application\Handlers\User\CreateUserCommandHandler;
use App\Application\Handlers\User\VerifyEmailCommandHandler;
use App\Application\UseCases\EmailUseCase;
use App\Application\UseCases\UserUseCase;
use App\Domain\Enums\Roles;
use App\Infrastructure\Mail\EmailSender;
use App\Infrastructure\Persistence\Eloquent\EmailVerificationRepository;
use App\Infrastructure\Persistence\Eloquent\UserRepository;
use App\Infrastructure\Requests\CreateUserRequest;
use App\Infrastructure\Services\BcryptPasswordHasher;
use App\Infrastructure\Services\TokenGenerator;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Request;

class UserController
{

    public function __construct(private CreateUserCommandHandler $createUserHandler, private VerifyEmailCommandHandler $verifyEmailHandler)
    {}

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

            $sendEmailCommand  = new SendVerificationEmailCommand($emailVerifyLink);

            $emailVerifyLink = $this->verifyEmailHandler->sendVerificationEmail($sendEmailCommand);

            return response()->json([
                'status'  => 'SUCCESS',
                'message' => 'User created successfully!',
                'data'    => [
                    'user' => $user->toArray(),
                    'email_verif_link_sent' => !empty($emailVerifyLink->getId()) ? true : false
                ]
            ], 200);
        } catch (\Throwable $th) {
            dd($th);
            return response()->json([
                'message' => 'An unexpected error just happened!',
                'data'    => []
            ], 500);
        }
    }

    public function verifyEmail(Request $request, $hash)
    {
        try {
            $command = new VerifyEmailCommand($hash);
            $handler = new VerifyEmailCommandHandler(new UserUseCase(new UserRepository, new BcryptPasswordHasher()));
        } catch (BadRequestException $th) {
            return response()->json([
                'message' => $th->getMessage(),
                'data'    => []
            ], status: 400);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'An unexpected error just happened!',
                'data'    => []
            ], 500);
        }
    }
}
