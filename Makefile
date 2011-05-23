
codeigniter	= $(shell readlink -f CodeIgniter)
application	= $(shell readlink -f application)
htdocs		= $(shell readlink -f htdocs)
srcdir		= $(shell readlink -f $(HOME)/DALICO_FILES)
dbdir		= $(shell readlink -f schema)

export confdir	= $(shell readlink -f $(application)/config)

wwwuser		= www-data
wwwgroup	= www-data

wwwfiles	= $(application)/cache $(application)/logs $(htdocs)/captcha

.PHONY: all deploy perms

all:

deploy: $(srcdir) $(dbdir)
	$(MAKE) -C $(srcdir) $@
	$(MAKE) -C $(dbdir) $@
	$(MAKE) perms

perms:
	sudo chgrp -Rc $(wwwgroup) $(wwwfiles)
	sudo chown -Rc $(wwwuser)  $(wwwfiles)
	find $(codeigniter) $(application) $(htdocs) -type d -print0 | xargs -0r sudo chmod -c 0755
	find $(codeigniter) $(application) $(htdocs) -type f -print0 | xargs -0r sudo chmod -c 0644

