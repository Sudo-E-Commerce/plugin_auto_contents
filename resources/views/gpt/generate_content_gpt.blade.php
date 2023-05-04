<style>
    .modal-middle {
        align-items: unset;
    }
    .modal-middle__left {
        width: 45%;
    }
    .modal-middle__left .form-group {
        margin-bottom: 15px;
    }
    .modal-middle__left .form-group label {
        width: 65%;
    }
    .modal-middle__right {
        width: 55%;
        padding-left: 50px;
    }
    .modal-middle__right ul {
        display:  flex;
        list-style: none;
        padding-left: 0;
    }
    .modal-middle__right ul li {
        padding: 3px 10px;
        border:  1px solid #556ee6;
        border-radius: 5px;
        transition: 0.3s;
        margin-right: 10px;
        cursor: pointer;
    }
    .modal-middle__right ul li span {
        color: #556ee6;
        transition: 0.3s;
    }
    .modal-middle__right ul li:hover,
    .modal-middle__right ul li.active {
        background: #556ee6;
    }
    .modal-middle__right ul li:hover span,
    .modal-middle__right ul li.active span {
        color:  #fff;
    }
    .lightbulb {
        cursor: pointer;
    }
    .lightbulb.active svg {
        fill: yellow;
    }
    .title_b {
        max-width: 400px;
    }
    .title_b p {
        white-space: pre-line;
    }
    .infomation {
        cursor: pointer;
        position: relative;
    }
    .popup_info {
        display:  none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        align-items: center;
        justify-content: center;
        z-index:  9;
    }
    .popup_info.active {
        display: flex;
    }
    .popup_info:after {
        position: absolute;
        content: "";
        background: rgba(0,0,0,0.6);
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }
    .popup_info__content {
        max-width: 600px;
        padding: 20px;
        background: #fff;
        border: 1px solid #ccc;
        border-radius: 5px;
        position: relative;
        z-index: 99;
    }
    .popup_info__content .prompt p {
        white-space: normal;
        text-align: justify;
    }
    .popup_info__content .close_popup {
        position: absolute;
        top: 5px;
        right: 5px;
    }
    .prompt b {
        margin-bottom: 15px;
        display: block;
        text-align: left;
    }
</style>

@if($class_col != '')
    <div class="{{ $class_col }}">
@endif
    <div class="form-group">
        <label {!! $has_row ? 'class="control-label col-md-2 col-sm-2 col-xs-12"' : 'style="text-align: left"' !!}>{{ $title??'' }}</label>
        @if($has_row)
            <div class="controls col-md-9 col-sm-10 col-xs-12">
        @endif
                <a type="button" class="btn btn-sm btn-primary" id="open-modal-gpt"
                        data-field_detail="{{ $field_detail }}"
                        data-field_title="{{ $field_title }}"
                        data-primary_keyword="{{ $primary_keyword ?? '' }}"
                        data-backdrop="static"
                        data-toggle="modal" href="#heading_modal_{{$field_detail}}"
                        data-bs-toggle="modal"
                    >
                    Generate
                </a>
        @if($has_row)
            </div>
        @endif
    </div>

@if($class_col != '')
    </div>
