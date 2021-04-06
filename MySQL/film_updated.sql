ALTER TABLE film
	ADD COLUMN trailers BOOLEAN NOT NULL AFTER rating,
    ADD COLUMN deleted_scenes BOOLEAN NOT NULL AFTER trailers,
    ADD COLUMN behind_scenes BOOLEAN NOT NULL AFTER deleted_scenes,
    ADD COLUMN commentaries BOOLEAN NOT NULL AFTER behind_scenes;

UPDATE film
	SET trailers = TRUE
    WHERE special_features REGEXP 'trailer';
UPDATE film
	SET deleted_scenes = TRUE
    WHERE special_features REGEXP 'deleted';
UPDATE film
	SET behind_scenes = TRUE
    WHERE special_features REGEXP 'behind';
UPDATE film
	SET commentaries = TRUE
    WHERE special_features REGEXP 'commentaries';
    
ALTER TABLE film DROP COLUMN special_features;
ALTER TABLE film DROP COLUMN original_language_id;