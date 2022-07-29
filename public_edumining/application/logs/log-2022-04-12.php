<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2022-04-12 05:06:15 --> ""
ERROR - 2022-04-12 05:06:15 --> {"path":"login","layout":"layout_test","skin":"login","layout_dir":"..\/login","mobile_layout_dir":"","use_sidebar":"","use_mobile_sidebar":"","skin_dir":"","mobile_skin_dir":"","page_title":"\ub85c\uadf8\uc778 - {\ud648\ud398\uc774\uc9c0\uc81c\ubaa9}","meta_description":"","meta_keywords":"","meta_author":"","page_name":""}
ERROR - 2022-04-12 05:06:18 --> Severity: Warning --> include(INCPATHcss/style.css): failed to open stream: No such file or directory /home/theimc/public_edumining/views/register/textomi/policy2.php 76
ERROR - 2022-04-12 05:06:18 --> Severity: Warning --> include(): Failed opening 'INCPATHcss/style.css' for inclusion (include_path='.:/usr/share/php') /home/theimc/public_edumining/views/register/textomi/policy2.php 76
ERROR - 2022-04-12 05:07:11 --> Severity: Warning --> include(INCPATHcss/style.css): failed to open stream: No such file or directory /home/theimc/public_edumining/views/register/textomi/policy2.php 76
ERROR - 2022-04-12 05:07:11 --> Severity: Warning --> include(): Failed opening 'INCPATHcss/style.css' for inclusion (include_path='.:/usr/share/php') /home/theimc/public_edumining/views/register/textomi/policy2.php 76
ERROR - 2022-04-12 05:07:11 --> ""
ERROR - 2022-04-12 05:07:11 --> {"path":"login","layout":"layout_test","skin":"login","layout_dir":"..\/login","mobile_layout_dir":"","use_sidebar":"","use_mobile_sidebar":"","skin_dir":"","mobile_skin_dir":"","page_title":"\ub85c\uadf8\uc778 - {\ud648\ud398\uc774\uc9c0\uc81c\ubaa9}","meta_description":"","meta_keywords":"","meta_author":"","page_name":""}
ERROR - 2022-04-12 05:07:22 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near ')         ORDER BY EBA.notice_status ASC, EBA.create_date DESC, EBA.no DESC) AS ' at line 1 - Invalid query: SELECT RST.* FROM (   SELECT  @ROWNUM:=@ROWNUM+1 AS item_no, RST_INN.*  FROM (SELECT @ROWNUM := 0) R,       (SELECT           EBA.no AS article_no,           EBA.user_no AS user_no,           EBA.title AS title,           EBA.contents AS contents,           EBA.create_date AS create_date,           EBA.update_date AS update_date,           EBA.view_count AS view_count,           EBA.notice_status AS notice_status,           TM.mem_username AS user_name         FROM edu_board_article AS EBA         JOIN t_member AS TM ON TM.mem_id = EBA.user_no         WHERE EBA.article_type = 0 AND EBA.delete_status = 'N' AND (TM.mem_userid = '' OR EBA.user_no = )         ORDER BY EBA.notice_status ASC, EBA.create_date DESC, EBA.no DESC) AS RST_INN ) AS RST LIMIT 10 OFFSET 0
ERROR - 2022-04-12 05:07:23 --> ""
ERROR - 2022-04-12 05:07:23 --> {"path":"login","layout":"layout_test","skin":"login","layout_dir":"..\/login","mobile_layout_dir":"","use_sidebar":"","use_mobile_sidebar":"","skin_dir":"","mobile_skin_dir":"","page_title":"\ub85c\uadf8\uc778 - {\ud648\ud398\uc774\uc9c0\uc81c\ubaa9}","meta_description":"","meta_keywords":"","meta_author":"","page_name":""}
ERROR - 2022-04-12 05:07:24 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near ')         ORDER BY EBA.notice_status ASC, EBA.create_date DESC, EBA.no DESC) AS ' at line 1 - Invalid query: SELECT RST.* FROM (   SELECT  @ROWNUM:=@ROWNUM+1 AS item_no, RST_INN.*  FROM (SELECT @ROWNUM := 0) R,       (SELECT           EBA.no AS article_no,           EBA.user_no AS user_no,           EBA.title AS title,           EBA.contents AS contents,           EBA.create_date AS create_date,           EBA.update_date AS update_date,           EBA.view_count AS view_count,           EBA.notice_status AS notice_status,           TM.mem_username AS user_name         FROM edu_board_article AS EBA         JOIN t_member AS TM ON TM.mem_id = EBA.user_no         WHERE EBA.article_type = 0 AND EBA.delete_status = 'N' AND (TM.mem_userid = '' OR EBA.user_no = )         ORDER BY EBA.notice_status ASC, EBA.create_date DESC, EBA.no DESC) AS RST_INN ) AS RST LIMIT 10 OFFSET 0
ERROR - 2022-04-12 05:07:27 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'AND data_type = 1 AND collection_state = 0' at line 1 - Invalid query: SELECT * FROM edu_data_overview WHERE user_no =  AND data_type = 1 AND collection_state = 0
ERROR - 2022-04-12 05:08:28 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'AND data_type = 1 AND collection_state = 0' at line 1 - Invalid query: SELECT * FROM edu_data_overview WHERE user_no =  AND data_type = 1 AND collection_state = 0
ERROR - 2022-04-12 05:10:35 --> ""
ERROR - 2022-04-12 05:10:35 --> {"path":"login","layout":"layout_test","skin":"login","layout_dir":"..\/login","mobile_layout_dir":"","use_sidebar":"","use_mobile_sidebar":"","skin_dir":"","mobile_skin_dir":"","page_title":"\ub85c\uadf8\uc778 - {\ud648\ud398\uc774\uc9c0\uc81c\ubaa9}","meta_description":"","meta_keywords":"","meta_author":"","page_name":""}
ERROR - 2022-04-12 08:10:45 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'AND data_type = 1 AND collection_state = 0' at line 1 - Invalid query: SELECT * FROM edu_data_overview WHERE user_no =  AND data_type = 1 AND collection_state = 0
ERROR - 2022-04-12 08:10:47 --> ""
ERROR - 2022-04-12 08:10:47 --> {"path":"login","layout":"layout_test","skin":"login","layout_dir":"..\/login","mobile_layout_dir":"","use_sidebar":"","use_mobile_sidebar":"","skin_dir":"","mobile_skin_dir":"","page_title":"\ub85c\uadf8\uc778 - {\ud648\ud398\uc774\uc9c0\uc81c\ubaa9}","meta_description":"","meta_keywords":"","meta_author":"","page_name":""}
ERROR - 2022-04-12 08:49:42 --> ""
ERROR - 2022-04-12 08:49:42 --> {"path":"login","layout":"layout_test","skin":"login","layout_dir":"..\/login","mobile_layout_dir":"","use_sidebar":"","use_mobile_sidebar":"","skin_dir":"","mobile_skin_dir":"","page_title":"\ub85c\uadf8\uc778 - {\ud648\ud398\uc774\uc9c0\uc81c\ubaa9}","meta_description":"","meta_keywords":"","meta_author":"","page_name":""}
ERROR - 2022-04-12 08:50:05 --> "http:\/\/edumining.textom.co.kr\/"
ERROR - 2022-04-12 09:13:49 --> ""
ERROR - 2022-04-12 09:13:49 --> {"path":"login","layout":"layout_test","skin":"login","layout_dir":"..\/login","mobile_layout_dir":"","use_sidebar":"","use_mobile_sidebar":"","skin_dir":"","mobile_skin_dir":"","page_title":"\ub85c\uadf8\uc778 - {\ud648\ud398\uc774\uc9c0\uc81c\ubaa9}","meta_description":"","meta_keywords":"","meta_author":"","page_name":""}
ERROR - 2022-04-12 09:13:56 --> "http:\/\/edumining.textom.co.kr\/"
ERROR - 2022-04-12 09:19:15 --> ""
ERROR - 2022-04-12 09:19:15 --> {"path":"login","layout":"layout_test","skin":"login","layout_dir":"..\/login","mobile_layout_dir":"","use_sidebar":"","use_mobile_sidebar":"","skin_dir":"","mobile_skin_dir":"","page_title":"\ub85c\uadf8\uc778 - {\ud648\ud398\uc774\uc9c0\uc81c\ubaa9}","meta_description":"","meta_keywords":"","meta_author":"","page_name":""}
ERROR - 2022-04-12 09:19:17 --> "http:\/\/edumining.textom.co.kr\/management\/class_manage"
ERROR - 2022-04-12 09:44:58 --> ""
ERROR - 2022-04-12 09:44:58 --> {"path":"login","layout":"layout_test","skin":"login","layout_dir":"..\/login","mobile_layout_dir":"","use_sidebar":"","use_mobile_sidebar":"","skin_dir":"","mobile_skin_dir":"","page_title":"\ub85c\uadf8\uc778 - {\ud648\ud398\uc774\uc9c0\uc81c\ubaa9}","meta_description":"","meta_keywords":"","meta_author":"","page_name":""}
ERROR - 2022-04-12 09:45:01 --> "http:\/\/edumining.textom.co.kr\/"
ERROR - 2022-04-12 09:45:31 --> Severity: Warning --> fopen(): Filename cannot be empty /home/theimc/public_edumining/application/models/edumining/Data_manage_model.php 79
ERROR - 2022-04-12 09:45:35 --> Severity: Warning --> fopen(): Filename cannot be empty /home/theimc/public_edumining/application/models/edumining/Data_manage_model.php 79
ERROR - 2022-04-12 09:45:40 --> Severity: Warning --> fopen(): Filename cannot be empty /home/theimc/public_edumining/application/models/edumining/Data_manage_model.php 79
ERROR - 2022-04-12 09:45:45 --> Severity: Warning --> fopen(): Filename cannot be empty /home/theimc/public_edumining/application/models/edumining/Data_manage_model.php 79
ERROR - 2022-04-12 09:45:51 --> Severity: Warning --> fopen(): Filename cannot be empty /home/theimc/public_edumining/application/models/edumining/Data_manage_model.php 79
ERROR - 2022-04-12 09:45:56 --> Severity: Warning --> fopen(): Filename cannot be empty /home/theimc/public_edumining/application/models/edumining/Data_manage_model.php 79
ERROR - 2022-04-12 09:46:01 --> Severity: Warning --> fopen(): Filename cannot be empty /home/theimc/public_edumining/application/models/edumining/Data_manage_model.php 79
ERROR - 2022-04-12 09:46:06 --> Severity: Warning --> fopen(): Filename cannot be empty /home/theimc/public_edumining/application/models/edumining/Data_manage_model.php 79
ERROR - 2022-04-12 09:46:10 --> Severity: Warning --> fopen(): Filename cannot be empty /home/theimc/public_edumining/application/models/edumining/Data_manage_model.php 79
ERROR - 2022-04-12 09:46:15 --> Severity: Warning --> fopen(): Filename cannot be empty /home/theimc/public_edumining/application/models/edumining/Data_manage_model.php 79
ERROR - 2022-04-12 09:46:20 --> Severity: Warning --> fopen(): Filename cannot be empty /home/theimc/public_edumining/application/models/edumining/Data_manage_model.php 79
ERROR - 2022-04-12 09:46:25 --> Severity: Warning --> fopen(): Filename cannot be empty /home/theimc/public_edumining/application/models/edumining/Data_manage_model.php 79
ERROR - 2022-04-12 09:46:30 --> Severity: Warning --> fopen(): Filename cannot be empty /home/theimc/public_edumining/application/models/edumining/Data_manage_model.php 79
ERROR - 2022-04-12 09:46:35 --> Severity: Warning --> fopen(): Filename cannot be empty /home/theimc/public_edumining/application/models/edumining/Data_manage_model.php 79
ERROR - 2022-04-12 09:46:40 --> Severity: Warning --> fopen(): Filename cannot be empty /home/theimc/public_edumining/application/models/edumining/Data_manage_model.php 79
ERROR - 2022-04-12 09:46:45 --> Severity: Warning --> fopen(): Filename cannot be empty /home/theimc/public_edumining/application/models/edumining/Data_manage_model.php 79
ERROR - 2022-04-12 09:46:50 --> Severity: Warning --> fopen(): Filename cannot be empty /home/theimc/public_edumining/application/models/edumining/Data_manage_model.php 79
ERROR - 2022-04-12 09:46:55 --> Severity: Warning --> fopen(): Filename cannot be empty /home/theimc/public_edumining/application/models/edumining/Data_manage_model.php 79
ERROR - 2022-04-12 09:47:00 --> Severity: Warning --> fopen(): Filename cannot be empty /home/theimc/public_edumining/application/models/edumining/Data_manage_model.php 79
ERROR - 2022-04-12 09:47:05 --> Severity: Warning --> fopen(): Filename cannot be empty /home/theimc/public_edumining/application/models/edumining/Data_manage_model.php 79
ERROR - 2022-04-12 09:47:10 --> Severity: Warning --> fopen(): Filename cannot be empty /home/theimc/public_edumining/application/models/edumining/Data_manage_model.php 79
ERROR - 2022-04-12 09:47:15 --> Severity: Warning --> fopen(): Filename cannot be empty /home/theimc/public_edumining/application/models/edumining/Data_manage_model.php 79
ERROR - 2022-04-12 09:47:20 --> Severity: Warning --> fopen(): Filename cannot be empty /home/theimc/public_edumining/application/models/edumining/Data_manage_model.php 79
ERROR - 2022-04-12 09:47:25 --> Severity: Warning --> fopen(): Filename cannot be empty /home/theimc/public_edumining/application/models/edumining/Data_manage_model.php 79
ERROR - 2022-04-12 09:47:30 --> Severity: Warning --> fopen(): Filename cannot be empty /home/theimc/public_edumining/application/models/edumining/Data_manage_model.php 79
ERROR - 2022-04-12 09:47:35 --> Severity: Warning --> fopen(): Filename cannot be empty /home/theimc/public_edumining/application/models/edumining/Data_manage_model.php 79
ERROR - 2022-04-12 09:47:40 --> Severity: Warning --> fopen(): Filename cannot be empty /home/theimc/public_edumining/application/models/edumining/Data_manage_model.php 79
ERROR - 2022-04-12 09:47:45 --> Severity: Warning --> fopen(): Filename cannot be empty /home/theimc/public_edumining/application/models/edumining/Data_manage_model.php 79
ERROR - 2022-04-12 09:47:50 --> Severity: Warning --> fopen(): Filename cannot be empty /home/theimc/public_edumining/application/models/edumining/Data_manage_model.php 79
ERROR - 2022-04-12 09:59:22 --> ""
ERROR - 2022-04-12 09:59:22 --> {"path":"login","layout":"layout_test","skin":"login","layout_dir":"..\/login","mobile_layout_dir":"","use_sidebar":"","use_mobile_sidebar":"","skin_dir":"","mobile_skin_dir":"","page_title":"\ub85c\uadf8\uc778 - {\ud648\ud398\uc774\uc9c0\uc81c\ubaa9}","meta_description":"","meta_keywords":"","meta_author":"","page_name":""}
ERROR - 2022-04-12 09:59:25 --> ""
ERROR - 2022-04-12 09:59:25 --> {"path":"login","layout":"layout_test","skin":"login","layout_dir":"..\/login","mobile_layout_dir":"","use_sidebar":"","use_mobile_sidebar":"","skin_dir":"","mobile_skin_dir":"","page_title":"\ub85c\uadf8\uc778 - {\ud648\ud398\uc774\uc9c0\uc81c\ubaa9}","meta_description":"","meta_keywords":"","meta_author":"","page_name":""}
ERROR - 2022-04-12 09:59:31 --> "http:\/\/edumining.textom.co.kr\/"
ERROR - 2022-04-12 10:02:30 --> ""
ERROR - 2022-04-12 10:02:30 --> {"path":"login","layout":"layout_test","skin":"login","layout_dir":"..\/login","mobile_layout_dir":"","use_sidebar":"","use_mobile_sidebar":"","skin_dir":"","mobile_skin_dir":"","page_title":"\ub85c\uadf8\uc778 - {\ud648\ud398\uc774\uc9c0\uc81c\ubaa9}","meta_description":"","meta_keywords":"","meta_author":"","page_name":""}
ERROR - 2022-04-12 10:02:49 --> "http:\/\/edumining.textom.co.kr\/"
ERROR - 2022-04-12 11:30:36 --> ""
ERROR - 2022-04-12 11:30:36 --> {"path":"login","layout":"layout_test","skin":"login","layout_dir":"..\/login","mobile_layout_dir":"","use_sidebar":"","use_mobile_sidebar":"","skin_dir":"","mobile_skin_dir":"","page_title":"\ub85c\uadf8\uc778 - {\ud648\ud398\uc774\uc9c0\uc81c\ubaa9}","meta_description":"","meta_keywords":"","meta_author":"","page_name":""}
ERROR - 2022-04-12 11:30:40 --> "http:\/\/edumining.textom.co.kr\/analysis\/sub_report_list"
ERROR - 2022-04-12 11:32:52 --> ""
ERROR - 2022-04-12 11:32:52 --> {"path":"login","layout":"layout_test","skin":"login","layout_dir":"..\/login","mobile_layout_dir":"","use_sidebar":"","use_mobile_sidebar":"","skin_dir":"","mobile_skin_dir":"","page_title":"\ub85c\uadf8\uc778 - {\ud648\ud398\uc774\uc9c0\uc81c\ubaa9}","meta_description":"","meta_keywords":"","meta_author":"","page_name":""}
ERROR - 2022-04-12 11:32:55 --> "http:\/\/edumining.textom.co.kr\/"
ERROR - 2022-04-12 11:50:30 --> ""
ERROR - 2022-04-12 11:50:30 --> {"path":"login","layout":"layout_test","skin":"login","layout_dir":"..\/login","mobile_layout_dir":"","use_sidebar":"","use_mobile_sidebar":"","skin_dir":"","mobile_skin_dir":"","page_title":"\ub85c\uadf8\uc778 - {\ud648\ud398\uc774\uc9c0\uc81c\ubaa9}","meta_description":"","meta_keywords":"","meta_author":"","page_name":""}
ERROR - 2022-04-12 11:50:34 --> "http:\/\/edumining.textom.co.kr\/"
ERROR - 2022-04-12 13:14:31 --> ""
ERROR - 2022-04-12 13:14:31 --> {"path":"login","layout":"layout_test","skin":"login","layout_dir":"..\/login","mobile_layout_dir":"","use_sidebar":"","use_mobile_sidebar":"","skin_dir":"","mobile_skin_dir":"","page_title":"\ub85c\uadf8\uc778 - {\ud648\ud398\uc774\uc9c0\uc81c\ubaa9}","meta_description":"","meta_keywords":"","meta_author":"","page_name":""}
ERROR - 2022-04-12 13:14:34 --> "http:\/\/edumining.textom.co.kr\/"
ERROR - 2022-04-12 13:27:00 --> ""
ERROR - 2022-04-12 13:27:00 --> {"path":"login","layout":"layout_test","skin":"login","layout_dir":"..\/login","mobile_layout_dir":"","use_sidebar":"","use_mobile_sidebar":"","skin_dir":"","mobile_skin_dir":"","page_title":"\ub85c\uadf8\uc778 - {\ud648\ud398\uc774\uc9c0\uc81c\ubaa9}","meta_description":"","meta_keywords":"","meta_author":"","page_name":""}
ERROR - 2022-04-12 13:27:02 --> "http:\/\/edumining.textom.co.kr\/"
ERROR - 2022-04-12 13:27:05 --> 404 Page Not Found: Uploads/favicon
ERROR - 2022-04-12 13:29:57 --> ""
ERROR - 2022-04-12 13:29:57 --> {"path":"login","layout":"layout_test","skin":"login","layout_dir":"..\/login","mobile_layout_dir":"","use_sidebar":"","use_mobile_sidebar":"","skin_dir":"","mobile_skin_dir":"","page_title":"\ub85c\uadf8\uc778 - {\ud648\ud398\uc774\uc9c0\uc81c\ubaa9}","meta_description":"","meta_keywords":"","meta_author":"","page_name":""}
ERROR - 2022-04-12 13:29:59 --> "http:\/\/edumining.textom.co.kr\/"
ERROR - 2022-04-12 13:34:39 --> ""
ERROR - 2022-04-12 13:34:39 --> {"path":"login","layout":"layout_test","skin":"login","layout_dir":"..\/login","mobile_layout_dir":"","use_sidebar":"","use_mobile_sidebar":"","skin_dir":"","mobile_skin_dir":"","page_title":"\ub85c\uadf8\uc778 - {\ud648\ud398\uc774\uc9c0\uc81c\ubaa9}","meta_description":"","meta_keywords":"","meta_author":"","page_name":""}
ERROR - 2022-04-12 13:34:42 --> "http:\/\/edumining.textom.co.kr\/"
ERROR - 2022-04-12 17:17:07 --> ""
ERROR - 2022-04-12 17:17:07 --> {"path":"login","layout":"layout_test","skin":"login","layout_dir":"..\/login","mobile_layout_dir":"","use_sidebar":"","use_mobile_sidebar":"","skin_dir":"","mobile_skin_dir":"","page_title":"\ub85c\uadf8\uc778 - {\ud648\ud398\uc774\uc9c0\uc81c\ubaa9}","meta_description":"","meta_keywords":"","meta_author":"","page_name":""}
ERROR - 2022-04-12 17:17:10 --> "http:\/\/edumining.textom.co.kr\/"
ERROR - 2022-04-12 17:40:33 --> ""
ERROR - 2022-04-12 17:40:33 --> {"path":"login","layout":"layout_test","skin":"login","layout_dir":"..\/login","mobile_layout_dir":"","use_sidebar":"","use_mobile_sidebar":"","skin_dir":"","mobile_skin_dir":"","page_title":"\ub85c\uadf8\uc778 - {\ud648\ud398\uc774\uc9c0\uc81c\ubaa9}","meta_description":"","meta_keywords":"","meta_author":"","page_name":""}
ERROR - 2022-04-12 17:40:37 --> "http:\/\/edumining.textom.co.kr\/analysis\/sub_report_list"
