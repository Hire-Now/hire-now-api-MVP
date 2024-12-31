<?php

namespace App\Infrastructure\Controllers;

use Carbon\Carbon;
use App\Domain\Enums\Roles;

use App\Domain\Entities\User;

use Illuminate\Http\JsonResponse;
use App\Domain\Enums\ElementStatus;
use App\Domain\Entities\EmailVerification;

use App\Infrastructure\Requests\CreateUserRequest;
use App\Application\Commands\User\CreateUserCommand;
use App\Application\Commands\Email\VerifyEmailCommand;

use App\Application\Commands\User\SetRoleToUserCommand;
use App\Application\Handlers\User\FetchUserCommandHandler;
use App\Application\Handlers\User\CreateUserCommandHandler;
use App\Application\Handlers\User\UpdateUserCommandHandler;

use App\Application\Handlers\Email\VerifyEmailCommandHandler;
use App\Application\Commands\Role\FetchRoleInformationCommand;

use App\Application\Commands\User\FetchUserInformationCommand;
use App\Application\Commands\User\UpdateUserInformationCommand;
use App\Application\Commands\Email\SendVerificationEmailCommand;
use App\Application\Commands\Email\CreateEmailVerificationCommand;
use App\Application\Handlers\Role\FetchRoleInformationCommandHandler;
use App\Application\Handlers\User\SetRoleToUserCommandHandler;

class UserController
{

    public function __construct(
        private CreateUserCommandHandler $createUserHandler,
        private VerifyEmailCommandHandler $verifyEmailHandler,
        private FetchUserCommandHandler $fetchUserCommandHandler,
        private UpdateUserCommandHandler $updateUserCommandHandler,
        private FetchRoleInformationCommandHandler $fetchRoleInformationCommandHandler,
        private SetRoleToUserCommandHandler $setRoleToUserCommandHandler
    ) {
    }

    /**
     * store
     *
     * @param  CreateUserRequest $request
     * @return JsonResponse
     */
    public function store(CreateUserRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();

            $user = $this->createUserWithRole(
                $validatedData['name'],
                $validatedData['email'],
                $validatedData['password'],
                $validatedData['birth_date'],
                $validatedData['roles']
            );

            $emailVerifyLink = $this->sendVerificationEmail(user: $user);

            return response()->json([
                'status'  => 'SUCCESS',
                'message' => 'User created successfully!',
                'data'    => [
                    'user'                  => $user->toArray(),
                    'email_verif_link_sent' => !empty($emailVerifyLink->getId())
                ]
            ], 200);
        } catch (\Throwable $th) {
            dd($th);
            logger()->error($th);

            return response()->json([
                'message' => 'An unexpected error just happened!',
                'data'    => []
            ], 500);
        }
    }

    private function createUserWithRole(string $name, string $email, string $password, string $birthDate, array $roles): User
    {
        $roleCommand = new FetchRoleInformationCommand($roles);
        $role = $this->fetchRoleInformationCommandHandler->handle($roleCommand);

        $userCommand = new CreateUserCommand(
            $name,
            $email,
            $password,
            Carbon::createFromFormat('Y/m/d', $birthDate),
            $role
        );

        $user = $this->createUserHandler->handle($userCommand);

        $setRoleCommand = new SetRoleToUserCommand($user->getId(), $role);
        $this->setRoleToUserCommandHandler->handle($setRoleCommand);

        return $user;
    }

    /**
     * verifyEmail
     *
     * @param  string $id
     * @param  string $hash
     * @return JsonResponse
     */
    public function verifyEmail(string $id, string $hash): JsonResponse
    {
        try {
            $user = $this->fetchUserById($id);

            $this->verifyEmailHash($user, $hash);

            $messageResponse = 'Email already verified!';

            if ($user->getStatus() !== ElementStatus::ACTIVE) {
                $user = $this->activateUser($user);
                $messageResponse = 'Email verified successfully!';
            }

            return response()->json([
                'status'  => 'SUCCESS',
                'message' => $messageResponse,
                'data'    => [
                    'user' => $user->toArray(),
                ]
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'An unexpected error just happened!',
                'data'    => []
            ], 500);
        }
    }

    /**
     * sendVerificationEmail
     *
     * @param  User $user
     * @return EmailVerification
     */
    private function sendVerificationEmail(User $user): EmailVerification
    {
        $createEmailCommand = new CreateEmailVerificationCommand($user->getId(), $user->getEmail());
        $emailVerifyLink = $this->verifyEmailHandler->handleLinkGeneration($createEmailCommand);

        $sendEmailCommand = new SendVerificationEmailCommand($emailVerifyLink);
        return $this->verifyEmailHandler->sendVerificationEmail($sendEmailCommand);
    }

    /**
     * fetchUserById
     *
     * @param  string $id
     * @return User
     */
    private function fetchUserById(string $id): User
    {
        $command = new FetchUserInformationCommand($id, null);
        return $this->fetchUserCommandHandler->handle($command);
    }


    /**
     * verifyEmailHash
     *
     * @param  User $user
     * @param  string $hash
     * @return void
     */
    private function verifyEmailHash(User $user, string $hash): void
    {
        $command = new VerifyEmailCommand($user->getId(), $user->getEmail(), $hash);
        $this->verifyEmailHandler->handleHashVerification($command);
    }


    /**
     * activateUser
     *
     * @param  User $user
     * @return User
     */
    private function activateUser(User $user): User
    {
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

        return $this->updateUserCommandHandler->handle($command);
    }
}
