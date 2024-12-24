<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CreateHexagonalElement extends Command
{
    protected $signature = 'make:hexagonal-element {name}';
    protected $description = 'Create a hexagonal architecture element (Entity, Repository, UseCase, DTO, Command, etc.)';

    public function handle()
    {
        $name = $this->argument('name');
        $name = ucfirst($name);

        $paths = [
            "app/Domain/Entities/{$name}.php"                                  => $this->getEntityTemplate($name),
            "app/Domain/Repositories/{$name}RepositoryInterface.php"           => $this->getRepositoryInterfaceTemplate($name),
            "app/Domain/Services/{$name}Service.php"                           => $this->getDomainServiceTemplate($name),
            "app/Application/Commands/{$name}/Create{$name}Command.php"        => $this->getCommandTemplate($name),
            "app/Application/DTOs/{$name}/{$name}DTO.php"                      => $this->getDTOTemplate($name),
            "app/Application/Handlers/{$name}/Create{$name}CommandHandler.php" => $this->getCommandHandlerTemplate($name),
            "app/Application/UseCases/{$name}UseCase.php"                      => $this->getUseCaseTemplate($name),
            "app/Infrastructure/Persistence/Eloquent/{$name}Repository.php"    => $this->getEloquentRepositoryTemplate($name),
            "app/Infrastructure/Controllers/{$name}Controller.php"             => $this->getControllerTemplate($name),
            "app/Infrastructure/Persistence/Eloquent/Models/{$name}.php"       => $this->getModelTemplate($name),
            "app/Infrastructure/Services/{$name}Service.php"                   => $this->getInfrastructureServiceTemplate($name),
            "tests/Unit/{$name}/{$name}UseCaseTest.php"                        => $this->getUseCaseTestTemplate($name),
            "tests/Unit/{$name}/{$name}RepositoryTest.php"                     => $this->getRepositoryTestTemplate($name),
            "tests/Unit/{$name}/{$name}ControllerTest.php"                     => $this->getControllerTestTemplate($name),
        ];

        foreach ($paths as $path => $content) {
            $this->createFile($path, $content);
        }

        $this->info("Hexagonal element '{$name}' created successfully!");

        $this->updateComposerJson();
        $this->info("Updated composer.json autoload configuration.");

        $this->info("Running composer dump-autoload...");
        exec('composer dump-autoload');
    }

    private function updateComposerJson()
    {
        $composerPath = base_path('composer.json');
        $composerContent = json_decode(file_get_contents($composerPath), true);

        if (!isset($composerContent['autoload']['psr-4']['App\\'])) {
            $composerContent['autoload']['psr-4']['App\\'] = "app/";
        }

        file_put_contents($composerPath, json_encode($composerContent, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }

    private function createFile($path, $content)
    {
        if (!File::exists(dirname($path))) {
            File::makeDirectory(dirname($path), 0755, true);
        }

        if (!File::exists($path)) {
            File::put($path, $content);
            $this->info("Created: {$path}");
        } else {
            $this->warn("Skipped (already exists): {$path}");
        }
    }

    private function getEntityTemplate($name)
    {
        return <<<PHP
<?php

namespace App\Domain\Entities;

use App\Domain\Attributes\Getter;
use App\Domain\Attributes\Setter;
use App\Domain\Traits\AccessorTrait;

class {$name}
{
    use AccessorTrait;

    public function __construct(
        #[Getter] #[Setter]
        public ?int \$id,
        #[Getter] #[Setter]
        public string \$name
    ) {}
}
PHP;
    }

    private function getRepositoryInterfaceTemplate($name)
    {
        return <<<PHP
<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\\{$name};

interface {$name}RepositoryInterface
{
    public function save({$name} \$entity): {$name};
}
PHP;
    }

    private function getDomainServiceTemplate($name)
    {
        return <<<PHP
<?php

namespace App\Domain\Services;

use App\Domain\Entities\\{$name};
use App\Domain\Repositories\\{$name}RepositoryInterface;

class {$name}Service{
    public function __construct(private {$name}RepositoryInterface \$repository) {}
}
PHP;
    }

    private function getInfrastructureServiceTemplate($name)
    {
        return <<<PHP
<?php

namespace App\Infrastructure\Services;

use Illuminate\Support\Facades\Mail;

class {$name}Service{
    public function sendNotification(string \$email, string \$message)
    {
        Mail::raw(\$message, function (\$mail) use (\$email) {
            \$mail->to(\$email)
                ->subject('Notification');
        });
    }}
PHP;
    }

    private function getDTOTemplate($name)
    {
        return <<<PHP
<?php

namespace App\Application\DTOs\\{$name};

class {$name}DTO
{
    public function __construct(
        public string \$name
    ) {}

    public static function fromRequest(array \$data): self
    {
        return new self(\$data['name']);
    }
}
PHP;
    }

    private function getUseCaseTemplate($name)
    {
        return <<<PHP
<?php

namespace App\Application\UseCases;

use App\Domain\Repositories\\{$name}RepositoryInterface;
use App\Application\DTOs\\{$name}\\{$name}DTO;
use App\Domain\Entities\\{$name};

class {$name}UseCase
{
    public function __construct(private {$name}RepositoryInterface \$repository) {}

    public function execute({$name}DTO \$dto): {$name}
    {
        \$entity = new {$name}(
            id: null,
            name: \$dto->name
        );

        return \$this->repository->save(\$entity);
    }
}
PHP;
    }

    private function getEloquentRepositoryTemplate($name)
    {
        return <<<PHP
<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Repositories\\{$name}RepositoryInterface;
use App\Domain\Entities\\{$name};
use App\Infrastructure\Persistence\Eloquent\Models\\{$name} as {$name}Model;

class {$name}Repository implements {$name}RepositoryInterface
{
    public function save({$name} \$entity): {$name}
    {
        \$model = {$name}Model::updateOrCreate(
            ['id' => \$entity->id],
            ['name' => \$entity->name]
        );

        \$entity->id = \$model->id;

        return \$entity;
    }
}
PHP;
    }

    private function getControllerTemplate($name)
    {
        return <<<PHP
<?php

namespace App\Infrastructure\Controllers;

use App\Application\UseCases\\{$name}UseCase;
use App\Application\DTOs\\{$name}\\{$name}DTO;
use Illuminate\Http\Request;

class {$name}Controller
{
    public function __construct(private {$name}UseCase \$useCase) {}

    public function store(Request \$request)
    {
        \$dto = {$name}DTO::fromRequest(\$request->all());
        \$entity = \$this->useCase->execute(\$dto);

        return response()->json([
            'message' => '{$name} created successfully!',
            '{$name}' => \$entity
        ]);
    }
}
PHP;
    }

    private function getModelTemplate($name)
    {
        return <<<PHP
<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;

class {$name} extends Model
{
    protected \$table = '{$name}s'; // Tabla en la base de datos

    protected \$fillable = ['name']; // Atributos que pueden ser asignados en masa

    public \$timestamps = true; // Habilitar timestamps si es necesario
}
PHP;
    }

    private function getCommandTemplate($name)
    {
        return <<<PHP
<?php

namespace App\Application\Commands\\{$name};

class Create{$name}Command
{
    private string \$name;
    private string \$email;
    private ?string \$phoneNumber;

    public function __construct(string \$name, string \$email, ?string \$phoneNumber = null)
    {
        \$this->name = \$name;
        \$this->email = \$email;
        \$this->phoneNumber = \$phoneNumber;
    }

    public function getName(): string
    {
        return \$this->name;
    }

    public function getEmail(): string
    {
        return \$this->email;
    }

    public function getPhoneNumber(): ?string
    {
        return \$this->phoneNumber;
    }
}
PHP;
    }


    private function getCommandHandlerTemplate($name)
    {
        return <<<PHP
<?php

namespace App\Application\Handlers\\{$name};

use App\Application\Commands\\{$name}\\Create{$name}Command;
use App\Domain\Repositories\\{$name}RepositoryInterface;
use App\Domain\Entities\\{$name};

class Create{$name}CommandHandler
{
    private {$name}RepositoryInterface \$repository;

    public function __construct({$name}RepositoryInterface \$repository)
    {
        \$this->repository = \$repository;
    }

    public function handle(Create{$name}Command \$command): {$name}
    {
        \$entity = new {$name}(null, \$command->getName());
        return \$this->repository->save(\$entity);
    }
}
PHP;
    }


    // Archivos de pruebas unitarias

    private function getUseCaseTestTemplate($name)
    {
        return <<<PHP
<?php

namespace Tests\Unit\\{$name};

use App\Application\UseCases\\{$name}UseCase;
use App\Domain\Repositories\\{$name}RepositoryInterface;
use App\Application\DTOs\\{$name}\\{$name}DTO;
use Tests\TestCase;
use Mockery;

class {$name}UseCaseTest extends TestCase
{
    public function testExecute()
    {
        \$repositoryMock = Mockery::mock({$name}RepositoryInterface::class);
        \$repositoryMock->shouldReceive('save')
            ->once()
            ->andReturn(new \App\Domain\Entities\\{$name}(1, 'Test'));

        \$useCase = new {$name}UseCase(\$repositoryMock);
        \$dto = new {$name}DTO('Test');
        \$result = \$useCase->execute(\$dto);

        \$this->assertInstanceOf(\App\Domain\Entities\\{$name}::class, \$result);
        \$this->assertEquals('Test', \$result->name);
    }
}
PHP;
    }

    private function getRepositoryTestTemplate($name)
    {
        return <<<PHP
<?php

namespace Tests\Unit\\{$name};

use App\Infrastructure\Persistence\Eloquent\\{$name}Repository;
use App\Domain\Repositories\\{$name}RepositoryInterface;
use App\Infrastructure\Persistence\Eloquent\Models\\{$name} as {$name}Model;
use Tests\TestCase;

class {$name}RepositoryTest extends TestCase
{
    public function testSave()
    {
        \$model = new {$name}Model();
        \$model->name = 'Test';
        \$model->save();

        \$repository = new {$name}Repository();
        \$entity = \$repository->save(new \App\Domain\Entities\\{$name}(null, 'Test'));

        \$this->assertEquals('Test', \$entity->name);
    }
}
PHP;
    }

    private function getControllerTestTemplate($name)
    {
        return <<<PHP
<?php

namespace Tests\Unit\\{$name};

use App\Infrastructure\Controllers\\{$name}Controller;
use App\Application\UseCases\\{$name}UseCase;
use Illuminate\Http\Request;
use Tests\TestCase;
use Mockery;

class {$name}ControllerTest extends TestCase
{
    public function testStore()
    {
        \$useCaseMock = Mockery::mock({$name}UseCase::class);
        \$useCaseMock->shouldReceive('execute')
            ->once()
            ->andReturn(new \App\Domain\Entities\\{$name}(1, 'Test'));

        \$controller = new {$name}Controller(\$useCaseMock);

        \$request = Request::create('/store', 'POST', ['name' => 'Test']);
        \$response = \$controller->store(\$request);

        \$this->assertEquals(200, \$response->getStatusCode());
        \$this->assertStringContainsString('Test created successfully!', \$response->getContent());
    }
}
PHP;
    }
}
