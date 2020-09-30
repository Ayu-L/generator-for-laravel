<?php


namespace Ayu\Generator\Console;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

class CodeMakeCommand extends GeneratorCommand
{
    protected $name = 'ayu:code {name} {--type=default}';

    protected $signature = 'ayu:code
                        {name : 代码名称}
                        {--type= : 选择生成类型}';

    protected $types = [
        'all',
        'model',
        'request',
        'service',
        'controller'
    ];

    protected $finishName;

    protected $typeName;

    protected $suffix;

    protected $variableName;

    public function handle()
    {
        $this->setFinishName();
        $this->variableName = $this->argument('name');

        switch ($this->option('type')) {
            case null:
            case 'all':
                $this->createBaseModel();
                $this->createModel();
                $this->createValidatorRequest();
                $this->createRequest();
                $this->createController();
                $this->createService();
                break;
            case 'model':
                $this->createBaseModel();
                $this->createModel();
                break;
            case 'request':
                $this->createValidatorRequest();
                $this->createRequest();
                break;
            case 'service':
                $this->createService();
                break;
            case 'controller':
                $this->createController();
                break;

            default:
                $this->error('Error Error!!');
                break;
        }
    }

    protected function setFinishName()
    {
        $this->finishName = Str::studly(class_basename($this->argument('name')));
    }

    public function createValidatorRequest()
    {
        $this->typeName = 'validatorRequest';

        $this->suffix = '\Http\Requests';

        $name = $this->qualifyClass('Validator') . 'Request';

        $path = $this->getPath($name);

        if (! $this->files->exists($path)) {

            $this->createFile($name, $path);

            $this->info($this->typeName.' created successfully.');
        }
    }

    public function createBaseModel()
    {
        $this->typeName = 'baseModel';

        $this->suffix = '\Models';

        $name = $this->qualifyClass('Model');

        $path = $this->getPath($name);

        if (! $this->files->exists($path)) {

            $this->createFile($name, $path);

            $this->info($this->typeName.' created successfully.');
        }
    }

    protected function createModel()
    {
        $this->typeName = 'model';
        $this->suffix = '\Models';

        // 获取命名空间
        $name = $this->qualifyClass($this->finishName);

        // 获取地址
        $this->createFile($name);

        $this->info($this->typeName.' created successfully.');
    }

    protected function createRequest()
    {
        $this->typeName = 'request';
        $this->suffix = '\Http\Requests';

        // 获取命名空间
        $name = $this->qualifyClass($this->finishName) . 'Request';

        // 获取地址
        $this->createFile($name);

        $this->info($this->typeName.' created successfully.');
    }

    protected function createService()
    {
        $this->typeName = 'service';
        $this->suffix = '\Services';

        // 获取命名空间
        $name = $this->qualifyClass($this->finishName) . 'Service';

        // 获取地址
        $this->createFile($name);

        $this->info($this->typeName.' created successfully.');

    }

    protected function createController()
    {
        $this->typeName = 'controller';
        $this->suffix = '\Http\Controllers';

        // 获取命名空间
        $name = $this->qualifyClass($this->finishName) . 'Controller';

        // 获取地址
        $this->createFile($name);

        $this->info($this->typeName.' created successfully.');
    }

    protected function createFile($namespace, $path = null)
    {
        // 获取地址
        if ($path === null) {
            $path = $this->getPath($namespace);
        }

        // 按照地址生成文件夹
        $this->makeDirectory($path);
        // 生成文件
        $stub = $this->buildUseName($this->buildVariableName($this->buildClass($namespace)));

        $this->files->put($path, $this->sortImports($stub));
    }

    protected function buildVariableName($stub)
    {
        return str_replace(['{{ variableName }}'], $this->variableName, $stub);
    }

    protected function buildUseName($stub)
    {
        return str_replace(['{{ useName }}'], $this->finishName, $stub);
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . $this->suffix;
    }

    protected function getStub()
    {
        return __DIR__.'/stubs/'. $this->typeName .'.stub';
    }
}
