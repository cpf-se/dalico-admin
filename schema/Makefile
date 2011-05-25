
DALICO		= psql -U dalico -h localhost -Atwd dalico
PGINLINE	= $(DALICO) -c
PGCOMMAND	= $(DALICO) -f

sql		= $(wildcard *.sql)
items		= $(basename $(sql))
items_done	= $(addprefix .,$(addsuffix .done,$(items)))

srcdir		= $(shell readlink -f $(HOME)/DALICO_FILES)

.PHONY: all deploy perms reset

all:

deploy: $(items_done)

.%.done: %.sql
	$(PGCOMMAND) $< 2>&1 | tee $@

perms:

reset:
	./bootstrap.sh

#=======================================================================

dalby_data.sql: $(srcdir)/dalby_data.sql
	cp $< $@

.answers.done: answers.sql

.ci_sessions.done: ci_sessions.sql

.dalby_data.done: dalby_data.sql .dalby_schema.done

.dalby_schema.done: dalby_schema.sql .users.done .surveys.done .questions_answers.done

.login_attempts.done: login_attempts.sql

.plpgsql.done: plpgsql.sql

.questions.done: questions.sql

.questions_answers.done: questions_answers.sql .questions.done .answers.done

.surveys.done: surveys.sql

.surveys_questions.done: surveys_questions.sql .surveys.done .questions.done

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

