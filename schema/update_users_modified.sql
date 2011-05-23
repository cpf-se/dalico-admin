
drop trigger if exists update_users_modified on users restrict;

create trigger update_users_modified before update on users for each row execute procedure update_column_modified();

