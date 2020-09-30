# Ayu 专用 laravel便捷开发 代码生成器

在config/app.php 添加

~~~php
/*
 * Package Service Providers...
 */
Ayu\Generator\GeneratorServiceProvider::class
~~~

laravel命令行:

~~~bash
php artisan ayu:code {name} {--type}
~~~

生成model,request,service,controller,Model,ValidatorRequset



type: all|model|request|controller|service