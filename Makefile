PHP_CS_FIXER = vendor/bin/php-cs-fixer
PHPSTAN = vendor/bin/phpstan

linter-fix:
	@$(PHP_CS_FIXER) fix

linter-fix-dry:
	@$(PHP_CS_FIXER) fix --dry-run --diff

phpstan:
	@$(PHPSTAN) analyse --memory-limit=2G