@endif
<!-- Modal -->
<div id="heading_modal_{{$field_detail}}" class="modal fade modal-table" role="dialog" aria-modal="true">
    <div class="modal-dialog modal-lg" style="max-width: 70vw;">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Tạo content</h4>
          <button type="button" class="close close_mode" style="border: 1px solid #ddd; border-radius: 50%; width: 25px; height: 25px; color: #444;"><i class="fa fa-times" aria-hidden="true"></i>
            </button>
        </div>
        <div class="modal-header modal-middle">
            <div class="modal-middle__left">
                <div class="form-group flex">
                    <label for="object" class="col-form-label">Bạn viết bài này cho ai?</label>
                    <input type="text" class="form-control" autocomplete="off" name="object" id="object" placeholder="VD: Người mới kinh doanh">
                </div>
                <div class="form-group flex">
                    <label for="age" class="col-form-label">Độ tuổi người đọc</label>
                    <input type="text" class="form-control" autocomplete="off" name="age" id="age" placeholder="VD: Từ 18 đến 25">
                </div>
                <div class="form-group flex">
                    <label for="gender" class="col-form-label">Giới tính người đọc</label>
                    <input type="text" class="form-control" autocomplete="off" name="gender" id="gender" placeholder="VD: Cho Nam hoặc cả Nam và Nữ">
                </div>
                <div class="form-group flex">
                    <label for="purpose_post" class="col-form-label">Mục đích của bài viết</label>
                    <input type="text" class="form-control" autocomplete="off" name="purpose_post" id="purpose_post" placeholder="VD: Bài viết tư vấn mua hàng">
                </div>
            </div>
            @php
                $general_ai = getOption('general_ai');
                $type_heading = getOption('type_heading');
                $yourApiKey = $general_ai['account_ai'] ?? '';
                $domain = $general_ai['domain'] ?? '';
                $content_website = $general_ai['content_website'] ?? '';
                $trademark = $general_ai['trademark'] ?? '';
                $purpose = $general_ai['purpose'] ?? '';
            @endphp
            <input type="hidden" name="yourApiKey" value="{{ $yourApiKey ?? '' }}">
            <div class="modal-middle__right">
                <label for="">Phong cách bài viết: </label>
                <ul class="style_post">
                    <li data-style="Thông tin" class="active style_post__item"><span>Thông tin</span></li>
                    <li data-style="Hài hước" class="style_post__item"><span>Hài hước</span></li>
                    <li data-style="Sáng tạo" class="style_post__item"><span>Sáng tạo</span></li>
                    <li data-style="Chuyên nghiệp" class="style_post__item"><span>Chuyên nghiệp</span></li>
                    <li data-style="Nhiệt tình" class="style_post__item"><span>Nhiệt tình</span></li>
                </ul>
                <div class="save_setting">
                    <a href="javascript:;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="20" height="20"><path d="M64 32C28.7 32 0 60.7 0 96V416c0 35.3 28.7 64 64 64H384c35.3 0 64-28.7 64-64V173.3c0-17-6.7-33.3-18.7-45.3L352 50.7C340 38.7 323.7 32 306.7 32H64zm0 96c0-17.7 14.3-32 32-32H288c17.7 0 32 14.3 32 32v64c0 17.7-14.3 32-32 32H96c-17.7 0-32-14.3-32-32V128zM224 288a64 64 0 1 1 0 128 64 64 0 1 1 0-128z" fill="#16bd16"/></svg>
                        Ghi nhớ cài đặt
                    </a>
                </div>
            </div>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label class="control-label col-sm-2" style="text-align: left">Chọn headings</label>
                <div class="col-sm-12">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped headings">
                            <thead>
                                <tr>
                                    <td class="text-center" style="width: 50px">#</td>
                                    <td class="text-center">Tag heading</td>
                                    <td class="text-center">Text heading</td>
                                    <td class="text-center"></td>
                                    <td class="text-center">Loại heading</td>
                                    <td class="text-center">Độ dài</td>
                                    <td class="text-center">
                                        <input type="checkbox" class="tick_all" checked>
                                    </td>
                                    <td class="text-center">Trạng thái</td>
                                    <td class="text-center">Thông tin</td>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary generate-gpt">
                <i style="display: none" class="loading fa fa-refresh fa-spin"></i>
                Generate
            </button>
            <button type="button" class="btn btn-default close_mode">Thoát</button>

        </div>
      </div>

    </div>
  </div>
  
