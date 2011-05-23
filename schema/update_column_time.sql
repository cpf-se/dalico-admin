
create or replace function update_column_time() returns trigger as $$
begin
	NEW.time = now();
	return NEW;
end;
$$ language 'plpgsql';

