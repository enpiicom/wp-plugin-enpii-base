# Contributing to Enpii Base

Thank you for your interest in contributing to Enpii Base! This document provides guidelines for contributing to the project.

## Getting Started

### Prerequisites

- PHP 7.3 or higher (PHP 8.0+ recommended)
- WordPress 5.0 or higher
- Composer
- Node.js and Yarn
- Docker (for development environment)

### Development Setup

1. **Clone the repository:**
   ```bash
   git clone https://github.com/enpii/enpii-base.git
   cd enpii-base
   ```

2. **Install PHP dependencies:**
   ```bash
   XDEBUG=off composer install
   ```

3. **Install Node.js dependencies:**
   ```bash
   yarn install
   ```

4. **Set up development environment:**
   ```bash
   # For PHP 8.1 development
   XDEBUG=off COMPOSER=composer-dev81.json composer install
   docker-compose up -d
   ```

5. **Configure Git hooks:**
   ```bash
   git config core.hooksPath .githooks
   ```

## Development Guidelines

### Code Standards

- Follow WordPress Coding Standards
- Use PSR-4 autoloading
- Write PHPDoc comments for all classes and methods
- Use meaningful variable and function names

### Testing

Run tests before submitting contributions:

```bash
# PHP CodeSniffer
composer phpcs

# PHPUnit tests
composer phpunit

# Full test suite
composer test
```

### Code Quality Tools

- **PHPCS**: Ensures code follows WordPress standards
- **PHPStan**: Static analysis for PHP
- **PHPUnit**: Unit testing framework

## Contribution Process

### 1. Fork and Branch

1. Fork the repository
2. Create a feature branch: `git checkout -b feature/your-feature-name`
3. Make your changes
4. Test thoroughly

### 2. Commit Guidelines

- Use clear, descriptive commit messages
- Follow conventional commit format when possible
- Keep commits focused on a single change

Example:
```
feat: add support for Laravel 9

- Update composer dependencies
- Fix compatibility issues
- Add migration guide
```

### 3. Pull Request Process

1. **Update documentation** if needed
2. **Add tests** for new functionality
3. **Ensure all tests pass**
4. **Update CHANGELOG.md**
5. **Submit pull request** with clear description

### Pull Request Template

```markdown
## Description
Brief description of changes

## Type of Change
- [ ] Bug fix
- [ ] New feature
- [ ] Breaking change
- [ ] Documentation update

## Testing
- [ ] Tests pass locally
- [ ] Added new tests if applicable
- [ ] Manual testing completed

## Checklist
- [ ] Code follows project standards
- [ ] Self-review completed
- [ ] Documentation updated
- [ ] CHANGELOG.md updated
```

## Reporting Issues

### Bug Reports

Include:
- WordPress version
- PHP version
- Plugin version
- Steps to reproduce
- Expected vs actual behavior
- Error messages/logs

### Feature Requests

Include:
- Use case description
- Proposed solution
- Alternative solutions considered
- Additional context

## Development Architecture

### Key Components

- **WP_Application**: Main application container
- **Service Providers**: Laravel service provider wrappers
- **Kernel Services**: HTTP and Console kernels
- **Blade Templates**: Template rendering system

### Directory Structure

```
src/
├── App/              # Application logic
├── Foundation/       # Core framework components
└── helpers.php       # Global helper functions

tests/
├── Unit/            # Unit tests
└── Support/         # Test utilities

dev-docs/            # Development documentation
resources/           # Templates and assets
public-assets/       # Compiled assets
```

## Release Process

1. Update version numbers
2. Update CHANGELOG.md
3. Run full test suite
4. Create release tag
5. Deploy to WordPress.org (if applicable)

## Getting Help

- **Documentation**: Check `dev-docs/` directory
- **Issues**: Search existing issues first
- **Discussions**: Use GitHub Discussions for questions
- **WordPress.org**: Plugin support forum

## Code of Conduct

- Be respectful and inclusive
- Focus on constructive feedback
- Help others learn and grow
- Follow WordPress community guidelines

Thank you for contributing to Enpii Base!