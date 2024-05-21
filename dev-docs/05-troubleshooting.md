## Enpii Base troubleshooting
- We may face some errors/issues when having Enpii Base plugin working on your application.

1. Symfony Console issue
- When using Laravel 10 or any package that requires `symfony/console` >= 6.0, the declaration `Symfony\Component\Console\Application::run` would be
```
Symfony\Component\Console\Application::run(?Symfony\Component\Console\Input\InputInterface $input = null, ?Symfony\Component\Console\Output\OutputInterface $output = null): int
```
while in older version < 6.0.0, it has not return type
```
Symfony\Component\Console\Application::run(?Symfony\Component\Console\Input\InputInterface $input = null, ?Symfony\Component\Console\Output\OutputInterface $output = null)
```
therefore, errors happens

- **Solution**: try to have `symfony/console` < 6.0.0 or >= 6.0.0 for all packages that requires `symfony/console`
