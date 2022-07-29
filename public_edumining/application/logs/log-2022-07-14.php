<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2022-07-14 00:15:07 --> Severity: error --> Exception: Unable to locate the model you have specified: Board_meta_model /home/theimc/public_edumining/_system/core/Loader.php 344
ERROR - 2022-07-14 01:18:33 --> Severity: error --> Exception: Unable to locate the model you have specified: Board_meta_model /home/theimc/public_edumining/_system/core/Loader.php 344
ERROR - 2022-07-14 01:43:20 --> 404 Page Not Found: Ab2h/index
ERROR - 2022-07-14 01:50:37 --> 404 Page Not Found: Views/_layout
ERROR - 2022-07-14 01:52:39 --> 404 Page Not Found: Views/_layout
ERROR - 2022-07-14 02:04:38 --> 404 Page Not Found: Plugin/summernote
ERROR - 2022-07-14 03:26:05 --> 404 Page Not Found: Wp-plainphp/index
ERROR - 2022-07-14 03:26:05 --> 404 Page Not Found: Kgdosodfphp/index
ERROR - 2022-07-14 05:17:13 --> 404 Page Not Found: Wp-plainphp/index
ERROR - 2022-07-14 05:17:13 --> 404 Page Not Found: Omlugsraphp/index
ERROR - 2022-07-14 05:27:10 --> 404 Page Not Found: admin/Configphp/index
ERROR - 2022-07-14 05:39:33 --> 404 Page Not Found: Wp-plainphp/index
ERROR - 2022-07-14 05:39:33 --> 404 Page Not Found: Ugcaoqubphp/index
ERROR - 2022-07-14 07:30:28 --> 404 Page Not Found: System_apiphp/index
ERROR - 2022-07-14 07:30:30 --> 404 Page Not Found: C/version.js
ERROR - 2022-07-14 07:30:32 --> 404 Page Not Found: Streaming/clients_live.php
ERROR - 2022-07-14 07:30:34 --> 404 Page Not Found: Stalker_portal/c
ERROR - 2022-07-14 07:30:36 --> 404 Page Not Found: Stream/live.php
ERROR - 2022-07-14 07:30:38 --> 404 Page Not Found: Flu/403.html
ERROR - 2022-07-14 10:52:32 --> 404 Page Not Found: Owa/auth
ERROR - 2022-07-14 10:53:28 --> 404 Page Not Found: Owa/auth
ERROR - 2022-07-14 10:56:28 --> 404 Page Not Found: Ecp/Current
ERROR - 2022-07-14 11:13:54 --> 404 Page Not Found: Owa/auth
ERROR - 2022-07-14 11:28:47 --> 404 Page Not Found: Well-known/assetlinks.json
ERROR - 2022-07-14 14:20:03 --> 404 Page Not Found: Js/core.js
ERROR - 2022-07-14 14:37:53 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near ')         ORDER BY EBA.notice_status ASC, EBA.create_date DESC, EBA.no DESC) AS ' at line 1 - Invalid query: SELECT RST.* FROM (   SELECT  @ROWNUM:=@ROWNUM+1 AS item_no, RST_INN.*  FROM (SELECT @ROWNUM := 0) R,       (SELECT           EBA.no AS article_no,           EBA.user_no AS user_no,           EBA.title AS title,           EBA.contents AS contents,           EBA.create_date AS create_date,           EBA.update_date AS update_date,           EBA.view_count AS view_count,           EBA.notice_status AS notice_status,           TM.mem_username AS user_name         FROM edu_board_article AS EBA         JOIN t_member AS TM ON TM.mem_id = EBA.user_no         WHERE EBA.article_type = 0 AND EBA.delete_status = 'N' AND (TM.mem_userid = '' OR EBA.user_no = )         ORDER BY EBA.notice_status ASC, EBA.create_date DESC, EBA.no DESC) AS RST_INN ) AS RST LIMIT 10 OFFSET 0
ERROR - 2022-07-14 14:37:53 --> Severity: Error --> Call to a member function result_array() on boolean /home/theimc/public_edumining/application/models/edumining/Analysis_model.php 310
ERROR - 2022-07-14 15:50:29 --> 404 Page Not Found: Remote/login
ERROR - 2022-07-14 16:27:45 --> 404 Page Not Found: Plugin/summernote
ERROR - 2022-07-14 18:20:15 --> ""
ERROR - 2022-07-14 18:20:15 --> {"path":"login","layout":"layout_test","skin":"login","layout_dir":"..\/login","mobile_layout_dir":"","use_sidebar":"","use_mobile_sidebar":"","skin_dir":"","mobile_skin_dir":"","page_title":"\ub85c\uadf8\uc778 - {\ud648\ud398\uc774\uc9c0\uc81c\ubaa9}","meta_description":"","meta_keywords":"","meta_author":"","page_name":""}
ERROR - 2022-07-14 20:22:42 --> ""
ERROR - 2022-07-14 20:22:42 --> {"path":"login","layout":"layout_test","skin":"login","layout_dir":"..\/login","mobile_layout_dir":"","use_sidebar":"","use_mobile_sidebar":"","skin_dir":"","mobile_skin_dir":"","page_title":"\ub85c\uadf8\uc778 - {\ud648\ud398\uc774\uc9c0\uc81c\ubaa9}","meta_description":"","meta_keywords":"","meta_author":"","page_name":""}
ERROR - 2022-07-14 20:46:37 --> 404 Page Not Found: Env/index
ERROR - 2022-07-14 20:53:34 --> Severity: error --> Exception: Unable to locate the model you have specified: Board_meta_model /home/theimc/public_edumining/_system/core/Loader.php 344
ERROR - 2022-07-14 21:47:48 --> 404 Page Not Found: Remote/fgt_lang
ERROR - 2022-07-14 22:45:26 --> 404 Page Not Found: Version/index
