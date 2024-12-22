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
            "app/Domain/Entities/{$name}.php"                               => $this->getEntityTemplate($name),
            "app/Domain/Repositories/{$name}RepositoryInterface.php"        => $this->getRepositoryInterfaceTemplate($name),
            "app/Application/DTOs/{$name}DTO.php"                           => $this->getDTOTemplate($name),
            "app/Application/UseCases/{$name}UseCase.php"                   => $this->getUseCaseTemplate($name),
            "app/Infrastructure/Persistence/Eloquent/{$name}Repository.php" => $this->getEloquentRepositoryTemplate($name),
            "app/Infrastructure/Controllers/{$name}Controller.php"          => $this->getControllerTemplate($name),
            "app/Infrastructure/Persistence/Eloquent/Models/{$name}.php"    => $this->getModelTemplate($name),
            "app/Application/Commands/Register{$name}Command.php"           => $this->getCommandTemplate($name),
            "tests/Unit/{$name}/{$name}UseCaseTest.php"                     => $this->getUseCaseTestTemplate($name),
            "tests/Unit/{$name}/{$name}RepositoryTest.php"                  => $this->getRepositoryTestTemplate($name),
            "tests/Unit/{$name}/{$name}ControllerTest.php"                  => $this->getControllerTestTemplate($name),
        ];

        foreach ($paths as $path => $content) {
            $this->createFile($path, $content);
        }

        $this->info("Hexagonal element '{$name}' created successfully!");
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

class {$name}
{
    public function __construct(
        public ?int \$id,
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

    private function getDTOTemplate($name)
    {
        return <<<PHP
<?php

namespace App\Application\DTOs;

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
use App\Application\DTOs\\{$name}DTO;
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
use App\Application\DTOs\\{$name}DTO;
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

namespace App\Application\Commands;

use Illuminate\Console\Command;

class Register{$name}Command extends Command
{
    protected \$signature = '{$name}:run';
    protected \$description = 'Command to manage or execute operations for {$name}';

    public function handle()
    {
        \$this->info('{$name} command executed successfully!');
    }
}
PHP;
    }

    // Archivos de pruebas unitarias

    private function getUseCaseTestTemplate($name)
    {
        return <<<PHP
<?php

namespace Tests\Unit\{$name};

use App\Application\UseCases\\{$name}UseCase;
use App\Domain\Repositories\\{$name}RepositoryInterface;
use App\Application\DTOs\\{$name}DTO;
use PHPUnit\Framework\TestCase;
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

namespace Tests\Unit\{$name};

use App\Infrastructure\Persistence\Eloquent\\{$name}Repository;
use App\Domain\Repositories\\{$name}RepositoryInterface;
use App\Infrastructure\Persistence\Eloquent\Models\\{$name} as {$name}Model;
use PHPUnit\Framework\TestCase;

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

namespace Tests\Unit;

use App\Infrastructure\Controllers\\{$name}Controller;
use App\Application\UseCases\\{$name}UseCase;
use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;
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
