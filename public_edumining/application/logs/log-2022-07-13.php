<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2022-07-13 01:02:56 --> ""
ERROR - 2022-07-13 01:02:56 --> {"path":"login","layout":"layout_test","skin":"login","layout_dir":"..\/login","mobile_layout_dir":"","use_sidebar":"","use_mobile_sidebar":"","skin_dir":"","mobile_skin_dir":"","page_title":"\ub85c\uadf8\uc778 - {\ud648\ud398\uc774\uc9c0\uc81c\ubaa9}","meta_description":"","meta_keywords":"","meta_author":"","page_name":""}
ERROR - 2022-07-13 01:03:06 --> 404 Page Not Found: Adstxt/index
ERROR - 2022-07-13 01:03:06 --> 404 Page Not Found: App-adstxt/index
ERROR - 2022-07-13 01:46:28 --> Severity: Warning --> include(INCPATHcss/style.css): failed to open stream: No such file or directory /home/theimc/public_edumining/views/register/textomi/policy2.php 81
ERROR - 2022-07-13 01:46:28 --> Severity: Warning --> include(): Failed opening 'INCPATHcss/style.css' for inclusion (include_path='.:/usr/share/php') /home/theimc/public_edumining/views/register/textomi/policy2.php 81
ERROR - 2022-07-13 02:12:56 --> 404 Page Not Found: Owa/auth.owa
ERROR - 2022-07-13 05:08:38 --> ""
ERROR - 2022-07-13 05:08:38 --> {"path":"login","layout":"layout_test","skin":"login","layout_dir":"..\/login","mobile_layout_dir":"","use_sidebar":"","use_mobile_sidebar":"","skin_dir":"","mobile_skin_dir":"","page_title":"\ub85c\uadf8\uc778 - {\ud648\ud398\uc774\uc9c0\uc81c\ubaa9}","meta_description":"","meta_keywords":"","meta_author":"","page_name":""}
ERROR - 2022-07-13 08:03:29 --> ""
ERROR - 2022-07-13 08:03:29 --> {"path":"login","layout":"layout_test","skin":"login","layout_dir":"..\/login","mobile_layout_dir":"","use_sidebar":"","use_mobile_sidebar":"","skin_dir":"","mobile_skin_dir":"","page_title":"\ub85c\uadf8\uc778 - {\ud648\ud398\uc774\uc9c0\uc81c\ubaa9}","meta_description":"","meta_keywords":"","meta_author":"","page_name":""}
ERROR - 2022-07-13 08:03:32 --> "https:\/\/edumining.textom.co.kr\/"
ERROR - 2022-07-13 08:17:21 --> 404 Page Not Found: Nmaplowercheck1657667841/index
ERROR - 2022-07-13 08:17:22 --> 404 Page Not Found: Evox/about
ERROR - 2022-07-13 08:17:24 --> 404 Page Not Found: Sdk/index
ERROR - 2022-07-13 08:17:25 --> 404 Page Not Found: HNAP1/index
ERROR - 2022-07-13 09:41:34 --> Severity: error --> Exception: Unable to locate the model you have specified: Board_meta_model /home/theimc/public_edumining/_system/core/Loader.php 344
ERROR - 2022-07-13 10:10:32 --> Severity: error --> Exception: Unable to locate the model you have specified: Board_meta_model /home/theimc/public_edumining/_system/core/Loader.php 344
ERROR - 2022-07-13 10:10:33 --> 404 Page Not Found: Well-known/security.txt
ERROR - 2022-07-13 10:55:15 --> 404 Page Not Found: Owa/auth
ERROR - 2022-07-13 10:55:42 --> 404 Page Not Found: Owa/auth
ERROR - 2022-07-13 10:58:23 --> 404 Page Not Found: Ecp/Current
ERROR - 2022-07-13 11:13:11 --> 404 Page Not Found: Owa/auth
ERROR - 2022-07-13 11:30:31 --> 404 Page Not Found: Well-known/assetlinks.json
ERROR - 2022-07-13 13:10:02 --> 404 Page Not Found: Mgmt/tm
ERROR - 2022-07-13 13:54:44 --> 404 Page Not Found: ReportServer/index
ERROR - 2022-07-13 13:54:56 --> ""
ERROR - 2022-07-13 13:54:56 --> {"path":"login","layout":"layout_test","skin":"login","layout_dir":"..\/login","mobile_layout_dir":"","use_sidebar":"","use_mobile_sidebar":"","skin_dir":"","mobile_skin_dir":"","page_title":"\ub85c\uadf8\uc778 - {\ud648\ud398\uc774\uc9c0\uc81c\ubaa9}","meta_description":"","meta_keywords":"","meta_author":"","page_name":""}
ERROR - 2022-07-13 13:55:21 --> ""
ERROR - 2022-07-13 13:55:21 --> {"path":"login","layout":"layout_test","skin":"login","layout_dir":"..\/login","mobile_layout_dir":"","use_sidebar":"","use_mobile_sidebar":"","skin_dir":"","mobile_skin_dir":"","page_title":"\ub85c\uadf8\uc778 - {\ud648\ud398\uc774\uc9c0\uc81c\ubaa9}","meta_description":"","meta_keywords":"","meta_author":"","page_name":""}
ERROR - 2022-07-13 13:55:28 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near ')         ORDER BY EBA.notice_status ASC, EBA.create_date DESC, EBA.no DESC) AS ' at line 1 - Invalid query: SELECT RST.* FROM (   SELECT  @ROWNUM:=@ROWNUM+1 AS item_no, RST_INN.*  FROM (SELECT @ROWNUM := 0) R,       (SELECT           EBA.no AS article_no,           EBA.user_no AS user_no,           EBA.title AS title,           EBA.contents AS contents,           EBA.create_date AS create_date,           EBA.update_date AS update_date,           EBA.view_count AS view_count,           EBA.notice_status AS notice_status,           TM.mem_username AS user_name         FROM edu_board_article AS EBA         JOIN t_member AS TM ON TM.mem_id = EBA.user_no         WHERE EBA.article_type = 0 AND EBA.delete_status = 'N' AND (TM.mem_userid = '' OR EBA.user_no = )         ORDER BY EBA.notice_status ASC, EBA.create_date DESC, EBA.no DESC) AS RST_INN ) AS RST LIMIT 10 OFFSET 0
ERROR - 2022-07-13 13:55:28 --> Severity: Error --> Call to a member function result_array() on boolean /home/theimc/public_edumining/application/models/edumining/Analysis_model.php 310
ERROR - 2022-07-13 13:55:29 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near ')         ORDER BY EBA.notice_status ASC, EBA.create_date DESC, EBA.no DESC) AS ' at line 1 - Invalid query: SELECT RST.* FROM (   SELECT  @ROWNUM:=@ROWNUM+1 AS item_no, RST_INN.*  FROM (SELECT @ROWNUM := 0) R,       (SELECT           EBA.no AS article_no,           EBA.user_no AS user_no,           EBA.title AS title,           EBA.contents AS contents,           EBA.create_date AS create_date,           EBA.update_date AS update_date,           EBA.view_count AS view_count,           EBA.notice_status AS notice_status,           TM.mem_username AS user_name         FROM edu_board_article AS EBA         JOIN t_member AS TM ON TM.mem_id = EBA.user_no         WHERE EBA.article_type = 0 AND EBA.delete_status = 'N' AND (TM.mem_userid = '' OR EBA.user_no = )         ORDER BY EBA.notice_status ASC, EBA.create_date DESC, EBA.no DESC) AS RST_INN ) AS RST LIMIT 10 OFFSET 0
ERROR - 2022-07-13 13:55:29 --> Severity: Error --> Call to a member function result_array() on boolean /home/theimc/public_edumining/application/models/edumining/Analysis_model.php 310
ERROR - 2022-07-13 14:18:33 --> 404 Page Not Found: admin/Configphp/index
ERROR - 2022-07-13 14:45:01 --> 404 Page Not Found: Env/index
ERROR - 2022-07-13 14:52:00 --> 404 Page Not Found: Vendor/phpunit
ERROR - 2022-07-13 14:52:02 --> 404 Page Not Found: DS_Store/index
ERROR - 2022-07-13 16:49:35 --> 404 Page Not Found: Actuator/health
ERROR - 2022-07-13 18:23:07 --> 404 Page Not Found: _ignition/execute-solution
ERROR - 2022-07-13 18:23:10 --> 404 Page Not Found: Script/index
ERROR - 2022-07-13 18:23:11 --> ""
ERROR - 2022-07-13 18:23:11 --> {"path":"login","layout":"layout_test","skin":"login","layout_dir":"..\/login","mobile_layout_dir":"","use_sidebar":"","use_mobile_sidebar":"","skin_dir":"","mobile_skin_dir":"","page_title":"\ub85c\uadf8\uc778 - {\ud648\ud398\uc774\uc9c0\uc81c\ubaa9}","meta_description":"","meta_keywords":"","meta_author":"","page_name":""}
ERROR - 2022-07-13 18:23:12 --> 404 Page Not Found: Jenkins/login
ERROR - 2022-07-13 18:23:13 --> 404 Page Not Found: Manager/html
