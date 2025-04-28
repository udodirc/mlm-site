PHP_CS_FIXER = vendor/bin/php-cs-fixer

linter-fix:
	@$(PHP_CS_FIXER) fix

linter-fix-dry:
	@$(PHP_CS_FIXER) fix --dry-run --diff
