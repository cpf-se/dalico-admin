
drop trigger if exists update_user_autologin_last_login on user_autologin restrict;

create trigger update_user_autologin_last_login before update on user_autologin for each row execute procedure update_column_last_login();

