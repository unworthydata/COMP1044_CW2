CREATE TABLE staff_store (
	store_id INTEGER,
	manager_staff_id INTEGER,
	PRIMARY KEY (store_id, manager_staff_id),
    
	CONSTRAINT store_staff_store_store_id
		FOREIGN KEY (store_id) REFERENCES store(store_id)
		ON UPDATE CASCADE
		ON DELETE RESTRICT,
        
	CONSTRAINT store_staff_staff_manager_staff_id
		FOREIGN KEY (manager_staff_id) REFERENCES staff(staff_id)
		ON UPDATE CASCADE
        	ON DELETE RESTRICT
);

INSERT INTO staff_store
	SELECT
		staff.store_id, 
		store.manager_staff_id
		FROM staff
	JOIN store USING (store_id);
	
ALTER TABLE store DROP COLUMN manager_staff_id;
ALTER TABLE staff DROP COLUMN store_id;
