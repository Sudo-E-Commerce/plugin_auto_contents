@extends('Core::layouts.app')
@php
$analysis = $outlines = [];
if($ac_outline) {
    $analysis = json_decode(base64_decode($ac_outline->analysis));
    $outlines = json_decode(base64_decode($ac_outline->outlines),true);

    if(!is_array($analysis)){
        $analysis = [];
    }

    if(!is_array($outlines)){
        $outlines = [];
    }
}
@endphp
@section('content')
 <h3>Outline: <span id="outline-keyword">{{$ac_keyword->primary_keyword}}</span></h3>
<p><i class="fa fa-calendar"></i> {{ date('d/m/Y H:i', strtotime($ac_keyword->updated_at)) }}</p>
<div id="outlines" class="row">
    <div id="install-extension-status" class="col-lg-12 alert alert-danger" style="display: flex; justify-content: space-between;">
        <span style="margin-right: 15px;">Vui lòng cài đặt extension để sử dụng</span>
        <a href="{{ route('admin.download_extension') }}" {{-- download="/vendor/core/auto_content/sudo-auto-content-extension.zip" --}}>Tải và cài đặt extension trên Google Chrome</a>
    </div>
    <div id="auto-content-result">
    </div>
    <div class="col-md-12">
        <div id="outline-static" class="flex flex-wrap justify-between">
            <div class="outline-static-action">
                <button class="outline-static-action-btn active" data-top="3">Top 3</button>
                <br>
                <button class="outline-static-action-btn" data-top="10">Top 10</button>
            </div>
        </div>
    </div>
    <hr>
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading" id="outline-top-panel-heading">
                Tops
            </div>
            <div class="panel-body">
                <div id="outline-top-result">
                </div>
            </div>
            <div class="panel-footer"><a class="btn btn-default" href="{!!route('admin.ac_keywords.index')!!}"><i class="fa fa-arrow-left"></i> Quay về trang danh sách</a></div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="panel panel-default">
            <div class="panel-heading flex justify-between">
                <div class="outline-content-static">

                </div>
                <div class="outline-content-action">
                    <button class="btn-success btn-xs outline-content-action-save" title="Click để lưu outline, hệ thống sẽ tự động lưu mỗi 3 phút"><i class="fa fa-save"></i></button>
                    <button class="btn-info btn-xs outline-content-action-add" title="Click để thêm nhanh H2 cuối outline"><i class="fa fa-plus"></i></button>
                    <button class="btn-danger btn-xs outline-content-action-remove" title="Double click để xóa toàn bộ heading"><i class="fa fa-trash"></i></button>
                </div>
            </div>
            <div class="panel-body">
                <div id="outline-content">
                    @foreach($outlines as $value)
                    <div class="outline-content-item flex item-{{$value['tag'] ?? 6}}" data-tag="{{$value['tag'] ?? 6}}">
                        <select name="" class="outline-content-item-type">
                            <option value="1" {{$value['tag'] == 1 ? 'selected' : ''}}>H1</option>
                            <option value="2" {{$value['tag'] == 2 ? 'selected' : ''}}>H2</option>
                            <option value="3" {{$value['tag'] == 3 ? 'selected' : ''}}>H3</option>
                            <option value="4" {{$value['tag'] == 4 ? 'selected' : ''}}>H4</option>
                            <option value="5" {{$value['tag'] == 5 ? 'selected' : ''}}>H5</option>
                            <option value="6" {{$value['tag'] == 6 ? 'selected' : ''}}>H6</option>
                        </select>
                        <div class="outline-content-item-heading">
                            <div class="outline-content-item-heading-title" title="Double click để sửa nội dung">
                                <h3>{{$value['text'] ?? 'Heading không lấy được từ database'}}</h3>
                            </div>
                            <div class="outline-content-item-heading-edit">
                                <textarea>{{$value['text'] ?? 'Heading không lấy được từ database'}}</textarea>
                                <button>Lưu</button>
                            </div>
                        </div>
                        <div class="outline-content-item-action">
                          {{--   <button class="btn-default outline-content-item-action-drop" title="Nắm và kéo để di chuyển heading này">
                               <i class="fa fa-arrows-alt" aria-hidden="true"></i></button> --}}
                            <button class="btn-info outline-content-item-action-add" title="Thêm heading dưới heading này"><i class="fa fa-plus"></i></button>
                            <button class="btn-success outline-content-item-action-duplicate" title="Nhân bản heading này"><i class="fa fa-copy"></i></button>
                            <button class="btn-warning outline-content-item-action-levels" title="Đóng mở heading levels"><i class="fa fa-eye"></i></button>
                            <button class="btn-danger outline-content-item-action-remove" title="Double click để xóa heading này"><i class="fa fa-trash"></i></button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="panel-footer flex" style="justify-content: flex-end;">
                <a class="btn btn-primary" id="write-content-by-ai" href="{!!route('admin.posts.create')!!}?ac_keyword={{$keyword_id}}&autoContent=true"><i class="fa fa-arrow-right"></i> Viết bằng AI</a>
                <a class="btn btn-success" style="margin-left: 15px;" id="write-content-by-ai" href="{!!route('admin.posts.create')!!}?ac_keyword={{$keyword_id}}"><i class="fa fa-arrow-right"></i> Viết thường</a>
            </div>
        </div>
    </div>
