PEAR=pear
PHPUNIT=phpunit
XSLTPROC=xsltproc
CP=cp
MKDIR=mkdir
RM=rm
VERSION=`./extract-version.sh`
CURVER=XPath_Parser-$(VERSION).tgz
APIKEY=5cd8785b-c05c-72d4-71f5-fa6fc9c39839
PEARHOST=http://pear.respear.net/respear/


all : test

test :
	$(PHPUNIT) XPath_ParserTest

push:
	git push --tags

release: tagging pearing

tagging: $(CURVER)
	git tag -a -m "Version $(VERSION)"  v$(VERSION)

pearing: $(CURVER)
	@read -p "Who are you ? " toto && cat $(CURVER) | curl -u `echo $$toto`:$(APIKEY) -X POST --data-binary @- $(PEARHOST)

$(CURVER): package.xml
	$(PEAR) package $?
