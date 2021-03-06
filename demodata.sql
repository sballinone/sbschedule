
INSERT INTO appointment (appointmentid, userid, title, address, apptDate, created, updated, response, max, enabled) VALUES
(1, 1, 'Gormet', 'Restaurant Zirbelstube', '2020-09-29 12:30:00', '2020-09-26 00:32:15', NULL, NULL, NULL, 0),
(2, 1, 'Spargelbesen', 'Besen Fellbach', '2020-09-23 12:30:00', '2020-09-22 00:32:15', NULL, NULL, NULL, 1),
(3, 1, 'Spargelbesen', 'Besen Fellbach', '2020-10-22 12:30:00', '2020-09-22 00:32:15', NULL, NULL, 5, 1),
(4, 1, 'Schweinshaxe', 'Brauereigasthof Karlsbrauerei', '2020-10-23 12:30:00', '2020-09-22 00:32:15', NULL, '2020-09-30 03:36:59', NULL, 1),
(5, 1, 'Sushi', 'Sushi Le', '2020-10-06 12:30:00', '2020-09-22 00:32:15', NULL, '2020-09-26 00:37:28', NULL, 1);

INSERT INTO responses (registrationid, userid, appointmentid, response, response, responsed, changed) VALUES
(1, 1, 4, -1, 2, '2020-09-26 02:43:42', NULL),
(2, 1, 5, 1, 2, '2020-09-26 02:43:42', NULL);

INSERT INTO users (userid, firstname, lastname, email, password, lang, active, admin) VALUES
(1, 'Demo', 'User', 'demo@user.com', '$2y$10$XvdiBhRMZaWN0s8k5ErbZOeSd7.RimTmBsCVY5RhmYpTaHJ/9gERu', 'de', 1, 1);
