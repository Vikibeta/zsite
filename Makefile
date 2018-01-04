VERSION=$(shell head -n 1 VERSION)

all: zip

trunk:
	git archive --format=zip --output=code.zip master
	rm -rf /tmp/chanzhirelease
	mkdir /tmp/chanzhirelease
	mv code.zip /tmp/chanzhirelease
	cd /tmp/chanzhirelease; unzip code.zip; rm code.zip; make zip

clean:
	rm -fr chanzhieps
	rm -fr *.zip
zip:
	mkdir chanzhieps
	cp -fv VERSION chanzhieps/
	cp -frv system chanzhieps/
	rm -fr chanzhieps/system/config/my.php
	cp -frv www chanzhieps && rm -fr chanzhieps/www/data/* && mkdir -p chanzhieps/www/data/upload/
	mkdir -p chanzhieps/www/data/css/
	mkdir -p chanzhieps/www/data/slides
	mkdir -p chanzhieps/www/data/source
	touch chanzhieps/www/robots.txt && rm chanzhieps/www/robots.txt && touch chanzhieps/www/robots.txt  && chmod 777 chanzhieps/www/robots.txt
	rm -frv chanzhieps/system/tmp
	mkdir -p chanzhieps/system/module/package/ext/
	mkdir -p chanzhieps/system/module/ui/theme/
	mkdir -p chanzhieps/system/tmp/cache/
	mkdir -p chanzhieps/system/tmp/log/
	mkdir -p chanzhieps/system/tmp/model/
	mkdir -p chanzhieps/system/tmp/backup/
	mkdir -p chanzhieps/system/tmp/package/
	mkdir -p chanzhieps/system/tmp/theme/
	mkdir -p chanzhieps/system/tmp/template/
	mkdir -p chanzhieps/system/tmp/effect/
	mkdir -p chanzhieps/system/tmp/fonts/
	# combine js and css files.
	mkdir -pv chanzhieps/system/build/ && cp system/build/minifyfront.php chanzhieps/system/build/
	cd chanzhieps/system/build/ && php ./minifyfront.php && php ./createcustomercss.php
	rm -frv chanzhieps/system/build
	# delete the unused files.
	find chanzhieps -name '.git*' |xargs rm -frv
	find chanzhieps -name '.svn*' |xargs rm -frv
	find chanzhieps -name tests |xargs rm -frv
	# create index.html of each folder.
	for path in `find chanzhieps/ -type d`; do touch "$$path/index.html"; done	
	rm chanzhieps/www/index.html
	# add header code to every php file.
	sed -i '1i\{if(!defined("RUN_MODE"))} {!die()} {/if}' `find chanzhieps/system/template/ -name '*.html.php'`
	sed -i '1i\<?php if(!defined("RUN_MODE")) die();?>' `find chanzhieps/system/module/ -name '*.php'`
	# change mode.
	chmod 777 -R chanzhieps/system/template
	chmod 777 -R chanzhieps/system/tmp/
	chmod 777 -R chanzhieps/www/data
	chmod 777 -R chanzhieps/system/config
	chmod 777 chanzhieps/system/module
	chmod 777 chanzhieps/system/module/package/ext
	chmod 777 chanzhieps/system/module/ui/theme
	chmod a+rx chanzhieps/system/bin/*
	#find chanzhieps/ -name ext |xargs chmod 777 -R
	# zip it.
	zip -r -9 chanzhiEPS.$(VERSION).zip chanzhieps
	rm -fr chanzhieps
