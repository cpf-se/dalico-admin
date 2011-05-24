
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
	./bootstrap.sh

#=======================================================================

.ci_sessions.done: ci_sessions.sql

.login_attempts.done: login_attempts.sql

.plpgsql.done: plpgsql.sql

.unix_timestamp.done: unix_timestamp.sql

.update_column_last_login.done: update_column_last_login.sql .plpgsql.done

.update_column_modified.done: update_column_modified.sql .plpgsql.done

.update_column_time.done: update_column_time.sql .plpgsql.done

.update_login_attempts_time.done: update_login_attempts_time.sql .update_column_time.done .login_attempts.done

.update_user_autologin_last_login.done: update_user_autologin_last_login.sql .update_column_last_login.done .user_autologin.done

.update_users_modified.done: update_users_modified.sql .update_column_modified.done .users.done

.user_autologin.done: user_autologin.sql

.user_profiles.done: user_profiles.sql .users.done

.users.done: users.sql