<script>
    $(document).ready(function () {
        let editor = null;
        let heading_tags = [];
        let process = 0;
        let div_content = '';
        let title = '';
        let primary_keyword = '';
        // let table = '';
        let settingGptContent = localStorage.getItem("setting_gpt_content") || '';
        if(settingGptContent != '') {
            settingGptContent = JSON.parse(settingGptContent);
            $('input[name="object"]').val(settingGptContent.object || '');
            $('input[name="age"]').val(settingGptContent.age || '');
            $('input[name="gender"]').val(settingGptContent.gender || '');
            $('input[name="purpose_post"]').val(settingGptContent.purpose_post || '');
            if((settingGptContent.style_post || '') != '') {
                $('.style_post .style_post__item').removeClass('active')
                $('.style_post .style_post__item').each(function(){
                    let data = $(this).data('style');
                    if(data == settingGptContent.style_post) {
                        $(this).addClass('active');
                    }
                })
            }
        }
        // event modal
        $('#heading_modal_{{$field_detail}}').on('show.bs.modal', function (e) {
            let btn = $(e.relatedTarget || '#open-modal-gpt');
            editor = btn.data('editor');
            let field_detail = btn.data('field_detail');
            let field_title = btn.data('field_title');
            editor = window.editor[field_detail];
            title = $(`input[name="${field_title}"]`).val().trim();
            primary_keyword = $(`input[name="primary_keyword"]`).val();
            if(!primary_keyword){
                primary_keyword = btn.data('primary_keyword');
            }
            // console.log(primary_keyword);
            if(!title){
                alert('Bạn phải nhập tiêu đề');
                e.preventDefault();
                setTimeout( function() {
                    $('body').removeClass('modal-open');
                }, 100);
                return;
            }

            let content = editor.getData() || $(`textarea[name="${field_detail}"]`).val();
            if(!content){
                alert('Bạn phải nhập nội dung có các heading');
                e.preventDefault();
            }
            div_content = $('<div></div>').html(content);

            heading_tags = div_content.find('h1, h2, h3, h4, h5, h6');

            if(!heading_tags.length){
                alert('Bạn phải nhập nội dung có các heading');
                e.preventDefault();
            }

            // fill
            let table_body = $(this).find('table.headings tbody');
            table_body.empty();
            @php
                $type_heading = getOption('type_heading');
                $type_headings = collect($type_heading['type_heading'] ?? [])->map(function($item) {
                    return (object) $item;
                });
                $type_heading = $type_headings->pluck('title', 'key')->toArray();
                $headingByType = $type_headings->pluck('prompt', 'key')->toArray();
                // dd($headingByType);
            @endphp

            heading_tags.each((i, el) => {
                // console.log(el);
                el = $(el);

                const tr = `
                    <tr id="table_item_${i+1}" data-id="${i+1}">
                        <td class="text-center">${i+1}</td>
                        <td class="text-center" >${el.prop("tagName").toLowerCase()}
                        </td>
                        <td class="title_b">
                            <p>${el.text()}</p>
                            <textarea class="form-control detail_b" name="detail_b" style="display: none;"></textarea>
                            <input type="hidden" name="title_a" class="title_a">
                        </td>
                        <td class="lightbulb"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" width="20" height="20"><path d="M272 384c9.6-31.9 29.5-59.1 49.2-86.2l0 0c5.2-7.1 10.4-14.2 15.4-21.4c19.8-28.5 31.4-63 31.4-100.3C368 78.8 289.2 0 192 0S16 78.8 16 176c0 37.3 11.6 71.9 31.4 100.3c5 7.2 10.2 14.3 15.4 21.4l0 0c19.8 27.1 39.7 54.4 49.2 86.2H272zM192 512c44.2 0 80-35.8 80-80V416H112v16c0 44.2 35.8 80 80 80zM112 176c0 8.8-7.2 16-16 16s-16-7.2-16-16c0-61.9 50.1-112 112-112c8.8 0 16 7.2 16 16s-7.2 16-16 16c-44.2 0-80 35.8-80 80z"/></svg></td>
                        <td>
                            <select class="type form-control">
                                @foreach($type_heading as $key => $name)
                                    <option value="{{ $key }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <select class="lenght form-control">
                                <option value="{{ $general_ai['text_medium'] ?? '300' }}">Trung bình</option>
                                <option value="{{ $general_ai['text_short'] ?? '160' }}">Ngắn</option>
                                <option value="{{ $general_ai['text_long']  ?? '500' }}">Dài</option>
                            </select>
                        </td>
                        <td class="text-center">
                            <input type="checkbox" style="width: 30px; cursor:pointer" value="${i}" class="tick">
                        </td>
                        <td class="text-center status">
                            <span class="label label-default">Chưa bắt đầu</span>
                        </td>
                        <td class="text-center infomation">
                            <div class="infomation_icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="20" height="20"><path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM216 336h24V272H216c-13.3 0-24-10.7-24-24s10.7-24 24-24h48c13.3 0 24 10.7 24 24v88h8c13.3 0 24 10.7 24 24s-10.7 24-24 24H216c-13.3 0-24-10.7-24-24s10.7-24 24-24zm40-208a32 32 0 1 1 0 64 32 32 0 1 1 0-64z"/></svg>
                            </div>
                             <div class="popup_info">
                                 <div class="popup_info__content">
                                    <div class="prompt">
                                        <b>Prompt: </b>
                                        <p class="infomation_detail"></p>
                                    </div>
                                    <div class="close_popup">
                                      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" width="20" height="20"><path d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z" fill="red"/></svg>
                                    </div>
                               </div>
                            </div>
                        </td>
                    </tr>
                `
                table_body.append(tr);

            });
            $(this).find('.tick_all').trigger('change');
            loadingEffect('close', this);
        })
        // tick all event
        $('#heading_modal_{{$field_detail}} .tick_all').on('change', function(){
            let is_check = $(this).prop('checked');
            $(this).closest('table').find('tbody .tick').prop('checked', is_check);
        })
        $('body').on('click', '.close_mode', function(){
            let modal = $(this).closest('.modal');
             modal.modal('hide');
            loadingEffect('close', modal);
        });
        $('body').on('click', '.lightbulb', function(){
            let tr = $(this).closest('tr');
            tr.find('textarea').show();
            let id = parseInt(tr.data('id'));
            let pre_id = id - 1;
            let title_A = $('#table_item_'+pre_id).find('.title_b p').text();
            tr.find('.title_a').val(title_A).change();
            $(this).addClass('active');
        });
        $('body').on('click', '.save_setting', function() {
            let object = $('input[name="object"]').val();
            let age = $('input[name="age"]').val();
            let gender = $('input[name="gender"]').val();
            let purpose_post = $('input[name="purpose_post"]').val();
            let style_post = $('.style_post .style_post__item.active').data('style');
            localStorage.removeItem('setting_gpt_content');
            localStorage.setItem("setting_gpt_content", JSON.stringify({object, age, gender, purpose_post, style_post}));
            alertText('Ghi nhớ cài đặt thành công!', 'success');
        });
        $('body').on('change', '#heading_modal_{{$field_detail}} table select.type', function(){
            // console.log('gdg');
            $(this).closest('tr').find('.tick').prop('checked', true);
        })
        $('body').on('click', '.style_post .style_post__item', function(){
            $(this).parent().find('.style_post__item').removeClass('active');
            $(this).addClass('active');
        });
        $('body').on('click', '.infomation .infomation_icon', function(){
            let tr_parent = $(this).closest('tr');
            let tr_id = tr_parent.data('id')
            let type = tr_parent.find('select.type').val();
            let lenght_text = tr_parent.find('select.lenght').val();
            let current_heading = tr_parent.find('.title_b p').text();
            let current_detail = tr_parent.find('.title_b .form-control').val();
            let previous_heading = $('#table_item_'+parseInt(tr_id - 1)).find('.title_b p').text();
            let object = $('input[name="object"]').val();
            let age = $('input[name="age"]').val();
            let gender = $('input[name="gender"]').val();
            let purpose_post = $('input[name="purpose_post"]').val();
            let style_post = $('.style_post .style_post__item.active').data('style');
            let promtData = {!! json_encode($headingByType) !!};
            // console.log(promtData);
            let prompt = ''
            let heading = [];
            for(let item in promtData) {
                if(item == type) {
                    prompt = promtData[item]
                }
            }
            let outline = $.map(heading_tags, (el, i)=>{
                    return el.outerHTML;
            }).join(' ');
            prompt = prompt.replace('{title}', title)
            prompt = prompt.replace('{outline}', outline)
            prompt = prompt.replace('{heading}', current_heading)
            prompt = prompt.replace('{primary_keyword}', primary_keyword)
            prompt = prompt.replace('{domain}', '{{$domain ?? ''}}')
            prompt = prompt.replace('{content_website}', '{{ $content_website ?? ''}}')
            prompt = prompt.replace('{trademark}', '{{ $trademark ?? '' }}')
            prompt = prompt.replace('{purpose}', '{{ $purpose ?? '' }}')
            prompt = prompt.replace('{object}', object)
            prompt = prompt.replace('{age}', age)
            prompt = prompt.replace('{gender}', gender)
            prompt = prompt.replace('{purpose_post}', purpose_post)
            prompt = prompt.replace('{style_post}', style_post)
            prompt = prompt.replace('{lenght_text}', lenght_text)
            prompt = prompt.replace('{previous_heading}', previous_heading)
            prompt = prompt.replace('{current_heading}', current_heading)
            prompt = prompt.replace('{current_detail}', current_detail)
            $(this).parent().find('.popup_info .prompt p').empty();
            $(this).parent().find('.popup_info .prompt p').text(prompt);
            $(this).parent().find('.popup_info').toggleClass('active');
        });
        $('body').on('click', '.popup_info__content .close_popup', function(){
            $(this).parents('.popup_info').removeClass('active')
        });

        // generate
        $('#heading_modal_{{$field_detail}} .generate-gpt').on('click', function(){
            let yourApiKey = $('input[name="yourApiKey"]').val();
            if(!yourApiKey || yourApiKey === "") {
                alert('Bạn cần nhập API của tài khoản chatGPT tại mục Cấu hình tư duy cho AI');
                return false;
            }
            let object = $('input[name="object"]').val();
            let age = $('input[name="age"]').val();
            let gender = $('input[name="gender"]').val();
            let purpose_post = $('input[name="purpose_post"]').val();
            let style_post = $('.style_post .style_post__item.active').data('style');
            let modal = $(this).closest('.modal');
            let heading_checked = modal.find('td .tick:checked');
            let selected_heading_indexs = $.map(heading_checked, function (el, i) {
                return parseInt($(el).val());
            });
            if(!selected_heading_indexs.length){
                alert('Chưa chọn heading');
                return;
            }
            loadingEffect('open', modal);
            process = 0;
            total_process = selected_heading_indexs.length;
            let outline = $.map(heading_tags, (el, i)=>{
                    return el.outerHTML;
            }).join(' ');
            selected_heading_indexs.forEach((index, j) => {
                let el = $(heading_tags[index]);
                let heading = el.text();
                let index_tr = Number(index) + 1
                modal.find(`tbody tr:nth-child(${index_tr}) td.status`).html(`
                    <span class="label label-warning">Đang xử lý...</span>
                `)
                let type = $('body').find(`#table_item_${index_tr} select.type`)[1].value || '';
                let lenght = modal.find(`tbody tr:nth-child(${index_tr}) select.lenght`)[1].value || '';
                let title_B = modal.find('#table_item_'+index_tr).find('.title_b p').text();
                let detail_B = $('body').find(`#table_item_${index_tr} .title_b .form-control`)[1].value || '';
                let title_A = $('body').find(`#table_item_${parseInt(index_tr - 1)} .title_b p`).text();
                setTimeout(() => {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'POST',
                        data: {
                            title, heading, type, outline,object,age,gender,purpose_post,style_post,lenght,title_A,title_B,detail_B,
                            child_headings: getChildHeadings(index).join(''),
                            primary_keyword
                        },

                        url: '/admin/ajax/getContentFromChatGPT',

                        success: function (result) {
                            if(result.success == 1){
                                data = result.data;
                                ans = data.answer;
                                ans = ans.replace("\r", '').replace(new RegExp('\n+', 'g'), "</p><p>");
                                let answer_tag = $('<p></p>').html(ans);
                                el.after(answer_tag);
                                editor.setData(div_content.html());
                                modal.find(`tbody tr:eq(${index}) td.status`).html(`
                                    <span class="label label-success">Thành công</span>
                                `)
                                console.log(data.prompt)
                            }else{
                                console.log(result.message);
                                alertText(result.message, 'error');
                                modal.find(`tbody tr:eq(${index}) td.status`).html(`
                                    <span class="label label-danger">Thất bại</span>
                                `)
                            }

                            process++;
                            if(process == total_process){
                                console.log('Hoàn tất viết bài chatgpt');
                                modal.modal('hide');
                                loadingEffect('close', modal);
                            }

                        },
                        error: function (error) {
                            console.log(error);

                            process++;
                            modal.find(`tbody tr:eq(${index}) td.status`).html(`
                                <span class="label label-success">Thất bại</span>
                            `);

                            if(process == total_process){
                                console.log('Hoàn tất viết bài chatgpt');
                                modal.modal('hide');
                                loadingEffect('close', modal);
                            }
                        },
                        // async: false
                    });
                }, j * 1000);
            });

        })

        function loadingEffect(status, modal){
            if(status == 'open'){
                $(modal).find('.modal-footer button').attr('disabled', true);
                $(modal).find('.modal-footer .loading').show();
            } else {
                $(modal).find('.modal-footer button').attr('disabled', false);
                $(modal).find('.modal-footer .loading').hide();
            }
        }


        // get child heading
        function getChildHeadings(index_heading){
            console.log(index_heading)
            let par = $(heading_tags[index_heading]);

            let par_lv = par.prop("tagName").replace('H');

            let childs = [];

            for (let index = index_heading + 1; index < heading_tags.length; index++) {
                const element = heading_tags[index];
                let element_lv = $(element).prop("tagName").replace('H');
                if(element_lv <= par_lv){
                    break;
                }
                childs.push(element.outerHTML);
            }
            return childs;
        }

        let checkAutoContent = new URL(window.location.href)
        checkAutoContent = new URLSearchParams(checkAutoContent.search)
        if(checkAutoContent && checkAutoContent.get('autoContent') == 'true') {
            setTimeout(function(){
                let modal = $('.modal-table');
                modal.modal('show');
            }, 200)
        }

    });


</script>
