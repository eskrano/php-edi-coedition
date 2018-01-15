.PHONY: test doc-preview

test:
	./vendor/bin/peridot test
doc-preview:
	couscous preview
