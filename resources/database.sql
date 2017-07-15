CREATE TABLE IF NOT EXISTS `Users` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Email` varchar(255) NOT NULL,
  `Name` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO `Users` (`ID`, `Email`, `Name`) VALUES
(1, 'airmail@code-pilots.com', 'The first user');
INSERT INTO test_ps.Users (Email, Name) VALUES ('airmail@code-pilots.com', 'The first user');
INSERT INTO test_ps.Users (Email, Name) VALUES ('email@email.com', 'The second user');
INSERT INTO test_ps.Users (Email, Name) VALUES ('pushkin@poem.ru', 'Alex');
INSERT INTO test_ps.Users (Email, Name) VALUES ('me@me.com', 'Cat');
INSERT INTO test_ps.Users (Email, Name) VALUES ('dog@animals.com', 'Bob');

CREATE TABLE IF NOT EXISTS `Session` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) NOT NULL,
  `TimeOfEvent` datetime NOT NULL,
  `Description` text NOT NULL,
  `SpeakersLimit` int(10) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
-- SpeakersLimit максимальное кол-во спикеров

INSERT INTO test_ps.Session (Name, TimeOfEvent, Description, SpeakersLimit) VALUES ('Story 1', '2017-07-21 17:00:00', 'Story 1 description', 2);
INSERT INTO test_ps.Session (Name, TimeOfEvent, Description, SpeakersLimit) VALUES ('Dinner', '2017-07-22 19:00:00', 'Family dinner', 4);
INSERT INTO test_ps.Session (Name, TimeOfEvent, Description, SpeakersLimit) VALUES ('BreakFast', '2017-07-23 16:42:19', 'Family breakfast', 3);


CREATE TABLE IF NOT EXISTS `SessionSpeakers` (
  `UserId` int(11) NOT NULL,
  `SessionId` int(11) NOT NULL,
PRIMARY KEY (`UserId`, `SessionId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO test_ps.SessionSpeakers (UserId, SessionId) VALUES (1, 1);
INSERT INTO test_ps.SessionSpeakers (UserId, SessionId) VALUES (1, 2);
INSERT INTO test_ps.SessionSpeakers (UserId, SessionId) VALUES (1, 3);
INSERT INTO test_ps.SessionSpeakers (UserId, SessionId) VALUES (2, 1);
INSERT INTO test_ps.SessionSpeakers (UserId, SessionId) VALUES (2, 2);
INSERT INTO test_ps.SessionSpeakers (UserId, SessionId) VALUES (2, 3);
INSERT INTO test_ps.SessionSpeakers (UserId, SessionId) VALUES (3, 2);
INSERT INTO test_ps.SessionSpeakers (UserId, SessionId) VALUES (3, 3);
