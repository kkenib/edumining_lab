TOOL=requirejs
.PHONY: all
all: $(TOOL).js

.PHONY: $(TOOL).js
$(TOOL).js:
	if [ ! -e require.js ]; then curl -LO http://requirejs.org/docs/release/2.3.3/comments/require.js; fi
	if [ ! -e r.js ]; then curl -LO http://requirejs.org/docs/release/2.3.3/r.js; fi
	rm -f xlsx.full.min.js; ln -s ../../dist/xlsx.full.min.js
	node r.js -o build.js paths.requireLib=./require include=requireLib
