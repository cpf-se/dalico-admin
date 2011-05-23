
DALICO		= psql -U dalico -h localhost -Atwd dalico
PGINLINE	= $(DALICO) -c
PGCOMMAND	= $(DALICO) -f

tables		= ci_sessions users login_attempts
tables_done	= $(addsuffix .done,$(tables))

.PHONY: all deploy perms reset

all:

deploy: $(tables_done)

%.done: %.sql
	$(PGCOMMAND) $< 2>&1 | tee $@

perms:

reset:

