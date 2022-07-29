<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2022-05-27 00:01:40 --> Severity: Warning --> include(INCPATHcss/style.css): failed to open stream: No such file or directory /home/theimc/public_edumining/views/register/textomi/policy2.php 81
ERROR - 2022-05-27 00:01:40 --> Severity: Warning --> include(): Failed opening 'INCPATHcss/style.css' for inclusion (include_path='.:/usr/share/php') /home/theimc/public_edumining/views/register/textomi/policy2.php 81
ERROR - 2022-05-27 00:45:55 --> 404 Page Not Found: Env/index
ERROR - 2022-05-27 00:45:55 --> 404 Page Not Found: Infophp/index
ERROR - 2022-05-27 00:45:55 --> 404 Page Not Found: Ecp/Current
ERROR - 2022-05-27 00:45:55 --> 404 Page Not Found: DS_Store/index
ERROR - 2022-05-27 00:45:55 --> 404 Page Not Found: Git/config
ERROR - 2022-05-27 00:45:55 --> 404 Page Not Found: Telescope/requests
ERROR - 2022-05-27 00:45:56 --> 404 Page Not Found: Configjson/index
ERROR - 2022-05-27 00:45:56 --> 404 Page Not Found: Loginaction/index
ERROR - 2022-05-27 03:32:25 --> 404 Page Not Found: Tmui/login.jsp
ERROR - 2022-05-27 10:09:14 --> 404 Page Not Found: admin/Configphp/index
ERROR - 2022-05-27 10:27:32 --> 404 Page Not Found: Owa/auth
ERROR - 2022-05-27 10:28:05 --> 404 Page Not Found: Owa/auth
ERROR - 2022-05-27 10:30:24 --> 404 Page Not Found: Ecp/Current
ERROR - 2022-05-27 10:56:29 --> 404 Page Not Found: Actuator/health
ERROR - 2022-05-27 12:00:27 --> 404 Page Not Found: Sitemap_indexxml/index
ERROR - 2022-05-27 12:23:10 --> Severity: Warning --> include(INCPATHcss/style.css): failed to open stream: No such file or directory /home/theimc/public_edumining/views/register/textomi/policy2.php 81
ERROR - 2022-05-27 12:23:10 --> Severity: Warning --> include(): Failed opening 'INCPATHcss/style.css' for inclusion (include_path='.:/usr/share/php') /home/theimc/public_edumining/views/register/textomi/policy2.php 81
ERROR - 2022-05-27 13:16:39 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near ')         ORDER BY EBA.notice_status ASC, EBA.create_date DESC, EBA.no DESC) AS ' at line 1 - Invalid query: SELECT RST.* FROM (   SELECT  @ROWNUM:=@ROWNUM+1 AS item_no, RST_INN.*  FROM (SELECT @ROWNUM := 0) R,       (SELECT           EBA.no AS article_no,           EBA.user_no AS user_no,           EBA.title AS title,           EBA.contents AS contents,           EBA.create_date AS create_date,           EBA.update_date AS update_date,           EBA.view_count AS view_count,           EBA.notice_status AS notice_status,           TM.mem_username AS user_name         FROM edu_board_article AS EBA         JOIN t_member AS TM ON TM.mem_id = EBA.user_no         WHERE EBA.article_type = 0 AND EBA.delete_status = 'N' AND (TM.mem_userid = '' OR EBA.user_no = )         ORDER BY EBA.notice_status ASC, EBA.create_date DESC, EBA.no DESC) AS RST_INN ) AS RST LIMIT 10 OFFSET 0
ERROR - 2022-05-27 13:16:39 --> Severity: Error --> Call to a member function result_array() on boolean /home/theimc/public_edumining/application/models/edumining/Analysis_model.php 310
ERROR - 2022-05-27 14:24:13 --> ""
ERROR - 2022-05-27 14:24:13 --> {"path":"login","layout":"layout_test","skin":"login","layout_dir":"..\/login","mobile_layout_dir":"","use_sidebar":"","use_mobile_sidebar":"","skin_dir":"","mobile_skin_dir":"","page_title":"\ub85c\uadf8\uc778 - {\ud648\ud398\uc774\uc9c0\uc81c\ubaa9}","meta_description":"","meta_keywords":"","meta_author":"","page_name":""}
ERROR - 2022-05-27 14:27:33 --> 404 Page Not Found: Atomxml/index
ERROR - 2022-05-27 14:53:58 --> Severity: error --> Exception: Unable to locate the model you have specified: Board_meta_model /home/theimc/public_edumining/_system/core/Loader.php 344
ERROR - 2022-05-27 18:00:55 --> 404 Page Not Found: Well-known/assetlinks.json
ERROR - 2022-05-27 19:19:22 --> 404 Page Not Found: Remote/fgt_lang
ERROR - 2022-05-27 19:59:15 --> 404 Page Not Found: admin/Configphp/index
ERROR - 2022-05-27 20:41:46 --> 404 Page Not Found: Env/index
