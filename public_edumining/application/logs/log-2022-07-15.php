<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2022-07-15 00:48:49 --> ""
ERROR - 2022-07-15 00:48:49 --> {"path":"login","layout":"layout_test","skin":"login","layout_dir":"..\/login","mobile_layout_dir":"","use_sidebar":"","use_mobile_sidebar":"","skin_dir":"","mobile_skin_dir":"","page_title":"\ub85c\uadf8\uc778 - {\ud648\ud398\uc774\uc9c0\uc81c\ubaa9}","meta_description":"","meta_keywords":"","meta_author":"","page_name":""}
ERROR - 2022-07-15 06:06:47 --> 404 Page Not Found: Remote/fgt_lang
ERROR - 2022-07-15 09:16:05 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near ')         ORDER BY EBA.notice_status ASC, EBA.create_date DESC, EBA.no DESC) AS ' at line 1 - Invalid query: SELECT RST.* FROM (   SELECT  @ROWNUM:=@ROWNUM+1 AS item_no, RST_INN.*  FROM (SELECT @ROWNUM := 0) R,       (SELECT           EBA.no AS article_no,           EBA.user_no AS user_no,           EBA.title AS title,           EBA.contents AS contents,           EBA.create_date AS create_date,           EBA.update_date AS update_date,           EBA.view_count AS view_count,           EBA.notice_status AS notice_status,           TM.mem_username AS user_name         FROM edu_board_article AS EBA         JOIN t_member AS TM ON TM.mem_id = EBA.user_no         WHERE EBA.article_type = 0 AND EBA.delete_status = 'N' AND (TM.mem_userid = '' OR EBA.user_no = )         ORDER BY EBA.notice_status ASC, EBA.create_date DESC, EBA.no DESC) AS RST_INN ) AS RST LIMIT 10 OFFSET 0
ERROR - 2022-07-15 09:16:05 --> Severity: Error --> Call to a member function result_array() on boolean /home/theimc/public_edumining/application/models/edumining/Analysis_model.php 310
ERROR - 2022-07-15 09:30:32 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near ')         ORDER BY EBA.notice_status ASC, EBA.create_date DESC, EBA.no DESC) AS ' at line 1 - Invalid query: SELECT RST.* FROM (   SELECT  @ROWNUM:=@ROWNUM+1 AS item_no, RST_INN.*  FROM (SELECT @ROWNUM := 0) R,       (SELECT           EBA.no AS article_no,           EBA.user_no AS user_no,           EBA.title AS title,           EBA.contents AS contents,           EBA.create_date AS create_date,           EBA.update_date AS update_date,           EBA.view_count AS view_count,           EBA.notice_status AS notice_status,           TM.mem_username AS user_name         FROM edu_board_article AS EBA         JOIN t_member AS TM ON TM.mem_id = EBA.user_no         WHERE EBA.article_type = 0 AND EBA.delete_status = 'N' AND (TM.mem_userid = '' OR EBA.user_no = )         ORDER BY EBA.notice_status ASC, EBA.create_date DESC, EBA.no DESC) AS RST_INN ) AS RST LIMIT 10 OFFSET 0
ERROR - 2022-07-15 09:30:32 --> Severity: Error --> Call to a member function result_array() on boolean /home/theimc/public_edumining/application/models/edumining/Analysis_model.php 310
ERROR - 2022-07-15 10:04:12 --> 404 Page Not Found: WuEL/index
ERROR - 2022-07-15 10:04:19 --> 404 Page Not Found: A/index
ERROR - 2022-07-15 10:04:21 --> 404 Page Not Found: Download/file.ext
ERROR - 2022-07-15 10:04:23 --> 404 Page Not Found: SiteLoader/index
ERROR - 2022-07-15 10:04:25 --> 404 Page Not Found: MPlayer/index
ERROR - 2022-07-15 10:08:29 --> 404 Page Not Found: Wp-plainphp/index
ERROR - 2022-07-15 10:08:29 --> 404 Page Not Found: Hptkyafqphp/index
ERROR - 2022-07-15 10:16:39 --> 404 Page Not Found: Aws/credentials
ERROR - 2022-07-15 10:48:06 --> 404 Page Not Found: Wp-plainphp/index
ERROR - 2022-07-15 10:48:06 --> 404 Page Not Found: Mtxlflfophp/index
ERROR - 2022-07-15 10:51:42 --> 404 Page Not Found: Owa/auth
ERROR - 2022-07-15 10:52:37 --> 404 Page Not Found: Owa/auth
ERROR - 2022-07-15 10:55:02 --> 404 Page Not Found: Ecp/Current
ERROR - 2022-07-15 11:13:40 --> 404 Page Not Found: Owa/auth
ERROR - 2022-07-15 11:32:45 --> 404 Page Not Found: Well-known/assetlinks.json
ERROR - 2022-07-15 12:37:24 --> 404 Page Not Found: admin/Configphp/index
ERROR - 2022-07-15 14:08:21 --> ""
ERROR - 2022-07-15 14:08:21 --> {"path":"login","layout":"layout_test","skin":"login","layout_dir":"..\/login","mobile_layout_dir":"","use_sidebar":"","use_mobile_sidebar":"","skin_dir":"","mobile_skin_dir":"","page_title":"\ub85c\uadf8\uc778 - {\ud648\ud398\uc774\uc9c0\uc81c\ubaa9}","meta_description":"","meta_keywords":"","meta_author":"","page_name":""}
ERROR - 2022-07-15 18:12:04 --> 404 Page Not Found: Remote/fgt_lang
ERROR - 2022-07-15 19:57:48 --> 404 Page Not Found: Web_shell_cmdgch/index
ERROR - 2022-07-15 19:57:48 --> 404 Page Not Found: Web_shell_cmdgch/index
ERROR - 2022-07-15 19:57:49 --> 404 Page Not Found: Web_shell_cmdgch/index
ERROR - 2022-07-15 22:00:16 --> 404 Page Not Found: Wp-plainphp/index
ERROR - 2022-07-15 22:00:16 --> 404 Page Not Found: Fenjovluphp/index
ERROR - 2022-07-15 23:10:55 --> Severity: error --> Exception: Unable to locate the model you have specified: Board_meta_model /home/theimc/public_edumining/_system/core/Loader.php 344
