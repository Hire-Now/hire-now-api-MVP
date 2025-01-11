<?php

namespace App\Infrastructure\Adapter\Inbound;

use App\Application\Commands\User\AssignRoleToUserCommand;
use App\Infrastructure\Requests\AssignRoleToUserRequest;
use App\Infrastructure\Requests\RemoveRoleToUserRequest;
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
use App\Application\Commands\User\ListUsersCommand;
use App\Application\Commands\User\RemoveRoleToUserCommand;
use App\Application\Commands\User\UpdateUserCommand;
use App\Application\Handlers\Role\FetchRoleInformationCommandHandler;
use App\Application\Handlers\User\CheckUserCredentialsCommandHandler;
use App\Application\Handlers\User\GenerateJWTUserCommandHandler;
use App\Application\Handlers\User\SetRoleToUserCommandHandler;
use App\Application\Handlers\User\FetchUserCommandHandler;
use App\Application\Handlers\User\CreateUserCommandHandler;
use App\Application\Handlers\User\UpdateUserCommandHandler;
use App\Application\Handlers\Email\VerifyEmailCommandHandler;

use App\Application\Contracts\AuthorizationInterface;
use App\Application\Handlers\User\AssignRoleToUserCommandHandler;
use App\Application\Handlers\User\DeleteUserCommandHandler;
use App\Application\Handlers\User\GetUserCommandHandler;
use App\Application\Handlers\User\ListUsersCommandHandler;
use App\Application\Handlers\User\RemoveRoleToUserCommandHandler;
use App\Infrastructure\Requests\AuthenticateUserRequest;
use App\Infrastructure\Requests\CreateUserRequest;
use App\Infrastructure\Requests\UpdateUserRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Validation\UnauthorizedException;

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
        private AuthorizationInterface $authorizationService,
        private ListUsersCommandHandler $listUsersCommandHandler,
        private AssignRoleToUserCommandHandler $assignRoleToUserCommandHandler,
        private RemoveRoleToUserCommandHandler $removeRoleToUserCommandHandler,
        private GetUserCommandHandler $getUserCommandHandler,
        private DeleteUserCommandHandler $deleteUserCommandHandler
    ) {
    }

    public function index(Request $request)
    {
        try {
            $command = new ListUsersCommand(
                $request->query('name') ?? null,
                $request->query('status') ?? null,
                $request->query('order_by') ?? 'created',
                $request->query('order_direction') ?? 'asc',
            );

            $users = $this->listUsersCommandHandler->handle($command);

            return response()->json([
                'status'  => 'SUCCESS',
                'message' => 'Users obtained succesfully!',
                'data'    => $users
            ], 200);
        } catch (\Throwable $th) {
            logger()->error("Error in UserController@index: {$th->getMessage()}", [
                'trace' => $th->getTraceAsString()
            ]);

            return response()->json([
                'status'  => 'ERROR',
                'message' => 'Failed to get the users, please try again later.',
                'data'    => []
            ], 500);
        }
    }

    public function show(Request $request, string $id)
    {
        try {
            $user = $request->attributes->get('user_model');
            $entityId = $request->attributes->get('user_entity')->getId();

            $this->authorizationService->userPolicy($entityId, $id, $user, [ 'recruiter', 'executive' ]);

            $users = $this->getUserCommandHandler->handle($id);

            return response()->json([
                'status'  => 'SUCCESS',
                'message' => 'Users obtained successfully!',
                'data'    => $users->toArray()
            ], 200);

        } catch (UnauthorizedException $e) {
            return response()->json([
                'status'  => 'FORBIDDEN',
                'message' => $e->getMessage(),
                'data'    => []
            ], 403);

        } catch (\Throwable $th) {
            logger()->error("Error in UserController@show: {$th->getMessage()}", [
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
    {   //TODO: ADMIN ROLE CANNOT BE ASSIGN USING A API PETITION, IT MUST BE USED A COMMAND TO CREATE A USER WITH ADMIN ROLE
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
    {//todo: Implementar tiempo de expiracion para validacion de email, 10 min para validar si no genera nuevo enlace
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
                null,
                $user->getBirthDate(),
                Carbon::now(),
                ElementStatus::ACTIVE
            );

            $command->setUserActivation(true);
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

    public function update(UpdateUserRequest $request, string $id)
    {
        try {
            $user = $request->attributes->get('user_model');
            $entityId = $request->attributes->get('user_entity')->getId();

            $this->authorizationService->userPolicy($entityId, $id, $user);

            $request = $request->validated();
            $userEntity = $request->attributes->get('user_entity');
            $emailHasChanged = !empty($request['email']) && $request['email'] === $userEntity->getEmail() ? true : false;

            $userCommand = new UpdateUserInformationCommand(
                $id,
                $request['name'] ?? null,
                $request['email'] ?? null,
                $request['password'] ?? null,
                $request['birth_date'] ?? null,
                Carbon::now(),
                null
            );

            $user = $this->updateUserCommandHandler->handle($userCommand);

            if ($emailHasChanged) {
                $emailVerifyLink = $this->sendVerificationEmail(user: $user);
            }

            return response()->json([
                'status'  => 'SUCCESS',
                'message' => 'User created successfully!',
                'data'    => [
                    'user'                  => $user->toArray(),
                    'email_has_changed'     => $emailHasChanged,
                    'email_verif_link_sent' => $emailHasChanged && !empty($emailVerifyLink->getId()) ? true : false
                ]
            ], 200);
        } catch (UnauthorizedException $e) {
            return response()->json([
                'status'  => 'FORBIDDEN',
                'message' => $e->getMessage(),
                'data'    => []
            ], 403);

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

    public function delete(Request $request, string $id)
    {
        try {
            $user = $request->attributes->get('user_model');
            $entityId = $request->attributes->get('user_entity')->getId();

            $this->authorizationService->userPolicy($entityId, $id, $user);

            $users = $this->deleteUserCommandHandler->handle($id);

            return response()->json([
                'status'  => 'SUCCESS',
                'message' => 'Process executed succesfully!',
                'data'    => [
                    'is_user_deleted' => $users
                ]
            ], 200);
        } catch (UnauthorizedException $e) {
            return response()->json([
                'status'  => 'FORBIDDEN',
                'message' => $e->getMessage(),
                'data'    => []
            ], 403);

        } catch (\Throwable $th) {
            logger()->error("Error in UserController@delete: {$th->getMessage()}", [
                'trace' => $th->getTraceAsString()
            ]);

            return response()->json([
                'status'  => 'ERROR',
                'message' => 'Failed to delete the user, please try again later.',
                'data'    => []
            ], 500);
        }

    }

    public function assignRoleToUser(AssignRoleToUserRequest $request, string $userId)
    {
        try {
            $request = $request->validated();

            $roleCommand = new FetchRoleInformationCommand(roles: $request['roles']);
            $role = $this->fetchRoleInformationCommandHandler->handle($roleCommand);

            $command = new AssignRoleToUserCommand(
                $userId,
                $role
            );

            $user = $this->assignRoleToUserCommandHandler->handle($command);

            return response()->json([
                'status'  => 'SUCCESS',
                'message' => 'Roles succesfully assigned to the user!',
                'data'    => $user->toArray()
            ], 200);
        } catch (\Throwable $th) {
            logger()->error("Error in UserController@assignRoleToUser: {$th->getMessage()}", [
                'trace' => $th->getTraceAsString()
            ]);

            return response()->json([
                'status'  => 'ERROR',
                'message' => 'Failed to assign new roles the user, please try again later.',
                'data'    => []
            ], 500);
        }
    }

    public function removeRoleToUser(RemoveRoleToUserRequest $request, string $userId)
    {
        try {
            $request = $request->validated();

            $command = new RemoveRoleToUserCommand(
                $userId,
                $request['roles']
            );

            $user = $this->removeRoleToUserCommandHandler->handle($command);

            return response()->json([
                'status'  => 'SUCCESS',
                'message' => 'Roles succesfully removed to the user!',
                'data'    => $user->toArray()
            ], 200);
        } catch (\Throwable $th) {
            logger()->error("Error in UserController@assignRoleToUser: {$th->getMessage()}", [
                'trace' => $th->getTraceAsString()
            ]);

            return response()->json([
                'status'  => 'ERROR',
                'message' => 'Failed to remove roles to the user, please try again later.',
                'data'    => []
            ], 500);
        }
    }
}
