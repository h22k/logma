## help:🖐🏽See available make commands
.PHONY: help
help:
	@sed -n 's/^##//p' ${MAKEFILE_LIST} | column -t -s ':' |  sed -e 's/^/ /'

## before-commit: 🚀 Runs fixer, phpstan and tests
.PHONY: before-commit
before-commit:
	composer fix src/ && composer fix tests/ && composer phpstan && composer test-detail && echo "it's ready to commit"
