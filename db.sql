CREATE TABLE `course` (
  `cid` int(11) NOT NULL,
  `cname` varchar(254),
  `desc` text,
  `duration` varchar(254),
  `keywords` varchar(254)
);

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `name` varchar(254)  NOT NULL,
  `username` varchar(254)  NOT NULL,
  `password` varchar(254)  NOT NULL,
  `email` varchar(254)  NOT NULL,  
  `roleid` int(11));

CREATE TABLE `role` (
  `rid` int(11) NOT NULL,
  `rname` varchar(254)  NOT NULL
);

CREATE TABLE `user_course_status` (
  `ucid` int(11) NOT NULL,
  `uid` int(11) NOT null,
  `cid` int(11) NOT null
);

INSERT INTO `user` (`id`, `name`, `username`, `password`, `email`, `roleid`) VALUES
(1, 'Administrator', 'admin', 'admin', 'admin@lms', 1);

