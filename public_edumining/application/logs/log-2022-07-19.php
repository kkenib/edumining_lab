<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2022-07-19 02:58:59 --> Severity: Warning --> include(INCPATHcss/style.css): failed to open stream: No such file or directory /home/theimc/public_edumining/views/register/textomi/policy2.php 81
ERROR - 2022-07-19 02:58:59 --> Severity: Warning --> include(): Failed opening 'INCPATHcss/style.css' for inclusion (include_path='.:/usr/share/php') /home/theimc/public_edumining/views/register/textomi/policy2.php 81
ERROR - 2022-07-19 03:20:00 --> 404 Page Not Found: admin/Configphp/index
ERROR - 2022-07-19 05:00:10 --> 404 Page Not Found: Nmaplowercheck1658174410/index
ERROR - 2022-07-19 05:00:15 --> 404 Page Not Found: HNAP1/index
ERROR - 2022-07-19 05:00:18 --> 404 Page Not Found: Evox/about
ERROR - 2022-07-19 05:00:43 --> 404 Page Not Found: Sdk/index
ERROR - 2022-07-19 07:47:27 --> Severity: error --> Exception: Unable to locate the model you have specified: Board_meta_model /home/theimc/public_edumining/_system/core/Loader.php 344
ERROR - 2022-07-19 08:52:23 --> Severity: error --> Exception: Unable to locate the model you have specified: Board_meta_model /home/theimc/public_edumining/_system/core/Loader.php 344
ERROR - 2022-07-19 10:01:19 --> 404 Page Not Found: Well-known/assetlinks.json
ERROR - 2022-07-19 10:38:42 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'AND data_type = 1 AND collection_state = 0' at line 1 - Invalid query: SELECT * FROM edu_data_overview WHERE user_no =  AND data_type = 1 AND collection_state = 0
ERROR - 2022-07-19 10:38:42 --> Severity: Error --> Call to a member function result_array() on boolean /home/theimc/public_edumining/application/models/edumining/Data_manage_model.php 118
ERROR - 2022-07-19 10:39:06 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near ')         ORDER BY EBA.notice_status ASC, EBA.create_date DESC, EBA.no DESC) AS ' at line 1 - Invalid query: SELECT RST.* FROM (   SELECT  @ROWNUM:=@ROWNUM+1 AS item_no, RST_INN.*  FROM (SELECT @ROWNUM := 0) R,       (SELECT           EBA.no AS article_no,           EBA.user_no AS user_no,           EBA.title AS title,           EBA.contents AS contents,           EBA.create_date AS create_date,           EBA.update_date AS update_date,           EBA.view_count AS view_count,           EBA.notice_status AS notice_status,           TM.mem_username AS user_name         FROM edu_board_article AS EBA         JOIN t_member AS TM ON TM.mem_id = EBA.user_no         WHERE EBA.article_type = 0 AND EBA.delete_status = 'N' AND (TM.mem_userid = '' OR EBA.user_no = )         ORDER BY EBA.notice_status ASC, EBA.create_date DESC, EBA.no DESC) AS RST_INN ) AS RST LIMIT 10 OFFSET 0
ERROR - 2022-07-19 10:39:06 --> Severity: Error --> Call to a member function result_array() on boolean /home/theimc/public_edumining/application/models/edumining/Analysis_model.php 310
ERROR - 2022-07-19 10:39:16 --> ""
ERROR - 2022-07-19 10:39:16 --> {"path":"login","layout":"layout_test","skin":"login","layout_dir":"..\/login","mobile_layout_dir":"","use_sidebar":"","use_mobile_sidebar":"","skin_dir":"","mobile_skin_dir":"","page_title":"\ub85c\uadf8\uc778 - {\ud648\ud398\uc774\uc9c0\uc81c\ubaa9}","meta_description":"","meta_keywords":"","meta_author":"","page_name":""}
ERROR - 2022-07-19 10:41:04 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near ')         ORDER BY EBA.notice_status ASC, EBA.create_date DESC, EBA.no DESC) AS ' at line 1 - Invalid query: SELECT RST.* FROM (   SELECT  @ROWNUM:=@ROWNUM+1 AS item_no, RST_INN.*  FROM (SELECT @ROWNUM := 0) R,       (SELECT           EBA.no AS article_no,           EBA.user_no AS user_no,           EBA.title AS title,           EBA.contents AS contents,           EBA.create_date AS create_date,           EBA.update_date AS update_date,           EBA.view_count AS view_count,           EBA.notice_status AS notice_status,           TM.mem_username AS user_name         FROM edu_board_article AS EBA         JOIN t_member AS TM ON TM.mem_id = EBA.user_no         WHERE EBA.article_type = 0 AND EBA.delete_status = 'N' AND (TM.mem_userid = '' OR EBA.user_no = )         ORDER BY EBA.notice_status ASC, EBA.create_date DESC, EBA.no DESC) AS RST_INN ) AS RST LIMIT 10 OFFSET 0
ERROR - 2022-07-19 10:41:04 --> Severity: Error --> Call to a member function result_array() on boolean /home/theimc/public_edumining/application/models/edumining/Analysis_model.php 310
ERROR - 2022-07-19 10:53:56 --> 404 Page Not Found: Owa/auth
ERROR - 2022-07-19 10:55:23 --> 404 Page Not Found: Owa/auth
ERROR - 2022-07-19 10:55:34 --> 404 Page Not Found: Ecp/Current
ERROR - 2022-07-19 11:16:29 --> 404 Page Not Found: Owa/auth
ERROR - 2022-07-19 14:36:06 --> 404 Page Not Found: Remote/fgt_lang
ERROR - 2022-07-19 19:12:18 --> 404 Page Not Found: Aws/credentials
ERROR - 2022-07-19 21:21:42 --> 404 Page Not Found: Vendor/phpunit
ERROR - 2022-07-19 21:21:44 --> 404 Page Not Found: DS_Store/index
ERROR - 2022-07-19 23:39:29 --> 404 Page Not Found: Remote/fgt_lang
