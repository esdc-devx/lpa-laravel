--
-- Date: 2018-05-29
-- Description: This script:
--              1) adds the lpa_admin user
--              2) adds the camunda-admin group
--              3) adds the lpa_admin user into the camunda-admin group
--              4) grants required authorizations to the camunda-admin
-- Purpose: Initialize the camunda database for lpa.
--

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


-- --------------------------------------------------------

--
-- Data for table `ACT_ID_GROUP`
--

INSERT INTO `ACT_ID_GROUP` (`ID_`, `REV_`, `NAME_`, `TYPE_`) VALUES
('camunda-admin', 1, 'camunda BPM Administrators', 'SYSTEM');

-- --------------------------------------------------------

--
-- Data for table `ACT_ID_USER`
--

INSERT INTO `ACT_ID_USER` (`ID_`, `REV_`, `FIRST_`, `LAST_`, `EMAIL_`, `PWD_`, `SALT_`, `LOCK_EXP_TIME_`, `ATTEMPTS_`, `PICTURE_ID_`) VALUES
('LPA_ADMIN', 1, 'LPA', 'Admin', 'csps.applicationdevelopment-developpementdapplications.efpc@canada.ca', '{SHA-512}TS8to8zTZtLna/7VLtsm2aI/tv89bN29JRdO+TyDOKvm0RVSBctRZJdvJmjlRW5/4+qoctr87GehOMVSJDQH3A==', 'Ar8jlIDyR8BIzzytOb4vcQ==', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Data for table `ACT_ID_MEMBERSHIP`
--

INSERT INTO `ACT_ID_MEMBERSHIP` (`USER_ID_`, `GROUP_ID_`) VALUES
('LPA_ADMIN', 'camunda-admin');


-- --------------------------------------------------------

--
-- Data for table `ACT_RU_AUTHORIZATION`
--

INSERT INTO `ACT_RU_AUTHORIZATION` (`ID_`, `REV_`, `TYPE_`, `GROUP_ID_`, `USER_ID_`, `RESOURCE_TYPE_`, `RESOURCE_ID_`, `PERMS_`) VALUES
('6ec7687b-3298-11e8-88e6-34e6d7457435', 1, 1, NULL, 'LPA_ADMIN', 1, 'LPA_ADMIN', 2147483647),
('6eca008c-3298-11e8-88e6-34e6d7457435', 1, 1, 'camunda-admin', NULL, 2, 'camunda-admin', 2),
('6ecd82fd-3298-11e8-88e6-34e6d7457435', 1, 1, 'camunda-admin', NULL, 0, '*', 2147483647),
('6ecf57be-3298-11e8-88e6-34e6d7457435', 1, 1, 'camunda-admin', NULL, 1, '*', 2147483647),
('6ed1538f-3298-11e8-88e6-34e6d7457435', 1, 1, 'camunda-admin', NULL, 2, '*', 2147483647),
('6ed34f60-3298-11e8-88e6-34e6d7457435', 1, 1, 'camunda-admin', NULL, 3, '*', 2147483647),
('6eda0621-3298-11e8-88e6-34e6d7457435', 1, 1, 'camunda-admin', NULL, 4, '*', 2147483647),
('6edc01f2-3298-11e8-88e6-34e6d7457435', 1, 1, 'camunda-admin', NULL, 5, '*', 2147483647),
('6eddfdc3-3298-11e8-88e6-34e6d7457435', 1, 1, 'camunda-admin', NULL, 6, '*', 2147483647),
('6edfd284-3298-11e8-88e6-34e6d7457435', 1, 1, 'camunda-admin', NULL, 7, '*', 2147483647),
('6ee1a745-3298-11e8-88e6-34e6d7457435', 1, 1, 'camunda-admin', NULL, 8, '*', 2147483647),
('6ee3f136-3298-11e8-88e6-34e6d7457435', 1, 1, 'camunda-admin', NULL, 9, '*', 2147483647),
('6ee59ee7-3298-11e8-88e6-34e6d7457435', 1, 1, 'camunda-admin', NULL, 10, '*', 2147483647),
('6ee773a8-3298-11e8-88e6-34e6d7457435', 1, 1, 'camunda-admin', NULL, 11, '*', 2147483647),
('6ee94869-3298-11e8-88e6-34e6d7457435', 1, 1, 'camunda-admin', NULL, 12, '*', 2147483647),
('6eeaf61a-3298-11e8-88e6-34e6d7457435', 1, 1, 'camunda-admin', NULL, 13, '*', 2147483647),
('6eeca3cb-3298-11e8-88e6-34e6d7457435', 1, 1, 'camunda-admin', NULL, 14, '*', 2147483647),
('8c6669e3-6377-11e8-aca8-34e6d7457435', 1, 1, 'camunda-admin', NULL, 15, '*', 2147483647),
('8c683ea4-6377-11e8-aca8-34e6d7457435', 1, 1, 'camunda-admin', NULL, 16, '*', 2147483647);

-- --------------------------------------------------------

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
