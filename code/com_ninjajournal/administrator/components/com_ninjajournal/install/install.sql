
-- --------------------------------------------------------

--
-- Table structure for table `jos_ninjajournal_logs`
--

CREATE TABLE IF NOT EXISTS `#__ninjajournal_logs` (
  `ninjajournal_log_id` SERIAL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `ninjajournal_project_id` bigint(20) UNSIGNED NOT NULL,
  `ninjajournal_task_id` bigint(20) UNSIGNED NOT NULL,
  `description` text NOT NULL COMMENT '@Filter("html")',
  `duration` int(11) NOT NULL,
  `created_on` datetime NOT NULL default '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL default 0,
  `modified_on` datetime NOT NULL default '0000-00-00 00:00:00',
  `modified_by` int(11) NOT NULL default 0,
  KEY `user_id` (`user_id`),
  KEY `ninjajournal_project_id` (`ninjajournal_project_id`),
  KEY `ninjajournal_task_id` (`ninjajournal_task_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `jos_ninjajournal_projects`
--

CREATE TABLE IF NOT EXISTS `#__ninjajournal_projects` (
  `ninjajournal_project_id` SERIAL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL COMMENT '@Filter("html")',
  `state` tinyint(4) NOT NULL
) TYPE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `jos_ninjajournal_tasks`
--

CREATE TABLE IF NOT EXISTS `#__ninjajournal_tasks` (
  `ninjajournal_task_id` SERIAL,
  `ninjajournal_project_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL COMMENT '@Filter("html")',
  `state` tinyint(4) NOT NULL,
  `ninjajournal_type_id` bigint(20) UNSIGNED NOT NULL,
  KEY `ninjajournal_project_id` (`ninjajournal_project_id`),
  KEY `ninjajournal_type_id` (`ninjajournal_type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `jos_ninjajournal_todos`
--

CREATE TABLE IF NOT EXISTS `#__ninjajournal_todos` (
  `ninjajournal_todo_id` SERIAL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `description` text NOT NULL COMMENT '@Filter("html")',
  `state` tinyint(4) NOT NULL,
  `created_on` datetime NOT NULL default '0000-00-00 00:00:00', 
  `created_by` int(11) NOT NULL default 0, 
  `modified_on` datetime NOT NULL default '0000-00-00 00:00:00', 
  `modified_by` int(11) NOT NULL default 0, 
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `jos_ninjajournal_types`
--

CREATE TABLE IF NOT EXISTS `#__ninjajournal_types` (
  `ninjajournal_type_id` SERIAL,
  `title` varchar(255) NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT 1
) TYPE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `jos_ninjajournal_status`
--

CREATE TABLE IF NOT EXISTS `#__ninjajournal_status` (
  `ninjajournal_status_id` SERIAL,
  `message` text NOT NULL COMMENT '@Filter("html")',
  `on` datetime NOT NULL default '0000-00-00 00:00:00',
  `by` bigint(20) UNSIGNED NOT NULL,
  UNIQUE KEY (`by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;