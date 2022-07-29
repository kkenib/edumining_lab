<div class="sub_tit">
    <div class="s_tit_wrap">
        <h1>현재 접속자</h1>
        <ul class="location">
            <li><i class="fa fa-home fa-lg"></i></li>
            <li><i class="fa fa-caret-right"></i>현재 접속자</li>
        </ul>
    </div>
</div>

<div class="box">
<table class="table">
    <thead>
        <tr>
            <th>번호</th>
            <th>이름</th>
            <th>위치</th>
        </tr>
    </thead>
    <tbody>
    <?php
    if (element('list', $view)) {
        foreach (element('list', $view) as $result) {
    ?>
        <tr>
            <td><?php echo element('num', $result); ?></td>
            <td><?php echo element('name_or_ip', $result); ?></td>
            <td><?php echo element('cur_page', $result); ?></td>
        </tr>
    <?php
        }
    }
    ?>
    </tbody>
</table>
<nav><?php echo element('paging', $view); ?></nav>
</div>
