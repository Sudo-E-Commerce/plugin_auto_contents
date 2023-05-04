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
    .custom_variable__detail {
        display: flex;
        flex-wrap: wrap;
    }
    .custom_variable__detail li {
        width: 50%;
    }
</style>
<div class="custom">
    <div class="custom_variable">
        <label class="col-sm-2 col-form-label">Mô tả các biến</label>
        <ul class="custom_variable__detail">
            <li><b>title:</b> Tiêu đề bài viết</li>
            <li><b>outline:</b> Dàn ý</li>
            <li><b>heading:</b> Heading</li>
            <li><b>primary_keyword:</b> Từ khóa chính</li>
            <li><b>domain:</b> Domain</li>
            <li><b>content_website:</b> Nội dung website</li>
            <li><b>trademark:</b> Thương hiệu</li>
            <li><b>purpose:</b> Mục đích website</li>
            <li><b>object:</b> Đối tượng</li>
            <li><b>age:</b> Độ tuổi</li>
            <li><b>gender:</b> Giới tính</li>
            <li><b>purpose_post:</b> Mục đích bài viết</li>
            <li><b>style_post:</b> Phong cách viết bài</li>
            <li><b>lenght_text:</b> Độ dài text</li>
            <li><b>current_heading:</b> Heading cần viết</li>
            <li><b>current_detail:</b> Mô tả thêm cho heading cần viết</li>
            <li><b>previous_heading:</b> Heading trước</li>
        </ul>
    </div>
    <div class="custom_required">
        <div class="mb-3 row">
            <label class="col-sm-2 col-form-label"><span class="text-red">*</span> Prompt Mở đầu</label>
            <div class="col-sm-9">
                <p>Các biến có thể dùng trong đoạn prompt: {title}, {outline}, {primary_keyword}, {domain}, {content_website}, {trademark}, {purpose}</p>
                <textarea class="form-control validate" style="height: 200px" type="text" name="type_foreword" placeholder="Ví dụ: Tôi đang viết bài SEO với tiêu đề bài viết: {title}. Hãy viết phần mở đầu với yêu cầu: Từ khóa {primary_keyword} xuất hiện ở 160 ký tự đầu tiên.">{{ $data['type_foreword'] ?? '' }}</textarea>
            </div>
        </div>
        <div class="mb-3 row">
            <label class="col-sm-2 col-form-label"><span class="text-red">*</span> Prompt kết luận</label>
            <div class="col-sm-9">
                <p>Các biến có thể dùng trong đoạn prompt: {title}, {outline}, {primary_keyword}, {domain}, {content_website}, {trademark}, {purpose}</p>
                <textarea class="form-control validate" style="height: 200px" type="text" name="type_end" placeholder="Ví dụ: Tôi đang viết bài SEO với tiêu đề bài viết: {title}. Hãy viết phần kết bài với yêu cầu: Từ khóa {primary_keyword} xuất hiện ở 160 ký tự đầu tiên.">{{ $data['type_end'] ?? '' }}</textarea>
            </div>
        </div>
    </div>
    <div class="custom-list">
        @if(isset($data['type_heading']) && count($data['type_heading']))
            @foreach ($data['type_heading'] as $key => $item)
                <div class="custom-list-item">
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label"><span class="text-red">*</span> Tên</label>
                        <div class="col-sm-9">
                            <input type="hidden" name="type_heading[{{ $key }}][key]" value="{{ $item['key'] ?? '' }}" placeholder="">
                            <input class="form-control validate" type="text" name="type_heading[{{ $key }}][title]" value="{{ $item['title'] ?? '' }}" placeholder="">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label"><span class="text-red">*</span> Prompt</label>
                        <div class="col-sm-9">
                            <p>Các biến có thể dùng trong đoạn prompt: {title}, {outline}, {heading}, {primary_keyword}, {domain}, {content_website}, {trademark}, {purpose}, {object}, {age}, {gender}, {purpose_post}, {style_post}, {lenght_text},{current_heading}, {previous_heading},{current_detail}</p>
                            <textarea class="form-control validate" style="height: 200px" type="text" name="type_heading[{{ $key }}][prompt]" placeholder="Ví dụ: Tôi đang viết bài SEO với tiêu đề bài viết: {title}. Hãy viết phần mở đầu với yêu cầu: Từ khóa {primary_keyword} xuất hiện ở 160 ký tự đầu tiên.">{{ $item['prompt'] ?? '' }}</textarea>
                        </div>
                        <div class="col-sm-1">
                            <button class="remove-item"><i class="fa fa-trash"></i></button>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
    <div class="text-center">
        <button type="button" style="width: 66%;" class="btn btn-primary add_address">+ Thêm loại heading</button>
    </div>
</div>
<script>
    $(document).ready(function(){
        $('body').on('click', '.add_address', function(e) {
            e.preventDefault();
            let stt = $(this).closest('.custom').find('.custom-list-item').length
            stt++
            let html = `
                <div class="custom-list-item">
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label"><span class="text-red">*</span> Tên</label>
                        <div class="col-sm-9">
                            <input type="hidden" name="type_heading[${stt}][key]" value="${stt}-${new Date().getTime()}" placeholder="">
                            <input class="form-control validate" type="text" name="type_heading[${stt}][title]" value="" placeholder="">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label"><span class="text-red">*</span> Prompt</label>
                        <div class="col-sm-9">
                            <p>Các biến có thể dùng trong đoạn prompt: {title}, {outline}, {heading}, {primary_keyword}, {domain}, {content_website}, {trademark}, {purpose},{object}, {age}, {gender}, {purpose_post}, {style_post}, {lenght_text}, {current_heading}, {previous_heading},{current_detail}</p>
                            <textarea class="form-control validate" style="height: 200px" type="text" name="type_heading[${stt}][prompt]" value="" placeholder="Ví dụ: Tôi đang viết bài SEO với tiêu đề bài viết: {title}. Hãy viết phần mở đầu với yêu cầu: Từ khóa {primary_keyword} xuất hiện ở 160 ký tự đầu tiên."></textarea>
                        </div>
                        <div class="col-sm-1">
                            <button class="remove-item"><i class="fa fa-trash"></i></button>
                        </div>
                    </div>
                </div>
                <script>$('.select2').select2();<\/script>
            `;
            $(this).closest('.custom').find('.custom-list').append(html);
        })
        $('body').on('click', '.remove-item', function(e){
            e.preventDefault();
            $(this).closest('.custom-list-item').remove()
        })
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