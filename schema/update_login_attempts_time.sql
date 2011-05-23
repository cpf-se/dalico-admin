
drop trigger if exists update_login_attempts_time on login_attempts restrict;

create trigger update_login_attempts_time before update on login_attempts for each row execute procedure update_column_time();

