INSERT INTO `user` (`id`, `username`, `password_hash`, `name`, `surname`)
VALUES
	(1, 'admin', '$2y$10$esEDa.lTxt4iwzG1n58/2Ogx74LTimpqIVOpd.DWODnTlI6xH9R6i', NULL, NULL);

	
	
INSERT INTO `role` (`id`, `role`)
VALUES
	(1, 'admin'),
	(2, 'hostess');

	
	
INSERT INTO `role_user` (`id`, `role_id`, `user_id`)
VALUES
	(1, 1, 1);

	
INSERT INTO `store` (`id`, `name`)
VALUES
	(1, 'web');

INSERT INTO `location` (`id`, `name`)
VALUES
	(1, 'web');

	
	
INSERT INTO `event` (`id`, `name`, `permalink`, `location_id`, `store_id`)
VALUES
	(1, 'web', 'xyz', 1, 1);