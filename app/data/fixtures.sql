INSERT INTO `user` (`id`, `username`, `password_hash`, `name`, `surname`)
VALUES
	(1, 'admin', '$2y$10$esEDa.lTxt4iwzG1n58/2Ogx74LTimpqIVOpd.DWODnTlI6xH9R6i', NULL, NULL),
	(2, 'hostess', '$2y$10$esEDa.lTxt4iwzG1n58/2Ogx74LTimpqIVOpd.DWODnTlI6xH9R6i', NULL, NULL);

	
	
INSERT INTO `role` (`id`, `role`)
VALUES
	(1, 'admin'),
	(2, 'hostess');


	
INSERT INTO `role_user` (`id`, `role_id`, `user_id`)
VALUES
	(1, 1, 1),
	(2, 2, 2);

	
	
INSERT INTO `store` (`id`, `name`)
VALUES
	(1, 'web'),
	(2, 'test store');

	
	
INSERT INTO `location` (`id`, `name`)
VALUES
	(1, 'web'),
	(2, 'test location');

	
	
INSERT INTO `event` (`id`, `name`, `permalink`, `location_id`, `store_id`)
VALUES
	(1, 'web', 'xyz', 1, 1),
	(2, 'test event', 'xyz', 2, 2);
	

INSERT INTO `event_user` (`id`, `event_id`, `user_id`)
VALUES
	(1, 2, 2);