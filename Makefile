install:
	@./bin/install.sh
	
install-selenium:
	@./bin/provisioners/selenium/installer-selenium-server.sh		
	
phing-level-one:
	@./bin/phing-level-one.sh
	
phing-level-two:
	@./bin/phing-level-two.sh	

test-level-one:
	@./bin/test-level-one.sh

test-level-two:
	@./bin/test-level-two.sh

analyze:
	@./bin/analyze.sh