</div>
@endsection
@section('foot')
	<script type="text/javascript" defer>
    $( function() {
        //render dữ liệu top nếu có sẵn từ db
        @if($analysis)
            var tops = {!!json_encode($analysis)!!};
            setTimeout(() => {
                document.dispatchEvent(new CustomEvent('SACE_render', {detail: tops}));
            }, 1000);
        @endif

        function addOutlineHeading(tag=2,text='Click đặt tiêu đề của bạn...') {
            return `<div class="outline-content-item flex item-${tag}" data-tag="${tag}">
                        <select class="outline-content-item-type">
                            <option value="1" ${(tag == 1 ? 'selected' : '')}>H1</option>
                            <option value="2" ${(tag == 2 ? 'selected' : '')}>H2</option>
                            <option value="3" ${(tag == 3 ? 'selected' : '')}>H3</option>
                            <option value="4" ${(tag == 4 ? 'selected' : '')}>H4</option>
                            <option value="5" ${(tag == 5 ? 'selected' : '')}>H5</option>
                            <option value="6" ${(tag == 6 ? 'selected' : '')}>H6</option>
                        </select>
                        <div class="outline-content-item-heading">
                            <div class="outline-content-item-heading-title" title="Double click để sửa nội dung">
                                <h3>${text}</h3>
                            </div>
                            <div class="outline-content-item-heading-edit">
                                <textarea>${text}</textarea>
                                <button>Lưu</button>
                            </div>
                        </div>
                        <div class="outline-content-item-action">
                            <button class="btn-info outline-content-item-action-add" title="Thêm heading dưới heading này"><i class="fa fa-plus"></i></button>
                            <button class="btn-success outline-content-item-action-duplicate" title="Nhân bản heading này"><i class="fa fa-copy"></i></button>
                            <button class="btn-warning outline-content-item-action-levels" title="Đóng mở heading levels"><i class="fa fa-eye"></i></button>
                            <button class="btn-danger outline-content-item-action-remove" title="Double click để xóa heading này"><i class="fa fa-trash"></i></button>
                        </div>
                    </div>`;
        }
        function outlineStatic() {
            var c1 = c2 = c3 = c4 = c5 = c6 = sum = 0;
            $('body #outline-content .outline-content-item').each(function( index ) {
                sum++;
                var tag = $(this).attr('data-tag');
                if(tag == 1) {
                    c1++;
                }else if(tag == 2) {
                    c2++
                }else if(tag == 3) {
                    c3++
                }else if(tag == 4) {
                    c4++
                }else if(tag == 5) {
                    c5++
                }else if(tag == 6) {
                    c6++
                }
            });
            var html_outline_static = `<button>All: ${sum}</button>
                    <button>H1: <span class="color-1">${c1}</span></button>
                    <button>H2: <span class="color-2">${c2}</span></button>
                    <button>H3: <span class="color-3">${c3}</span></button>
                    <button>H4: <span class="color-4">${c4}</span></button>
                    <button>H5: <span class="color-5">${c5}</span></button>
                    <button>H6: <span class="color-6">${c6}</span></button>`;
            $('.outline-content-static').html(html_outline_static);
        }
        function getDataAnalysis() {
            var tops = [];
            $('body #outline-top-result .outline-top-item').each(function( index, element ) {
                var title = $(this).attr('data-title');
                var link = $(this).attr('data-link');
                var headings = [];
                $(this).find('.outline-top-item-detail-item').each(function( i, e ) {
                    var tag = $(this).attr('data-tag');
                    var text = $(this).attr('data-text');
                    headings.push({'tag':tag,'text':text});
                });
                tops.push({'title':title,'link':link,'headings':headings});
            });
            return tops;
        }
        function getOutlines() {
            var outlines = [];
            $('body #outline-content .outline-content-item').each(function( index, element ) {
                var tag = $(this).attr('data-tag');
                var text = $(this).find('.outline-content-item-heading-title h3').text();
                console.log(tag);
                console.log(text);
                outlines.push({'tag':tag,'text':text});
            });
            return outlines;
        }
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        function saveOutline(callback) {
            var keyword_id = {{$keyword_id}};
            var analysis = getDataAnalysis();
            var outlines = getOutlines();
            console.log(outlines);
            var token = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                type: 'post',
                async: false,
                dataType: 'json',
                data:{"_token":token, keyword_id:keyword_id, analysis:analysis, outlines:outlines},
                url: '/admin/ac_outline_save',
                success:function(result){
                    $('.outline-content-action-save').prop('disabled', false);
                    $('.image-loading').remove();
                    console.log(result);
                    if (typeof callback === 'function') callback(result, true);
                },
                error: function(xhr, status, error) {
                    if (typeof callback === 'function') callback(error, false);
                },
                beforeSend:function(){

                }
            });
        }

        //Tính toán khởi tạo page khi có sẵn outline
        $( "#outline-content" ).sortable();
        outlineStatic();
        setInterval(function () {saveOutline();}, 1000*60*3);

        //Click nút top3 - top10 lọc phần thống kê
        $('body').on('click','.outline-static-action-btn', function() {
            console.log('dgfdgfdg')
            var top = $(this).attr('data-top');
            $('.outline-static-action-btn').removeClass('active');
            $(this).addClass('active');
            $('.outline-static-list').removeClass('active');
            $('.outline-static-list[data-top="'+top+'"]').addClass('active');
        });
        //Click vào các item ở mục tops
        $('body').on('click','.outline-top-item', function() {
            $('.outline-top-item').removeClass('active');
            $(this).addClass('active').focus();
        });
        //click nút lọc heading các item tops
        $('body').on('click','.outline-top-item-static button', function() {
            var h = $(this).attr('data-heading');
            $('.outline-top-item-static button').removeClass('active');
            $(this).addClass('active');
            $('.outline-top-item-detail').removeClass('active1 active2 active3 active4 active5 active6');
            $(this).closest('.outline-top-item').find('.outline-top-item-detail').addClass('active'+h);
        });
        //click nút thêm heading các item tops
        $('body').on('click','.outline-top-item-detail-item-action .plus', function() {
            var tag = $(this).closest('.outline-top-item-detail-item').attr('data-tag');
            var text = $(this).closest('.outline-top-item-detail-item').attr('data-text');
            $('#outline-content').append(addOutlineHeading(tag,text));
            $('#outline-content').sortable();
            outlineStatic();
        });
        //click nút xóa heading các item tops
        // $('body').on('dblclick','.outline-top-item-detail-item-action .minus', function() {
        $('body').on('click','.outline-top-item-detail-item-action .minus', function() {
            $(this).closest('.outline-top-item-detail-item').remove();
        });
        //click nút save outline content
        $('body').on('click','.outline-content-action-save', function() {
            $(this).prop('disabled', true);
            $(this).before('<img class="image-loading" src="/assets/img/chanhtuoi_loading_ajax.gif" width="22" height="22" />');
            saveOutline();
        });
        //click nút add outline content
        $('body').on('click','.outline-content-action-add', function() {
            $('#outline-content').append(addOutlineHeading());
            $('#outline-content').sortable();
            outlineStatic();
        });
        //click nút remove outline content
        $('body').on('dblclick','.outline-content-action-remove', function() {
            $('#outline-content').html('');
        });

        //Thay đổi select loại heading item outline content
        $('body').on('change','.outline-content-item-type', function() {
            var tag = $(this).find(":selected").val();
            console.log(tag);
            var item = $(this).closest('.outline-content-item');
            item.attr('data-tag',tag);
            item.removeClass('item-1 item-2 item-3 item-4 item-5 item-6').addClass('item-'+tag);
            setTimeout(() => {
                outlineStatic();
            }, 1000);
        });
        //Double click để sửa nội dung outline content
        $('body').on('dblclick','.outline-content-item-heading', function() {
            $(this).addClass('active').focus();
        });
        //click nút lưu sửa nội dung outline content
        $('body').on('click','.outline-content-item-heading-edit button', function() {
            var text = $(this).closest('.outline-content-item-heading-edit').find('textarea').val();
            $(this).closest('.outline-content-item-heading').removeClass('active').find('.outline-content-item-heading-title h3').html(text);
        });
        $('body').on('blur','.outline-content-item-heading-edit textarea', function() {
            var text = $(this).val();
            $(this).closest('.outline-content-item-heading').removeClass('active').find('.outline-content-item-heading-title h3').html(text);
        });
        //click nút add item outline content
        $('body').on('click','.outline-content-item-action-add', function() {
            let tag = $(this).closest('.outline-content-item').data('tag') || 2
            $(this).closest('.outline-content-item').after(addOutlineHeading(tag));
            $('#outline-content').sortable();
            outlineStatic();
        });
        //click nút duplicate item outline content
        $('body').on('click','.outline-content-item-action-duplicate', function() {
            var item = $(this).closest('.outline-content-item');
            item.clone().insertAfter(item);
            $('#outline-content').sortable();
            outlineStatic();
        });
        //click nút show/hide item outline content
        $('body').on('click','.outline-content-item-action-levels', function() {
            var item = $(this).closest('.outline-content-item');
            var tag = item.attr('data-tag');
            console.log(tag);
            while(tag < item.next().attr('data-tag')) {
                item.next().toggle();
                item = item.next();
            }
        });
        //Double click nút remove item outline content
        // $('body').on('dblclick','.outline-content-item-action-remove', function() {
        $('body').on('click','.outline-content-item-action-remove', function() {
            $(this).closest('.outline-content-item').remove();
            $('#outline-content').sortable();
            outlineStatic();
        });
        $('#write-content-by-ai').click(function(e) {
            var link = $(this).attr('href');
            e.preventDefault();
            saveOutline(function(result, success) {
                if (success) {
                    window.location = link;
                } else {
                    console.log('Lưu outline thất bại:', result);
                }
            });
        })
    } );
    </script>
@endsection
