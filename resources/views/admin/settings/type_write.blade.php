<style>
    .custom-list-item {
        position: relative;
    }
    .custom-list-item .remove-item {
        background: red;
        border: 0;
        cursor: pointer;
        position: absolute;
        bottom: 0;
    }
    .custom-list-item .remove-item i {
        color: #fff;
        font-size: #fff;
    }
    .custom-list-item {
        border-top: 2px solid #ededed;
        padding-bottom: 10px;
        margin-bottom: 10px;
        padding-top: 10px;
    }
</style>
<div class="custom">
    <div class="custom_variable">
        <label class="col-sm-2 col-form-label">Mô tả biến</label>
        <ul class="custom_variable__detail">
            <li><b>title:</b> Tiêu đề bài viết</li>
            <li><b>outline:</b> Dàn ý</li>
            <li><b>heading:</b> Heading</li>
            <li><b>text:</b> Nội dung viết thêm</li>
        </ul>
    </div>
    <div class="custom-list">
        <div class="custom-list-item">
           {{--  <div class="mb-3 row">
                <label class="col-sm-2 col-form-label"><span class="text-red">*</span>Behavior</label>
                <div class="col-sm-9">
                    <textarea class="form-control validate" style="height: 100px" type="text" name="type_write[behavior]" placeholder="Ví dụ: Bạn là một chuyên gia trong lĩnh vực viết lách">{{ $data['type_write']['behavior'] ?? '' }}</textarea>
                </div>
            </div> --}}
            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label"><span class="text-red">*</span> Prompt</label>
                <div class="col-sm-9">
                    <p>Các biến có thể dùng trong đoạn prompt: {title}', '{outline}', '{heading}', '{text}</p>
                    <textarea class="form-control validate" style="height: 200px" type="text" name="type_write[prompt]" placeholder="Ví dụ: Tôi đang viết bài SEO với tiêu đề bài viết: {title}. Hãy viết phần mở đầu với yêu cầu: Từ khóa {primary_keyword} xuất hiện ở 160 ký tự đầu tiên.">{{ $data['type_write']['prompt'] ?? '' }}</textarea>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $('body').on('click', 'button[type="submit"]', function(e){
            let checkType = true;
            $('.form-control.validate').each(function(e) {
                if($(this).val() == '' || $(this).val() == null || $(this).val() == undefined) {
                    checkType = false
                }
            })
            if(!checkType) {
                alert('Tất cả các giá trị đều là bắt buộc!')
                e.preventDefault();
            }
        })
    });
</script>