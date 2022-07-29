<?php
    $fileName = $view['data']['filePath'];
    $keyword = $view['data']['keyword'];
    $updateDate = $view['data']['updateDate'];
    $fileSize = $view['data']['fileSize'] / 1024;
    if($fileSize > 1024){
        $sizeTxt = round($fileSize / 1024, 2) . " MB";
    }else{
        $sizeTxt = round($fileSize, 2) . "KB";
    }
?>

<div class="tit_pop pink_pop">
    <h2>원문 미리보기</h2>
    <span class="btn_pop_close" title="창닫기" onclick="self.close();"><i class="fas fa-times"></i></span>
</div>

<div class="pop_body">

    <section>
        <div class="grey_contbox brd_detail">
            <h3 id="title" class="mb10"><?=$keyword?></h3>
            <p id="class_info" class="mb10"><time id="" datetime="<?=$updateDate?>"><?=$updateDate?></time> / <?=$sizeTxt?></p>
            <div class="wh_box mt20">
				<ul id="rad" class="list_rawdata">


				</ul>
			</div>
		</div>
    </section>

</div>

<script type="text/javascript">

const filePath = "<? echo $fileName ?>"
$.ajax({
    method: 'POST',
    url: "/edumining/data_management/getTextData",
    data: {
        "file_path" : filePath
    },
    dataType: "JSON",
    success : function(data){
    
        let textArr = data.text
        if (textArr.length > 0) {
            let src = ''
            for (let i=0; i<textArr.length; i++) {
                const title    = textArr[i][0]
                const url      = textArr[i][1]
                const contents = textArr[i][2]
                src += `<li>${title}<br><a target="_blank" href="${url}">${url}</a>${contents}</li>`
            }
            const doc = document.getElementById("rad")
            doc.innerHTML = src
        }
        
    },
    error: function(xhr, status, error) {
        console.log(error);
    }
});

</script>
