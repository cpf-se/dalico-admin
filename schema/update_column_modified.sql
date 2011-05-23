
create or replace function update_column_modified() returns trigger as $$
begin
	NEW.modified = now();
	return NEW;
end;
$$ language 'plpgsql';

