<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2022-05-28 01:19:39 --> 404 Page Not Found: Git/config
ERROR - 2022-05-28 02:08:58 --> 404 Page Not Found: Atomxml/index
ERROR - 2022-05-28 02:34:38 --> Severity: error --> Exception: Unable to locate the model you have specified: Board_meta_model /home/theimc/public_edumining/_system/core/Loader.php 344
ERROR - 2022-05-28 02:48:14 --> 404 Page Not Found: admin/Configphp/index
ERROR - 2022-05-28 05:31:58 --> Severity: Warning --> include(INCPATHcss/style.css): failed to open stream: No such file or directory /home/theimc/public_edumining/views/register/textomi/policy2.php 81
ERROR - 2022-05-28 05:31:58 --> Severity: Warning --> include(): Failed opening 'INCPATHcss/style.css' for inclusion (include_path='.:/usr/share/php') /home/theimc/public_edumining/views/register/textomi/policy2.php 81
ERROR - 2022-05-28 09:09:29 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near ')         ORDER BY EBA.notice_status ASC, EBA.create_date DESC, EBA.no DESC) AS ' at line 1 - Invalid query: SELECT RST.* FROM (   SELECT  @ROWNUM:=@ROWNUM+1 AS item_no, RST_INN.*  FROM (SELECT @ROWNUM := 0) R,       (SELECT           EBA.no AS article_no,           EBA.user_no AS user_no,           EBA.title AS title,           EBA.contents AS contents,           EBA.create_date AS create_date,           EBA.update_date AS update_date,           EBA.view_count AS view_count,           EBA.notice_status AS notice_status,           TM.mem_username AS user_name         FROM edu_board_article AS EBA         JOIN t_member AS TM ON TM.mem_id = EBA.user_no         WHERE EBA.article_type = 0 AND EBA.delete_status = 'N' AND (TM.mem_userid = '' OR EBA.user_no = )         ORDER BY EBA.notice_status ASC, EBA.create_date DESC, EBA.no DESC) AS RST_INN ) AS RST LIMIT 10 OFFSET 0
ERROR - 2022-05-28 09:09:29 --> Severity: Error --> Call to a member function result_array() on boolean /home/theimc/public_edumining/application/models/edumining/Analysis_model.php 310
ERROR - 2022-05-28 10:08:36 --> 404 Page Not Found: Env/index
ERROR - 2022-05-28 10:08:36 --> 404 Page Not Found: admin/Env/index
ERROR - 2022-05-28 10:08:36 --> 404 Page Not Found: Admin-app/.env
ERROR - 2022-05-28 10:08:36 --> 404 Page Not Found: Api/.env
ERROR - 2022-05-28 10:08:37 --> 404 Page Not Found: Back/.env
ERROR - 2022-05-28 10:08:37 --> 404 Page Not Found: Backend/.env
ERROR - 2022-05-28 10:08:37 --> 404 Page Not Found: Cp/.env
ERROR - 2022-05-28 10:08:37 --> 404 Page Not Found: Development/.env
ERROR - 2022-05-28 10:08:37 --> 404 Page Not Found: Docker/.env
ERROR - 2022-05-28 10:08:38 --> 404 Page Not Found: Local/.env
ERROR - 2022-05-28 10:08:38 --> 404 Page Not Found: Private/.env
ERROR - 2022-05-28 10:08:38 --> 404 Page Not Found: Rest/.env
ERROR - 2022-05-28 10:08:38 --> 404 Page Not Found: Shared/.env
ERROR - 2022-05-28 10:08:38 --> 404 Page Not Found: Laravel/.env
ERROR - 2022-05-28 10:08:39 --> 404 Page Not Found: System/.env
ERROR - 2022-05-28 10:08:39 --> 404 Page Not Found: Sources/.env
ERROR - 2022-05-28 10:08:39 --> 404 Page Not Found: Public/.env
ERROR - 2022-05-28 10:08:39 --> 404 Page Not Found: V1/.env
ERROR - 2022-05-28 10:08:39 --> 404 Page Not Found: App/.env
ERROR - 2022-05-28 10:08:40 --> 404 Page Not Found: Config/.env
ERROR - 2022-05-28 10:08:40 --> 404 Page Not Found: Core/.env
ERROR - 2022-05-28 10:08:40 --> 404 Page Not Found: Apps/.env
ERROR - 2022-05-28 10:08:40 --> 404 Page Not Found: Lib/.env
ERROR - 2022-05-28 10:08:40 --> 404 Page Not Found: Cron/.env
ERROR - 2022-05-28 10:08:41 --> 404 Page Not Found: Database/.env
ERROR - 2022-05-28 10:08:41 --> 404 Page Not Found: Uploads/.env
ERROR - 2022-05-28 10:08:41 --> 404 Page Not Found: Site/.env
ERROR - 2022-05-28 10:08:41 --> 404 Page Not Found: Web/.env
ERROR - 2022-05-28 10:08:41 --> 404 Page Not Found: Administrator/.env
ERROR - 2022-05-28 10:25:36 --> 404 Page Not Found: Owa/auth
ERROR - 2022-05-28 10:25:39 --> 404 Page Not Found: Owa/auth
ERROR - 2022-05-28 10:27:52 --> 404 Page Not Found: Ecp/Current
ERROR - 2022-05-28 10:44:57 --> 404 Page Not Found: Owa/auth
ERROR - 2022-05-28 11:19:37 --> 404 Page Not Found: Level/15
ERROR - 2022-05-28 13:30:46 --> 404 Page Not Found: Actuator/health
ERROR - 2022-05-28 15:23:26 --> 404 Page Not Found: Aws/credentials
ERROR - 2022-05-28 15:34:15 --> Severity: Warning --> include(INCPATHcss/style.css): failed to open stream: No such file or directory /home/theimc/public_edumining/views/register/textomi/policy2.php 81
ERROR - 2022-05-28 15:34:15 --> Severity: Warning --> include(): Failed opening 'INCPATHcss/style.css' for inclusion (include_path='.:/usr/share/php') /home/theimc/public_edumining/views/register/textomi/policy2.php 81
ERROR - 2022-05-28 17:19:17 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'AND data_type = 1 AND collection_state = 0' at line 1 - Invalid query: SELECT * FROM edu_data_overview WHERE user_no =  AND data_type = 1 AND collection_state = 0
ERROR - 2022-05-28 17:19:17 --> Severity: Error --> Call to a member function result_array() on boolean /home/theimc/public_edumining/application/models/edumining/Data_manage_model.php 118
ERROR - 2022-05-28 17:41:22 --> 404 Page Not Found: Well-known/assetlinks.json
ERROR - 2022-05-28 17:47:52 --> ""
ERROR - 2022-05-28 17:47:52 --> {"path":"login","layout":"layout_test","skin":"login","layout_dir":"..\/login","mobile_layout_dir":"","use_sidebar":"","use_mobile_sidebar":"","skin_dir":"","mobile_skin_dir":"","page_title":"\ub85c\uadf8\uc778 - {\ud648\ud398\uc774\uc9c0\uc81c\ubaa9}","meta_description":"","meta_keywords":"","meta_author":"","page_name":""}
ERROR - 2022-05-28 17:47:57 --> "https:\/\/edumining.textom.co.kr\/"
ERROR - 2022-05-28 18:03:22 --> 404 Page Not Found: analysis/connect
ERROR - 2022-05-28 18:59:26 --> 404 Page Not Found: Git/config
ERROR - 2022-05-28 19:00:33 --> 404 Page Not Found: Git/config
ERROR - 2022-05-28 19:02:11 --> 404 Page Not Found: admin/Configphp/index
ERROR - 2022-05-28 21:56:55 --> ""
ERROR - 2022-05-28 21:56:55 --> {"path":"login","layout":"layout_test","skin":"login","layout_dir":"..\/login","mobile_layout_dir":"","use_sidebar":"","use_mobile_sidebar":"","skin_dir":"","mobile_skin_dir":"","page_title":"\ub85c\uadf8\uc778 - {\ud648\ud398\uc774\uc9c0\uc81c\ubaa9}","meta_description":"","meta_keywords":"","meta_author":"","page_name":""}
ERROR - 2022-05-28 21:56:55 --> ""
ERROR - 2022-05-28 21:56:55 --> {"path":"login","layout":"layout_test","skin":"login","layout_dir":"..\/login","mobile_layout_dir":"","use_sidebar":"","use_mobile_sidebar":"","skin_dir":"","mobile_skin_dir":"","page_title":"\ub85c\uadf8\uc778 - {\ud648\ud398\uc774\uc9c0\uc81c\ubaa9}","meta_description":"","meta_keywords":"","meta_author":"","page_name":""}
ERROR - 2022-05-28 21:56:57 --> ""
ERROR - 2022-05-28 21:56:57 --> {"path":"login","layout":"layout_test","skin":"login","layout_dir":"..\/login","mobile_layout_dir":"","use_sidebar":"","use_mobile_sidebar":"","skin_dir":"","mobile_skin_dir":"","page_title":"\ub85c\uadf8\uc778 - {\ud648\ud398\uc774\uc9c0\uc81c\ubaa9}","meta_description":"","meta_keywords":"","meta_author":"","page_name":""}
ERROR - 2022-05-28 21:57:06 --> ""
ERROR - 2022-05-28 21:57:06 --> {"path":"login","layout":"layout_test","skin":"login","layout_dir":"..\/login","mobile_layout_dir":"","use_sidebar":"","use_mobile_sidebar":"","skin_dir":"","mobile_skin_dir":"","page_title":"\ub85c\uadf8\uc778 - {\ud648\ud398\uc774\uc9c0\uc81c\ubaa9}","meta_description":"","meta_keywords":"","meta_author":"","page_name":""}
ERROR - 2022-05-28 21:57:07 --> ""
ERROR - 2022-05-28 21:57:07 --> {"path":"login","layout":"layout_test","skin":"login","layout_dir":"..\/login","mobile_layout_dir":"","use_sidebar":"","use_mobile_sidebar":"","skin_dir":"","mobile_skin_dir":"","page_title":"\ub85c\uadf8\uc778 - {\ud648\ud398\uc774\uc9c0\uc81c\ubaa9}","meta_description":"","meta_keywords":"","meta_author":"","page_name":""}
ERROR - 2022-05-28 21:57:28 --> ""
ERROR - 2022-05-28 21:57:28 --> {"path":"login","layout":"layout_test","skin":"login","layout_dir":"..\/login","mobile_layout_dir":"","use_sidebar":"","use_mobile_sidebar":"","skin_dir":"","mobile_skin_dir":"","page_title":"\ub85c\uadf8\uc778 - {\ud648\ud398\uc774\uc9c0\uc81c\ubaa9}","meta_description":"","meta_keywords":"","meta_author":"","page_name":""}
ERROR - 2022-05-28 21:57:28 --> ""
ERROR - 2022-05-28 21:57:28 --> {"path":"login","layout":"layout_test","skin":"login","layout_dir":"..\/login","mobile_layout_dir":"","use_sidebar":"","use_mobile_sidebar":"","skin_dir":"","mobile_skin_dir":"","page_title":"\ub85c\uadf8\uc778 - {\ud648\ud398\uc774\uc9c0\uc81c\ubaa9}","meta_description":"","meta_keywords":"","meta_author":"","page_name":""}
ERROR - 2022-05-28 21:57:32 --> ""
ERROR - 2022-05-28 21:57:32 --> {"path":"login","layout":"layout_test","skin":"login","layout_dir":"..\/login","mobile_layout_dir":"","use_sidebar":"","use_mobile_sidebar":"","skin_dir":"","mobile_skin_dir":"","page_title":"\ub85c\uadf8\uc778 - {\ud648\ud398\uc774\uc9c0\uc81c\ubaa9}","meta_description":"","meta_keywords":"","meta_author":"","page_name":""}
ERROR - 2022-05-28 21:59:04 --> 404 Page Not Found: Atomxml/index
ERROR - 2022-05-28 22:24:35 --> Severity: error --> Exception: Unable to locate the model you have specified: Board_meta_model /home/theimc/public_edumining/_system/core/Loader.php 344
ERROR - 2022-05-28 22:58:00 --> ""
ERROR - 2022-05-28 22:58:00 --> {"path":"login","layout":"layout_test","skin":"login","layout_dir":"..\/login","mobile_layout_dir":"","use_sidebar":"","use_mobile_sidebar":"","skin_dir":"","mobile_skin_dir":"","page_title":"\ub85c\uadf8\uc778 - {\ud648\ud398\uc774\uc9c0\uc81c\ubaa9}","meta_description":"","meta_keywords":"","meta_author":"","page_name":""}
ERROR - 2022-05-28 22:58:09 --> ""
ERROR - 2022-05-28 22:58:09 --> {"path":"login","layout":"layout_test","skin":"login","layout_dir":"..\/login","mobile_layout_dir":"","use_sidebar":"","use_mobile_sidebar":"","skin_dir":"","mobile_skin_dir":"","page_title":"\ub85c\uadf8\uc778 - {\ud648\ud398\uc774\uc9c0\uc81c\ubaa9}","meta_description":"","meta_keywords":"","meta_author":"","page_name":""}
ERROR - 2022-05-28 22:58:14 --> ""
ERROR - 2022-05-28 22:58:14 --> {"path":"login","layout":"layout_test","skin":"login","layout_dir":"..\/login","mobile_layout_dir":"","use_sidebar":"","use_mobile_sidebar":"","skin_dir":"","mobile_skin_dir":"","page_title":"\ub85c\uadf8\uc778 - {\ud648\ud398\uc774\uc9c0\uc81c\ubaa9}","meta_description":"","meta_keywords":"","meta_author":"","page_name":""}
ERROR - 2022-05-28 23:01:27 --> ""
ERROR - 2022-05-28 23:01:27 --> {"path":"login","layout":"layout_test","skin":"login","layout_dir":"..\/login","mobile_layout_dir":"","use_sidebar":"","use_mobile_sidebar":"","skin_dir":"","mobile_skin_dir":"","page_title":"\ub85c\uadf8\uc778 - {\ud648\ud398\uc774\uc9c0\uc81c\ubaa9}","meta_description":"","meta_keywords":"","meta_author":"","page_name":""}
ERROR - 2022-05-28 23:04:12 --> ""
ERROR - 2022-05-28 23:04:12 --> {"path":"login","layout":"layout_test","skin":"login","layout_dir":"..\/login","mobile_layout_dir":"","use_sidebar":"","use_mobile_sidebar":"","skin_dir":"","mobile_skin_dir":"","page_title":"\ub85c\uadf8\uc778 - {\ud648\ud398\uc774\uc9c0\uc81c\ubaa9}","meta_description":"","meta_keywords":"","meta_author":"","page_name":""}
ERROR - 2022-05-28 23:12:54 --> ""
ERROR - 2022-05-28 23:12:54 --> {"path":"login","layout":"layout_test","skin":"login","layout_dir":"..\/login","mobile_layout_dir":"","use_sidebar":"","use_mobile_sidebar":"","skin_dir":"","mobile_skin_dir":"","page_title":"\ub85c\uadf8\uc778 - {\ud648\ud398\uc774\uc9c0\uc81c\ubaa9}","meta_description":"","meta_keywords":"","meta_author":"","page_name":""}
