
DALICO		= psql -U dalico -h localhost -Atwd dalico
PGINLINE	= $(DALICO) -c
PGCOMMAND	= $(DALICO) -f

sql		= $(wildcard *.sql)
items		= $(basename $(sql))
items_done	= $(addprefix .,$(addsuffix .done,$(items)))

.PHONY: all deploy perms reset

all:

deploy: $(items_done)

.%.done: %.sql
	$(PGCOMMAND) $< 2>&1 | tee $@

perms:

reset:

#=======================================================================

.ci_sessions.done:

.login_attempts.done:

.users.done:

.plpgsql.done:

.update_column_last_login.done .update_column_modified.done .update_column_time.done: .plpgsql.done

.update_login_attempts_time.done: .update_column_time.done .login_attempts.done

.update_user_autologin_last_login.done: .update_column_last_login.done .user_autologin.done

.update_users_modified.done: .update_column_modified.done .users.done

