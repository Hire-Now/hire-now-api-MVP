<?php

namespace App\Infrastructure\Controllers;

use Carbon\Carbon;

use Illuminate\Http\JsonResponse;

use App\Domain\Enums\ElementStatus;
use App\Domain\Entities\User;
use App\Domain\Entities\EmailVerification;

use App\Application\Commands\User\CreateUserCommand;
use App\Application\Commands\User\SetRoleToUserCommand;
use App\Application\Commands\User\FetchUserInformationCommand;
use App\Application\Commands\User\UpdateUserInformationCommand;
use App\Application\Commands\User\CheckUserCredentialsCommand;
use App\Application\Commands\User\GenerateJWTUserCommand;
use App\Application\Commands\Email\SendVerificationEmailCommand;
use App\Application\Commands\Email\CreateEmailVerificationCommand;
use App\Application\Commands\Email\VerifyEmailCommand;
use App\Application\Commands\Role\FetchRoleInformationCommand;

use App\Application\Handlers\Role\FetchRoleInformationCommandHandler;
use App\Application\Handlers\User\CheckUserCredentialsCommandHandler;
use App\Application\Handlers\User\GenerateJWTUserCommandHandler;
use App\Application\Handlers\User\SetRoleToUserCommandHandler;
use App\Application\Handlers\User\FetchUserCommandHandler;
use App\Application\Handlers\User\CreateUserCommandHandler;
use App\Application\Handlers\User\UpdateUserCommandHandler;
use App\Application\Handlers\Email\VerifyEmailCommandHandler;

use App\Application\Contracts\AuthorizationInterface;

use App\Infrastructure\Requests\AuthenticateUserRequest;
use App\Infrastructure\Requests\CreateUserRequest;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        private CreateUserCommandHandler $createUserHandler,
        private VerifyEmailCommandHandler $verifyEmailHandler,
        private FetchUserCommandHandler $fetchUserCommandHandler,
        private UpdateUserCommandHandler $updateUserCommandHandler,
        private FetchRoleInformationCommandHandler $fetchRoleInformationCommandHandler,
        private SetRoleToUserCommandHandler $setRoleToUserCommandHandler,
        private CheckUserCredentialsCommandHandler $checkUserCredentialsCommandHandler,
        private GenerateJWTUserCommandHandler $generateJWTUserCommandHandler,
        private AuthorizationInterface $authorizationService
    ) {
    }

    public function index(Request $request)
    {
        try {
            $request = $request->validated();


        } catch (\Throwable $th) {
            logger()->error("Error in UserController@authenticate: {$th->getMessage()}", [
                'trace' => $th->getTraceAsString()
            ]);

            return response()->json([
                'status'  => 'ERROR',
                'message' => 'Failed to authenticate the user, please try again later.',
                'data'    => []
            ], 500);
        }
    }

    public function show(Request $request)
    {
        try {
            $this->authorizationService->userPolicy($request->attributes->get('user_entity')->getId(), $request->attributes->get('user_model'));

        } catch (\Throwable $th) {
            logger()->error("Error in UserController@authenticate: {$th->getMessage()}", [
                'trace' => $th->getTraceAsString()
            ]);

            return response()->json([
                'status'  => 'ERROR',
                'message' => 'Failed to get the user, please try again later.',
                'data'    => []
            ], 500);
        }
    }

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
            logger()->error("Error in UserController@store: {$th->getMessage()}", [
                'trace' => $th->getTraceAsString()
            ]);

            return response()->json([
                'status'  => 'ERROR',
                'message' => 'Failed to create the user. Please try again later.',
                'data'    => []
            ], 500);
        }
    }

    public function verifyEmail(string $id, string $hash): JsonResponse
    {
        try {
            $fetchUserInformationcommand = new FetchUserInformationCommand($id, null);
            $user = $this->fetchUserCommandHandler->handle($fetchUserInformationcommand);

            $command = new VerifyEmailCommand($user->getId(), $user->getEmail(), $hash);
            $this->verifyEmailHandler->handleHashVerification($command);

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
            logger()->error("Error in UserController@verifyEmail: {$th->getMessage()}", [
                'user_id' => $id,
                'hash'    => $hash,
                'trace'   => $th->getTraceAsString()
            ]);

            return response()->json([
                'status'  => 'ERROR',
                'message' => 'Failed to verify email. Please try again later.',
                'data'    => []
            ], 500);
        }
    }

    private function createUserWithRole(string $name, string $email, string $password, string $birthDate, array $roles): User
    {
        try {
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
        } catch (\Throwable $th) {
            logger()->error("Error in UserController@createUserWithRole: {$th->getMessage()}", [
                'name'  => $name,
                'email' => $email,
                'roles' => $roles,
                'trace' => $th->getTraceAsString()
            ]);

            throw new \Exception('Failed to create user with roles.', 0, $th);
        }
    }

    private function sendVerificationEmail(User $user): EmailVerification
    {
        try {
            $createEmailCommand = new CreateEmailVerificationCommand($user->getId(), $user->getEmail());
            $emailVerifyLink = $this->verifyEmailHandler->handleLinkGeneration($createEmailCommand);

            $sendEmailCommand = new SendVerificationEmailCommand($emailVerifyLink);
            return $this->verifyEmailHandler->sendVerificationEmail($sendEmailCommand);
        } catch (\Throwable $th) {
            logger()->error("Error in UserController@sendVerificationEmail: {$th->getMessage()}", [
                'user_id' => $user->getId(),
                'trace'   => $th->getTraceAsString()
            ]);

            throw new \Exception('Failed to send verification email.', 0, $th);
        }
    }

    private function activateUser(User $user): User
    {
        try {
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
        } catch (\Throwable $th) {
            logger()->error("Error in UserController@activateUser: {$th->getMessage()}", [
                'user_id' => $user->getId(),
                'trace'   => $th->getTraceAsString()
            ]);

            throw new \Exception('Failed to activate user.', 0, $th);
        }
    }

    public function authenticate(AuthenticateUserRequest $request)
    {
        try {
            $request = $request->validated();

            $checkUserCredentialsCommand = new CheckUserCredentialsCommand($request['email'], $request['password']);
            $user = $this->checkUserCredentialsCommandHandler->handle($checkUserCredentialsCommand);

            $generateJWTUserCommand = new GenerateJWTUserCommand($user);
            $jwt = $this->generateJWTUserCommandHandler->handle($generateJWTUserCommand);

            return response()->json([
                'status'  => 'SUCCESS',
                'message' => 'Authentication successful',
                'data'    => [
                    'token'      => $jwt,
                    'type'       => 'Bearer',
                    'expires_in' => config('app.user_auth.jwt_validity_time') . ' ' . config('app.user_auth.jwt_type_time')
                ]
            ], 200);
        } catch (\Throwable $th) {
            logger()->error("Error in UserController@authenticate: {$th->getMessage()}", [
                'trace' => $th->getTraceAsString()
            ]);

            return response()->json([
                'status'  => 'ERROR',
                'message' => 'Failed to authenticate the user, please try again later.',
                'data'    => []
            ], 500);
        }
    }

    public function update(AuthenticateUserRequest $request)
    {
        try {
            $request = $request->validated();


        } catch (\Throwable $th) {
            logger()->error("Error in UserController@authenticate: {$th->getMessage()}", [
                'trace' => $th->getTraceAsString()
            ]);

            return response()->json([
                'status'  => 'ERROR',
                'message' => 'Failed to authenticate the user, please try again later.',
                'data'    => []
            ], 500);
        }
    }

    public function delete(AuthenticateUserRequest $request)
    {
        try {
            $request = $request->validated();


        } catch (\Throwable $th) {
            logger()->error("Error in UserController@authenticate: {$th->getMessage()}", [
                'trace' => $th->getTraceAsString()
            ]);

            return response()->json([
                'status'  => 'ERROR',
                'message' => 'Failed to authenticate the user, please try again later.',
                'data'    => []
            ], 500);
        }
    }

    public function assignRoleToUser(Request $request)
    {
    }

}
