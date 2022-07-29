

<div class="mypage">
    <?include __DIR__."/mypage_tab.php";?>

    <h3>로그인 기록</h3>

    <table class="table">
        <thead>
            <tr>
                <th>로그인여부</th>
                <th>IP</th>
                <th>OS</th>
                <th>Browser</th>
                <th>날짜</th>
            </tr>
        </thead>
        <tbody>

        <?php
        if (element('list', element('data', $view))) {
            foreach (element('list', element('data', $view)) as $result) {
        ?>
            <tr>
                <td><?php echo element('mll_success', $result) === '1' ? "<span class=\"label label-success\">로그인성공</span>":"<span class=\"label label-danger\">로그인실패</span>"; ?></td>
                <td><?php echo html_escape(element('mll_ip', $result)); ?></td>
                <td><?php echo html_escape(element('os', $result)); ?></td>
                <td><?php echo html_escape(element('browsername', $result)); ?> <?php echo html_escape(element('browserversion', $result)); ?> <?php echo html_escape(element('engine', $result)); ?></td>
                <td><?php echo display_datetime(element('mll_datetime', $result), 'full'); ?></td>
            </tr>
        <?php
            }
        }
        if ( ! element('list', element('data', $view))) {
        ?>
            <tr>
                <td colspan="5" class="nopost">로그인 기록이 없습니다</td>
            </tr>
        <?php
        }
        ?>
        </tbody>
    </table>
    <nav><?php echo element('paging', $view); ?></nav>
</div>
