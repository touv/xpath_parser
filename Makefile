PEAR=pear
PHPUNIT=phpunit
XSLTPROC=xsltproc
CP=cp
MKDIR=mkdir
RM=rm

all : test

test :
	$(PHPUNIT) XPath_ParserTest

push:
	git push --tags

release: XPath_Parser-`./extract-version.sh`.tgz

XPath_Parser-`./extract-version.sh`.tgz: package.xml
	$(PEAR) package package.xml
	git tag -a -m "Version `./extract-version.sh`"  v`./extract-version.sh`
