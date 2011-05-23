
create or replace function update_column_last_login() returns trigger as $$
begin
	NEW.last_login = now();
	return NEW;
end;
$$ language 'plpgsql';

